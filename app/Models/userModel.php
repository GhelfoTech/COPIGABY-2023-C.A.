<?php

namespace App\models;

use App\config\ConectDB;
use PDO;
use PDOException;

class userModel extends ConectDB {

    private $conex;

    private $cedula_usuario;
    private $telefono;
    private $nombre_usuario;
    private $password;
    private $codigo_rol;
    private $estado;

    public function __construct() {
        parent::__construct();
        $this->conex = $this->getConnection();
    }

    public function getAllUsers() {
        try {
            $query = "SELECT u.cedula_usuario, u.telefono, u.nombre_usuario, u.estado, u.codigo_rol, r.nombre_rol 
                      FROM usuario u 
                      INNER JOIN rol r ON u.codigo_rol = r.codigo_rol";
            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function addUser($cedula, $nombre_usuario, $telefono, $password, $rol = 2) {
        try {
            $query = "INSERT INTO usuario (cedula_usuario, telefono, nombre_usuario, codigo_rol, password, estado) 
                      VALUES (:cedula, :telefono, :nombre, :rol, :pass, 1)";
            $stmt = $this->conex->prepare($query);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':nombre', trim($nombre_usuario));
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':pass', $password);
            
            if ($stmt->execute()) {
                return ["status" => "success", "message" => "Usuario registrado con éxito."];
            }
            return ["status" => "error", "message" => "No se pudo completar el registro."];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function updateUser($id, $cedula, $nombre, $telefono, $rol, $estado) {
        try {
            $query = "UPDATE usuario SET cedula_usuario = :cedula, nombre_usuario = :nombre, 
                      telefono = :telefono, codigo_rol = :rol, estado = :estado 
                      WHERE cedula_usuario = :id";
            $stmt = $this->conex->prepare($query);
            $stmt->bindParam(':cedula', $cedula);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':telefono', $telefono);
            $stmt->bindParam(':rol', $rol);
            $stmt->bindParam(':estado', $estado);
            $stmt->bindParam(':id', $id);
            
            if ($stmt->execute()) {
                return ["status" => "success", "message" => "Usuario actualizado."];
            }
            return ["status" => "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function deleteUser($id) {
        try {
            $query = "UPDATE usuario SET estado = 0 WHERE cedula_usuario = :id";
            $stmt = $this->conex->prepare($query);
            $stmt->bindParam(':id', $id);
            return ["status" => $stmt->execute() ? "success" : "error"];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }

    public function login($username, $password) {
        try {
            $username = trim((string)$username);
            $password = trim((string)$password);

            $query = "SELECT cedula_usuario, nombre_usuario, password, codigo_rol, estado 
                      FROM usuario 
                      WHERE nombre_usuario = :user";
            
            $stmt = $this->conex->prepare($query);
            $stmt->bindParam(':user', $username);
            $stmt->execute();
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && (int)$user['estado'] === 1) {
                if ($password === $user['password']) {
                    unset($user['password']);
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("PDOException during login for user '{$username}': " . $e->getMessage());
            return false;
        }
    }

    public function getEmpresaRIF() {
        try {
            $query = "SELECT nombre_empresa, rif FROM empresa LIMIT 1";
            $stmt = $this->conex->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: ['nombre_empresa' => null, 'rif' => null];
        } catch (PDOException $e) {
            return ['nombre_empresa' => null, 'rif' => null];
        }
    }

    public function updateCredentials(string $currentUsername, string $passwordActual, ?string $nuevoNombre, ?string $nuevaPassword, ?string $confirmarPassword) {
        try {
            $user = $this->login($currentUsername, $passwordActual);
            if (!$user) {
                return ["status" => "error", "message" => "La contraseña actual es incorrecta."];
            }

            if (empty($nuevoNombre) && empty($nuevaPassword)) {
                return ["status" => "error", "message" => "Debe ingresar al menos un dato a modificar."];
            }

            if (!empty($nuevaPassword)) {
                if ($nuevaPassword !== $confirmarPassword) {
                    return ["status" => "error", "message" => "La confirmación de la nueva contraseña no coincide."];
                }
            }

            $fields = [];
            $params = [':id' => $user['cedula_usuario']];

            if (!empty($nuevoNombre)) {
                $fields[] = 'nombre_usuario = :nuevo_nombre';
                $params[':nuevo_nombre'] = trim($nuevoNombre);
            }

            if (!empty($nuevaPassword)) {
                $fields[] = 'password = :nueva_password';
                $params[':nueva_password'] = trim($nuevaPassword);
            }

            $query = "UPDATE usuario SET " . implode(', ', $fields) . " WHERE cedula_usuario = :id";
            $stmt = $this->conex->prepare($query);
            $stmt->execute($params);

            return ["status" => "success", "message" => "Credenciales actualizadas correctamente."];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => $e->getMessage()];
        }
    }
}
