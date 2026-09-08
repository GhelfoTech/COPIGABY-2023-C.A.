<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class compraModel extends ConectDB {
    private $conex;

    private $codigo_compra;
    private $codigo_proveedor;
    private $cedula_usuario;
    private $numero_factura_proveedor;
    private $fecha_compra;
    private $monto_total;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    /**
     * Obtiene todas las compras registradas con información de proveedor y usuario.
     */
    public function getAllCompras() {
        try {
            $query = "SELECT c.*, p.razon_social AS nombre_proveedor, u.nombre_usuario 
                      FROM compra c
                      INNER JOIN proveedor p ON c.codigo_proveedor = p.codigo_proveedor
                      INNER JOIN usuario u ON c.cedula_usuario = u.cedula_usuario
                      ORDER BY c.codigo_compra DESC";
            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Registra una compra completa con sus detalles y actualiza stock.
     */
    public function addCompra($datos, $items) {
        try {
            $this->conex->beginTransaction();

            // 1. Insertar Cabecera usando cedula_usuario
            $query = "INSERT INTO compra (codigo_proveedor, cedula_usuario, numero_factura_proveedor, fecha_compra, monto_total, estado) 
                      VALUES (?, ?, ?, ?, ?, 1)";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([
                $datos['codigo_proveedor'],
                $datos['cedula_usuario'],
                $datos['numero_factura_proveedor'],
                $datos['fecha_compra'],
                $datos['monto_total']
            ]);

            $codigoCompra = $this->conex->lastInsertId();

            // 2. Insertar Detalles y Actualizar Stock
            $queryDetalle = "INSERT INTO detalle_compra (codigo_compra, codigo_producto, cantidad, costo_unitario, subtotal) 
                             VALUES (?, ?, ?, ?, ?)";
            $stmtDetalle = $this->conex->prepare($queryDetalle);

            $queryStock = "UPDATE producto_insumo SET stock_actual = stock_actual + ? 
                           WHERE codigo_producto = ?";
            $stmtStock = $this->conex->prepare($queryStock);

            $productoModel = new productoModel();

            foreach ($items as $item) {
                $subtotal = $item['cantidad'] * $item['costo'];
                
                // Guardar detalle
                $stmtDetalle->execute([
                    $codigoCompra,
                    $item['codigo_producto'],
                    $item['cantidad'],
                    $item['costo'],
                    $subtotal
                ]);

                // Actualizar inventario (sumar stock únicamente)
                $stmtStock->execute([
                    $item['cantidad'],
                    $item['codigo_producto']
                ]);

                // Recalcular y guardar el precio de venta del producto afectado
                $productoModel->calcularYGuardarPrecio((int) $item['codigo_producto']);
            }

            $this->conex->commit();
            return ["status" => "success"];
        } catch (PDOException $e) {
            if ($this->conex->inTransaction()) $this->conex->rollBack();
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Actualiza los datos modificables de una compra.
     */
    public function updateCompra(int $id, array $datos) {
        try {
            $query = "UPDATE compra SET codigo_proveedor = ?, numero_factura_proveedor = ?,
                      fecha_compra = ?, monto_total = ?, estado = ?
                      WHERE codigo_compra = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->bindValue(1, (int) $datos['codigo_proveedor'], PDO::PARAM_INT);
            $stmt->bindValue(2, $datos['numero_factura_proveedor']);
            $stmt->bindValue(3, $datos['fecha_compra']);
            $stmt->bindValue(4, $datos['monto_total']);
            $stmt->bindValue(5, (int) $datos['estado'], PDO::PARAM_INT);
            $stmt->bindValue(6, $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ["status" => "success", "message" => "Compra actualizada con éxito"];
            }
            return ["status" => "error", "message" => "No se pudo actualizar"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Anulación lógica de una compra.
     */
    public function deleteCompra($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE compra SET estado = 0 WHERE codigo_compra = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    /**
     * Obtiene proveedores para el selector del modal.
     */
    public function getProviders() {
        try {
            $stmt = $this->conex->prepare("SELECT codigo_proveedor, razon_social FROM proveedor WHERE estado = 1");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Obtiene productos para el selector de la compra.
     */
    public function getProducts() {
        try {
            $query = "SELECT p.codigo_producto, p.nombre_producto,
                             COALESCE(
                                 (SELECT dc.costo_unitario FROM detalle_compra dc
                                  WHERE dc.codigo_producto = p.codigo_producto
                                  ORDER BY dc.codigo_compra DESC LIMIT 1),
                             0) AS costo
                      FROM producto_insumo p WHERE p.estado = 1";
            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    /**
     * Obtiene la cabecera detallada de una compra específica.
     */
    public function getCompraById($id) {
        try {
            $query = "SELECT c.*, p.razon_social, p.rif_proveedor, p.telefono, p.direccion, u.nombre_usuario 
                      FROM compra c
                      INNER JOIN proveedor p ON c.codigo_proveedor = p.codigo_proveedor
                      INNER JOIN usuario u ON c.cedula_usuario = u.cedula_usuario
                      WHERE c.codigo_compra = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return null; }
    }

    /**
     * Obtiene los productos (puente) de una compra específica.
     */
    public function getItemsByCompra($id) {
        try {
            $query = "SELECT dc.*, p.nombre_producto 
                      FROM detalle_compra dc
                      INNER JOIN producto_insumo p ON dc.codigo_producto = p.codigo_producto
                      WHERE dc.codigo_compra = ?";
            $stmt = $this->conex->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }
}
