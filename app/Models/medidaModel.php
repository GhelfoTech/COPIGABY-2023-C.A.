<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class medidaModel extends ConectDB {
    private $conex;

    private $codigo_media;
    private $nombre;
    private $abreviatura;
    private $cantidad_unidad;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    /**
     * Obtiene todas las unidades de medida.
     */
    public function getAllMedidas() {
        try {
            $stmt = $this->conex->prepare("SELECT * FROM unidad_medida ORDER BY codigo_media DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Obtiene las unidades de medida activas para selectors.
     * Incluye cantidad_unidad (unidades por caja) para cálculos de stock.
     */
     public function getMedidasActivas() {
         try {
             $query = "SELECT codigo_media, nombre, abreviatura, cantidad_unidad FROM unidad_medida WHERE estado = 1 ORDER BY nombre ASC";
             $stmt = $this->conex->prepare($query);
             $stmt->execute();
             $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
             foreach ($rows as &$row) {
                 if (!isset($row['abreviatura'])) $row['abreviatura'] = null;
                 $row['cantidad_unidad'] = (int) ($row['cantidad_unidad'] ?? 1);
             }
             return $rows;
         } catch (PDOException $e) {
             return [];
         }
     }

    /**
     * Obtiene todas las unidades con abreviatura y cantidad_unidad.
     */
    public function getAllMedidasFull() {
        try {
            $query = "SELECT codigo_media, nombre, abreviatura, cantidad_unidad, estado FROM unidad_medida ORDER BY codigo_media DESC";
            $stmt = $this->conex->prepare($query);
            if (!$stmt) {
                $stmt = $this->conex->query($query);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$row) {
                if (!isset($row['abreviatura'])) $row['abreviatura'] = null;
                if (!isset($row['cantidad_unidad'])) $row['cantidad_unidad'] = 1;
                $row['cantidad_unidad'] = (int) $row['cantidad_unidad'];
            }
            return $rows;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function addMedida($nombre, $abreviatura = null, $cantidadUnidad = 1) {
        try {
            $nombre = trim((string) $nombre);
            $abreviatura = $abreviatura !== null ? trim((string) $abreviatura) : null;
            $cantidadUnidad = (int) $cantidadUnidad;

            if ($nombre === '') {
                return ['status' => 'error', 'message' => 'El nombre es obligatorio'];
            }

            $query = 'INSERT INTO unidad_medida (nombre, abreviatura, cantidad_unidad, estado) VALUES (?, ?, ?, 1)';
            $stmt = $this->conex->prepare($query);
            $ok = $stmt->execute([$nombre, $abreviatura, $cantidadUnidad]);
            if ($ok) {
                return true;
            }
            return ['status' => 'error', 'message' => 'No se pudo registrar la unidad'];
        } catch (PDOException $e) {
            $mensaje = $e->getMessage();
            if (stripos($mensaje, 'default value') !== false || stripos($mensaje, 'codigo_media') !== false) {
                try {
                    $stmtMax = $this->conex->query('SELECT MAX(codigo_media) AS maximo FROM unidad_medida');
                    $maximo = (int) $stmtMax->fetchColumn();
                    $nuevoCodigo = $maximo + 1;

                    $query2 = 'INSERT INTO unidad_medida (codigo_media, nombre, abreviatura, cantidad_unidad, estado) VALUES (?, ?, ?, ?, 1)';
                    $stmt2 = $this->conex->prepare($query2);
                    $ok2 = $stmt2->execute([$nuevoCodigo, $nombre, $abreviatura, $cantidadUnidad]);
                    if ($ok2) {
                        return true;
                    }
                    return ['status' => 'error', 'message' => 'No se pudo registrar la unidad con código manual'];
                } catch (PDOException $e2) {
                    return ['status' => 'error', 'message' => $e2->getMessage()];
                }
            }
            return ['status' => 'error', 'message' => $mensaje];
        }
    }

    public function updateMedida($id, $nombre, $abreviatura = null, $cantidadUnidad = 1, $estado) {
        try {
            $query = "UPDATE unidad_medida SET nombre = ?, abreviatura = ?, cantidad_unidad = ?, estado = ? WHERE codigo_media = ?";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([
                $nombre,
                $abreviatura,
                (int) $cantidadUnidad,
                (int) $estado,
                (int) $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Desactivación lógica (borrado).
     */
    public function deleteMedida($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE unidad_medida SET estado = 0 WHERE codigo_media = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Obtiene una unidad de medida específica por su código.
     */
    public function getMedidaById($id) {
        try {
            $stmt = $this->conex->prepare("SELECT codigo_media, nombre, abreviatura, cantidad_unidad, estado FROM unidad_medida WHERE codigo_media = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }
}
