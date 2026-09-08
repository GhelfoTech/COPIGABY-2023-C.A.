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
            $query = "SELECT codigo_media, nombre, cantidad_unidad FROM unidad_medida WHERE estado = 1 ORDER BY nombre ASC";
            $checkStmt = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'abreviatura'");
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                $query = "SELECT codigo_media, nombre, abreviatura, cantidad_unidad FROM unidad_medida WHERE estado = 1 ORDER BY nombre ASC";
            }
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
            $query = "SELECT codigo_media, nombre, estado FROM unidad_medida ORDER BY codigo_media DESC";
            $checkStmt = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'abreviatura'");
            $checkStmt->execute();
            $hasAbreviatura = $checkStmt->rowCount() > 0;
            $checkStmt2 = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'cantidad_unidad'");
            $checkStmt2->execute();
            $hasCantidadUnidad = $checkStmt2->rowCount() > 0;

            $cols = ['codigo_media', 'nombre'];
            if ($hasAbreviatura) $cols[] = 'abreviatura';
            if ($hasCantidadUnidad) $cols[] = 'cantidad_unidad';
            $cols[] = 'estado';
            $query = "SELECT " . implode(', ', $cols) . " FROM unidad_medida ORDER BY codigo_media DESC";

            $stmt = $this->conex->prepare($query);
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
            $cols = ['nombre'];
            $placeholders = ['?'];
            $params = [$nombre];
            $checkAbrev = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'abreviatura'");
            $checkAbrev->execute();
            if ($checkAbrev->rowCount() > 0) {
                $cols[] = 'abreviatura';
                $placeholders[] = '?';
                $params[] = $abreviatura;
            }
            $checkCant = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'cantidad_unidad'");
            $checkCant->execute();
            if ($checkCant->rowCount() > 0) {
                $cols[] = 'cantidad_unidad';
                $placeholders[] = '?';
                $params[] = (int) $cantidadUnidad;
            }
            $cols[] = 'estado';
            $placeholders[] = '1';
            $query = "INSERT INTO unidad_medida (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateMedida($id, $nombre, $abreviatura = null, $cantidadUnidad = 1, $estado) {
        try {
            $sets = ['nombre = ?'];
            $params = [$nombre];
            $checkAbrev = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'abreviatura'");
            $checkAbrev->execute();
            if ($checkAbrev->rowCount() > 0) {
                $sets[] = 'abreviatura = ?';
                $params[] = $abreviatura;
            }
            $checkCant = $this->conex->prepare("SHOW COLUMNS FROM unidad_medida LIKE 'cantidad_unidad'");
            $checkCant->execute();
            if ($checkCant->rowCount() > 0) {
                $sets[] = 'cantidad_unidad = ?';
                $params[] = (int) $cantidadUnidad;
            }
            $sets[] = 'estado = ?';
            $params[] = $estado;
            $params[] = $id;
            $query = "UPDATE unidad_medida SET " . implode(', ', $sets) . " WHERE codigo_media = ?";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute($params);
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
