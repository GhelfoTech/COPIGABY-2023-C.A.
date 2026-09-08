<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class monedaModel extends ConectDB {
    private $conex;

    private $codigo_moneda;
    private $nombre_moneda;
    private $simbolo;
    private $codigo_tasa;
    private $estado;
    private $activa;

    /** @var bool|null Indica si la tabla moneda tiene columna `activa` */
    private ?bool $tieneColumnaActiva = null;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    private function logPdoError(string $context, PDOException $e): void {
        error_log(sprintf(
            '[monedaModel::%s] %s | SQLSTATE=%s',
            $context,
            $e->getMessage(),
            $e->getCode()
        ));
    }

    /**
     * Verifica si existe la columna `activa` en la tabla moneda.
     */
    private function hasActivaColumn(): bool {
        if ($this->tieneColumnaActiva !== null) {
            return $this->tieneColumnaActiva;
        }

        try {
            $stmt = $this->conex->query("SHOW COLUMNS FROM moneda LIKE 'activa'");
            $this->tieneColumnaActiva = $stmt !== false && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            $this->tieneColumnaActiva = false;
        }

        return $this->tieneColumnaActiva;
    }

    /**
     * Obtiene todas las monedas registradas con su tasa de cambio vigente.
     */
    public function getAllMonedas(): array {
        try {
            $activaSelect = $this->hasActivaColumn() ? ', m.activa' : ', 0 AS activa';

            $query = "SELECT m.codigo_moneda, m.nombre_moneda, m.simbolo, m.codigo_tasa, m.estado,
                             t.monto_bolivares AS tasa_cambio, t.fecha AS fecha_tasa
                             {$activaSelect}
                      FROM moneda m
                      INNER JOIN tasa_cambio t ON m.codigo_tasa = t.codigo_tasa
                      ORDER BY m.activa DESC, m.codigo_moneda DESC";

            if (!$this->hasActivaColumn()) {
                $query = "SELECT m.codigo_moneda, m.nombre_moneda, m.simbolo, m.codigo_tasa, m.estado,
                                 t.monto_bolivares AS tasa_cambio, t.fecha AS fecha_tasa,
                                 0 AS activa
                          FROM moneda m
                          INNER JOIN tasa_cambio t ON m.codigo_tasa = t.codigo_tasa
                          ORDER BY m.codigo_moneda DESC";
            }

            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            $monedas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $activa = $this->getMonedaActiva();
            if ($activa && !$this->hasActivaColumn()) {
                foreach ($monedas as &$moneda) {
                    $moneda['activa'] = ((int) $moneda['codigo_moneda'] === (int) $activa['codigo_moneda']) ? 1 : 0;
                }
                unset($moneda);
            }

            return $monedas;
        } catch (PDOException $e) {
            $this->logPdoError('getAllMonedas', $e);
            return [];
        }
    }

    /**
     * Obtiene una moneda por su código con datos de tasa.
     */
    public function getMonedaById(int $id): ?array {
        try {
            $stmt = $this->conex->prepare(
                'SELECT m.codigo_moneda, m.nombre_moneda, m.simbolo, m.codigo_tasa, m.estado,
                        t.monto_bolivares AS tasa_cambio, t.fecha AS fecha_tasa
                 FROM moneda m
                 INNER JOIN tasa_cambio t ON m.codigo_tasa = t.codigo_tasa
                 WHERE m.codigo_moneda = ?'
            );
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            $this->logPdoError('getMonedaById', $e);
            return null;
        }
    }

    /**
     * Moneda seleccionada como referencia global del sistema (Pedidos, Compras, etc.).
     */
    public function getMonedaActiva(): ?array {
        try {
            if ($this->hasActivaColumn()) {
                $stmt = $this->conex->prepare(
                    'SELECT m.codigo_moneda, m.nombre_moneda, m.simbolo, m.codigo_tasa, m.estado,
                            t.monto_bolivares AS tasa_cambio, t.fecha AS fecha_tasa
                     FROM moneda m
                     INNER JOIN tasa_cambio t ON m.codigo_tasa = t.codigo_tasa
                     WHERE m.estado = 1 AND m.activa = 1
                     ORDER BY m.codigo_moneda DESC
                     LIMIT 1'
                );
                $stmt->execute();
                $moneda = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($moneda) {
                    return $moneda;
                }
            }

            $stmtFallback = $this->conex->prepare(
                'SELECT m.codigo_moneda, m.nombre_moneda, m.simbolo, m.codigo_tasa, m.estado,
                        t.monto_bolivares AS tasa_cambio, t.fecha AS fecha_tasa
                 FROM moneda m
                 INNER JOIN tasa_cambio t ON m.codigo_tasa = t.codigo_tasa
                 WHERE m.estado = 1
                 ORDER BY m.codigo_moneda ASC
                 LIMIT 1'
            );
            $stmtFallback->execute();
            $row = $stmtFallback->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            $this->logPdoError('getMonedaActiva', $e);
            return null;
        }
    }

    /**
     * Tasa de cambio (Bs) de la moneda activa global.
     */
    public function getTasaActual(): float {
        $moneda = $this->getMonedaActiva();
        return $moneda ? (float) $moneda['tasa_cambio'] : 1.0;
    }

    /**
     * Registra una moneda e inserta su tasa inicial en tasa_cambio (transaccional).
     */
    public function addMoneda(array $datos): array {
        $nombre = trim((string) ($datos['nombre_moneda'] ?? ''));
        $simbolo = trim((string) ($datos['simbolo'] ?? ''));
        $tasa = (float) ($datos['tasa_cambio'] ?? 0);

        if ($nombre === '' || $simbolo === '') {
            return ['status' => 'error', 'message' => 'Nombre y símbolo son obligatorios'];
        }
        if ($tasa <= 0) {
            return ['status' => 'error', 'message' => 'La tasa de cambio debe ser mayor a cero'];
        }

        try {
            $this->conex->beginTransaction();

            $stmtTasa = $this->conex->prepare(
                'INSERT INTO tasa_cambio (fecha, monto_bolivares) VALUES (NOW(), ?)'
            );
            $stmtTasa->execute([$tasa]);
            $idTasa = (int) $this->conex->lastInsertId();

            if ($this->hasActivaColumn()) {
                $stmt = $this->conex->prepare(
                    'INSERT INTO moneda (nombre_moneda, simbolo, codigo_tasa, estado, activa)
                     VALUES (?, ?, ?, 1, 0)'
                );
            } else {
                $stmt = $this->conex->prepare(
                    'INSERT INTO moneda (nombre_moneda, simbolo, codigo_tasa, estado)
                     VALUES (?, ?, ?, 1)'
                );
            }

            $stmt->execute([$nombre, $simbolo, $idTasa]);

            $hayActiva = $this->getMonedaActiva();
            if (!$hayActiva) {
                $nuevoId = (int) $this->conex->lastInsertId();
                $this->marcarComoActivaInterno($nuevoId);
            }

            $this->conex->commit();

            return ['status' => 'success', 'message' => 'Moneda registrada correctamente'];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $this->logPdoError('addMoneda', $e);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Actualiza datos de moneda y su tasa vinculada.
     */
    public function updateMoneda(int $id, array $datos): array {
        if ($id <= 0) {
            return ['status' => 'error', 'message' => 'Moneda no válida'];
        }

        $nombre = trim((string) ($datos['nombre_moneda'] ?? ''));
        $simbolo = trim((string) ($datos['simbolo'] ?? ''));
        $tasa = (float) ($datos['tasa_cambio'] ?? 0);
        $estado = (int) ($datos['estado'] ?? 1) ? 1 : 0;

        if ($nombre === '' || $simbolo === '') {
            return ['status' => 'error', 'message' => 'Nombre y símbolo son obligatorios'];
        }
        if ($tasa <= 0) {
            return ['status' => 'error', 'message' => 'La tasa de cambio debe ser mayor a cero'];
        }

        try {
            $this->conex->beginTransaction();

            $monedaActual = $this->getMonedaById($id);
            if (!$monedaActual) {
                throw new PDOException('La moneda indicada no existe');
            }

            // Inserta una tasa nueva y la vincula solo a esta moneda para evitar
            // actualizaciones masivas cuando varias monedas comparten codigo_tasa.
            $stmtTasa = $this->conex->prepare(
                'INSERT INTO tasa_cambio (fecha, monto_bolivares) VALUES (NOW(), ?)'
            );
            $stmtTasa->execute([$tasa]);
            $idTasaNueva = (int) $this->conex->lastInsertId();

            if ($idTasaNueva <= 0) {
                throw new PDOException('No se pudo registrar la nueva tasa de cambio');
            }

            $stmt = $this->conex->prepare(
                'UPDATE moneda
                 SET nombre_moneda = ?, simbolo = ?, estado = ?, codigo_tasa = ?
                 WHERE codigo_moneda = ?'
            );
            $stmt->execute([$nombre, $simbolo, $estado, $idTasaNueva, $id]);

            if ($estado === 0 && $this->hasActivaColumn()) {
                $stmtClear = $this->conex->prepare(
                    'UPDATE moneda SET activa = 0 WHERE codigo_moneda = ?'
                );
                $stmtClear->execute([$id]);
            }

            $this->conex->commit();

            return ['status' => 'success', 'message' => 'Moneda actualizada correctamente'];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $this->logPdoError('updateMoneda', $e);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Marca una moneda como la activa global del sistema (solo una a la vez).
     */
    public function setMonedaActiva(int $id): array {
        if ($id <= 0) {
            return ['status' => 'error', 'message' => 'Moneda no válida'];
        }

        if (!$this->hasActivaColumn()) {
            return [
                'status'  => 'error',
                'message' => 'Agregue la columna activa en moneda: ALTER TABLE moneda ADD activa tinyint(1) NOT NULL DEFAULT 0;',
            ];
        }

        $moneda = $this->getMonedaById($id);
        if (!$moneda) {
            return ['status' => 'error', 'message' => 'La moneda indicada no existe'];
        }
        if ((int) $moneda['estado'] !== 1) {
            return ['status' => 'error', 'message' => 'Solo se puede activar una moneda habilitada'];
        }

        try {
            $this->conex->beginTransaction();
            $this->marcarComoActivaInterno($id);
            $this->conex->commit();

            return [
                'status'  => 'success',
                'message' => 'Moneda "' . $moneda['nombre_moneda'] . '" establecida como referencia global',
            ];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $this->logPdoError('setMonedaActiva', $e);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Desactivación lógica de una moneda.
     */
    public function deleteMoneda(int $id): array {
        try {
            $this->conex->beginTransaction();

            if ($this->hasActivaColumn()) {
                $stmtClear = $this->conex->prepare(
                    'UPDATE moneda SET estado = 0, activa = 0 WHERE codigo_moneda = ?'
                );
            } else {
                $stmtClear = $this->conex->prepare(
                    'UPDATE moneda SET estado = 0 WHERE codigo_moneda = ?'
                );
            }
            $stmtClear->execute([$id]);

            $this->conex->commit();

            return ['status' => 'success', 'message' => 'Moneda desactivada correctamente'];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) {
                $this->conex->rollBack();
            }
            $this->logPdoError('deleteMoneda', $e);
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    /**
     * Establece una moneda como activa global (uso interno transaccional).
     */
    private function marcarComoActivaInterno(int $id): void {
        if ($this->hasActivaColumn()) {
            $stmtReset = $this->conex->prepare('UPDATE moneda SET activa = 0');
            $stmtReset->execute();

            $stmtActiva = $this->conex->prepare(
                'UPDATE moneda SET activa = 1, estado = 1 WHERE codigo_moneda = ?'
            );
            $stmtActiva->execute([$id]);
            return;
        }

        $stmtReset = $this->conex->prepare(
            'UPDATE moneda SET estado = 1 WHERE estado = 1'
        );
        $stmtReset->execute();

        $stmtActiva = $this->conex->prepare(
            'UPDATE moneda SET estado = 1 WHERE codigo_moneda = ?'
        );
        $stmtActiva->execute([$id]);
    }
}
