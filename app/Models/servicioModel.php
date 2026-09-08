<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class servicioModel extends ConectDB {
    private $conex;

    private $codigo_servicio;
    private $nombre_servicio;
    private $descripcion;
    private $precio;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    public function getAllServicios() {
        try {
            $stmt = $this->conex->prepare("SELECT * FROM servicio ORDER BY codigo_servicio DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function addServicio($datos, $materiales = []) {
        try {
            $this->conex->beginTransaction();

            $query = "INSERT INTO servicio (nombre_servicio, descripcion, precio, estado) VALUES (?, ?, ?, 1)";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([
                trim($datos['nombre_servicio']),
                trim($datos['descripcion']),
                $datos['precio']
            ]);

            $idServicio = $this->conex->lastInsertId();

            if (!empty($materiales)) {
                $queryMat = "INSERT INTO servicio_material (codigo_producto, codigo_servicio, cantidad_usada) VALUES (?, ?, ?)";
                $stmtMat = $this->conex->prepare($queryMat);
                foreach ($materiales as $mat) {
                    $stmtMat->execute([$mat['codigo_producto'], $idServicio, $mat['cantidad_usada']]);
                }
            }

            $this->conex->commit();
            return ["status" => "success"];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) $this->conex->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function updateServicio($id, $datos, $materiales = []) {
        try {
            $this->conex->beginTransaction();

            $query = "UPDATE servicio SET nombre_servicio = ?, descripcion = ?, precio = ?, estado = ? WHERE codigo_servicio = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([
                trim($datos['nombre_servicio']),
                trim($datos['descripcion']),
                $datos['precio'],
                $datos['estado'],
                $id
            ]);

            // Actualizar materiales: eliminamos los anteriores e insertamos los nuevos
            $stmtDel = $this->conex->prepare("DELETE FROM servicio_material WHERE codigo_servicio = ?");
            $stmtDel->execute([$id]);

            if (!empty($materiales)) {
                $queryMat = "INSERT INTO servicio_material (codigo_producto, codigo_servicio, cantidad_usada) VALUES (?, ?, ?)";
                $stmtMat = $this->conex->prepare($queryMat);
                foreach ($materiales as $mat) {
                    $stmtMat->execute([$mat['codigo_producto'], $id, $mat['cantidad_usada']]);
                }
            }

            $this->conex->commit();
            return ["status" => "success"];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) $this->conex->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function deleteServicio($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE servicio SET estado = 0 WHERE codigo_servicio = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function getMaterialesByServicio($id) {
        try {
            $query = "SELECT sm.*, p.nombre_producto 
                      FROM servicio_material sm 
                      INNER JOIN producto_insumo p ON sm.codigo_producto = p.codigo_producto 
                      WHERE sm.codigo_servicio = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}