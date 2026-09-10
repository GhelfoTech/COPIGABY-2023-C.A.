<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class productoModel extends ConectDB {
    private $conex;

    private $codigo_producto;
    private $nombre_producto;
    private $codigo_categoria;
    private $codigo_media;
    private $descripcion;
    private $costo;
    private $porcentaje_ganancia;
    private $precio;
    private $stock_actual;
    private $stock_minimo;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    public function getAllProducts() {
        try {
            $query = "SELECT p.*, c.nombre_categoria,
                COALESCE(p.costo, 0) AS costo,
                COALESCE(
                    (SELECT dc.costo_unitario FROM detalle_compra dc
                     WHERE dc.codigo_producto = p.codigo_producto
                     ORDER BY dc.codigo_compra DESC LIMIT 1),
                    0) AS costo_compra,
                COALESCE(p.porcentaje_ganancia, 0) AS porcentaje_ganancia,
                ROUND(COALESCE(
                    (SELECT dc.costo_unitario FROM detalle_compra dc
                     WHERE dc.codigo_producto = p.codigo_producto
                     ORDER BY dc.codigo_compra DESC LIMIT 1),
                    p.costo, 0) * (1 + COALESCE(p.porcentaje_ganancia, 0) / 100), 2) AS precio
                FROM producto_insumo p
                LEFT JOIN categoria c ON p.codigo_categoria = c.codigo_categoria";

            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function calculatePrice($costo, $porcentajeGanancia) {
        $costo = floatval($costo);
        $porcentajeGanancia = floatval($porcentajeGanancia);
        return round($costo * (1 + $porcentajeGanancia / 100), 2);
    }

    public function getCategories() {
        try {
            $stmt = $this->conex->prepare("SELECT codigo_categoria, nombre_categoria FROM categoria WHERE estado = 1");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) { return []; }
    }

    public function addProduct($datos) {
        try {
            $checkMedia = $this->conex->prepare("SHOW COLUMNS FROM producto_insumo LIKE 'codigo_media'");
            $checkMedia->execute();
            $hasMedia = $checkMedia->rowCount() > 0;

            if ($hasMedia && !empty($datos['codigo_media'])) {
                $query = "INSERT INTO producto_insumo (nombre_producto, codigo_categoria, codigo_media, descripcion, porcentaje_ganancia, stock_actual, stock_minimo, estado)
                    VALUES (?, ?, ?, ?, ?, ?, ?, 1)";
                $stmt = $this->conex->prepare($query);
                $stmt->execute([
                    $datos['nombre_producto'],
                    $datos['codigo_categoria'],
                    (int) $datos['codigo_media'],
                    $datos['descripcion'],
                    floatval($datos['porcentaje_ganancia'] ?? 0),
                    intval($datos['stock_actual'] ?? 0),
                    intval($datos['stock_minimo'] ?? 0)
                ]);
            } else {
                $query = "INSERT INTO producto_insumo (nombre_producto, codigo_categoria, descripcion, porcentaje_ganancia, stock_actual, stock_minimo, estado)
                    VALUES (?, ?, ?, ?, ?, ?, 1)";
                $stmt = $this->conex->prepare($query);
                $stmt->execute([
                    $datos['nombre_producto'],
                    $datos['codigo_categoria'],
                    $datos['descripcion'],
                    floatval($datos['porcentaje_ganancia'] ?? 0),
                    intval($datos['stock_actual'] ?? 0),
                    intval($datos['stock_minimo'] ?? 0)
                ]);
            }

            $this->calcularYGuardarPrecio((int) $this->conex->lastInsertId());
            return ["status" => "success"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function updateProduct($id, $datos) {
        try {
            $checkMedia = $this->conex->prepare("SHOW COLUMNS FROM producto_insumo LIKE 'codigo_media'");
            $checkMedia->execute();
            $hasMedia = $checkMedia->rowCount() > 0;

            if ($hasMedia) {
                $codigoMedia = !empty($datos['codigo_media']) ? (int) $datos['codigo_media'] : null;
                $query = "UPDATE producto_insumo SET nombre_producto = ?, codigo_categoria = ?, codigo_media = ?, descripcion = ?, porcentaje_ganancia = ?, stock_actual = ?, stock_minimo = ?, estado = ? WHERE codigo_producto = ?";
                $stmt = $this->conex->prepare($query);
                $stmt->execute([
                    $datos['nombre_producto'],
                    $datos['codigo_categoria'],
                    $codigoMedia,
                    $datos['descripcion'],
                    floatval($datos['porcentaje_ganancia'] ?? 0),
                    intval($datos['stock_actual'] ?? 0),
                    intval($datos['stock_minimo'] ?? 0),
                    intval($datos['estado'] ?? 1),
                    $id
                ]);
            } else {
                $query = "UPDATE producto_insumo SET nombre_producto = ?, codigo_categoria = ?, descripcion = ?, porcentaje_ganancia = ?, stock_actual = ?, stock_minimo = ?, estado = ? WHERE codigo_producto = ?";
                $stmt = $this->conex->prepare($query);
                $stmt->execute([
                    $datos['nombre_producto'],
                    $datos['codigo_categoria'],
                    $datos['descripcion'],
                    floatval($datos['porcentaje_ganancia'] ?? 0),
                    intval($datos['stock_actual'] ?? 0),
                    intval($datos['stock_minimo'] ?? 0),
                    intval($datos['estado'] ?? 1),
                    $id
                ]);
            }

            $this->calcularYGuardarPrecio($id);
            return ["status" => "success"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function deleteProduct($id) {
        try {
            $stmt = $this->conex->prepare("UPDATE producto_insumo SET estado = 0 WHERE codigo_producto = ?");
            return ["status" => $stmt->execute([$id]) ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    // Calcula el precio de venta (costo de la última compra o costo manual
    // multiplicado por (1 + porcentaje_ganancia/100)) y lo guarda en la
    // columna precio del producto para que otros módulos lo consulten directo.
    public function calcularYGuardarPrecio(int $codigoProducto): void {
        try {
            $stmt = $this->conex->prepare(
                "UPDATE producto_insumo p
                    SET precio = ROUND(COALESCE(
                            (SELECT dc.costo_unitario FROM detalle_compra dc
                             WHERE dc.codigo_producto = p.codigo_producto
                             ORDER BY dc.codigo_compra DESC LIMIT 1),
                            p.costo, 0) * (1 + COALESCE(p.porcentaje_ganancia, 0) / 100), 2)
                 WHERE codigo_producto = ?"
            );
            $stmt->execute([$codigoProducto]);
        } catch (PDOException $e) {
            // Si falla el recálculo, se ignora para no bloquear la operación principal
        }
    }

    // Recalcula y guarda el precio de varios productos a la vez.
    public function recalcularPrecios(array $codigosProducto): void {
        foreach (array_unique($codigosProducto) as $codigo) {
            $this->calcularYGuardarPrecio((int) $codigo);
        }
    }
}
