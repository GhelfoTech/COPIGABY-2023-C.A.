<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class proveedorModel extends ConectDB {
    private $conex;

    private $codigo_proveedor;
    private $rif_proveedor;
    private $razon_social;
    private $telefono;
    private $correo;
    private $direccion;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    /**
     * Obtiene todos los proveedores.
     */
    public function getAllProveedores() {
        try {
            $stmt = $this->conex->prepare("SELECT * FROM proveedor ORDER BY codigo_proveedor DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Registra un nuevo proveedor.
     */
    public function addProveedor($datos) {
        try {
            $query = "INSERT INTO proveedor (rif_proveedor, razon_social, telefono, correo, direccion, estado) 
                      VALUES (?, ?, ?, ?, ?, 1)";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([
                $datos['rif_proveedor'],
                $datos['razon_social'],
                $datos['telefono'],
                $datos['correo'],
                $datos['direccion']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza un proveedor existente.
     */
    public function updateProveedor($id, $datos) {
        try {
            $query = "UPDATE proveedor SET rif_proveedor = ?, razon_social = ?, telefono = ?, correo = ?, direccion = ?, estado = ? 
                      WHERE codigo_proveedor = ?";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([
                $datos['rif_proveedor'],
                $datos['razon_social'],
                $datos['telefono'],
                $datos['correo'],
                $datos['direccion'],
                $datos['estado'],
                $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Desactivación lógica (borrado).
     */
    public function deleteProveedor($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE proveedor SET estado = 0 WHERE codigo_proveedor = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}