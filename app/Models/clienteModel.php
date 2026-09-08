<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class clienteModel extends ConectDB {
    private $conex;

    private $cedula_cliente;
    private $nombre;
    private $telefono;
    private $correo;
    private $direccion;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    /**
     * Obtiene todos los clientes registrados.
     */
    public function getAllClientes() {
        try {
            $stmt = $this->conex->prepare("SELECT * FROM cliente ORDER BY nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Registra un nuevo cliente.
     */
    public function addCliente($datos) {
        try {
            $query = "INSERT INTO cliente (cedula_cliente, nombre, telefono, correo, direccion, estado) 
                      VALUES (?, ?, ?, ?, ?, 1)";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([
                $datos['cedula_cliente'],
                $datos['nombre'],
                $datos['telefono'],
                $datos['correo'],
                $datos['direccion']
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza la información de un cliente.
     */
    public function updateCliente($idActual, $datos) {
        try {
            $query = "UPDATE cliente SET cedula_cliente = ?, nombre = ?, telefono = ?, correo = ?, direccion = ?, estado = ? 
                      WHERE cedula_cliente = ?";
            $stmt = $this->conex->prepare($query);
            return $stmt->execute([
                $datos['cedula_cliente'],
                $datos['nombre'],
                $datos['telefono'],
                $datos['correo'],
                $datos['direccion'],
                (int) $datos['estado'],
                $idActual
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Desactivación lógica (borrado) de un cliente.
     */
    public function deleteCliente($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE cliente SET estado = 0 WHERE cedula_cliente = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}