<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class ivaModel extends ConectDB {
    private $conex;

    private $codigo_iva;
    private $porcentaje_iva;
    private $fecha;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    /**
     * Obtiene todos los registros de IVA.
     */
    public function getAllIvas() {
        try {
            $stmt = $this->conex->prepare("SELECT codigo_IVA, porcentaje_iva, fecha, estado FROM iva ORDER BY fecha DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Registra un nuevo porcentaje de IVA.
     */
    public function addIva($porcentaje_iva) {
        try {
            $query = "INSERT INTO iva (porcentaje_iva, fecha, estado) VALUES (?, NOW(), 1)";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([$porcentaje_iva]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza un registro de IVA existente.
     */
    public function updateIva($id, $porcentaje_iva, $estado) {
        try {
            $query = "UPDATE iva SET porcentaje_iva = ?, estado = ? WHERE codigo_IVA = ?";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([$porcentaje_iva, $estado, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Desactivación lógica (borrado).
     */
    public function deleteIva($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE iva SET estado = 0 WHERE codigo_IVA = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}