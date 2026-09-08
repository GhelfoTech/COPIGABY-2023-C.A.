<?php

    use App\models\clienteModel;

    if (session_status() === PHP_SESSION_NONE) session_start();

    // Seguridad: Verificar sesión activa
    if (!isset($_SESSION['user_id'])) {
        header("Location: ?url=login");
        exit();
    }

    $object = new clienteModel();

    if (isset($_GET['type'])) {

        if ($_GET['type'] === 'register') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cedula_cliente'])) {
                $object->addCliente($_POST);
                header("Location: ?url=cliente");
                exit();
            }
            header("Location: ?url=cliente");
            exit();
        }

        elseif ($_GET['type'] === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_actual'])) {
                $_POST['estado'] = isset($_POST['estado']) ? 1 : 0;
                $object->updateCliente($_POST['id_actual'], $_POST);
                header("Location: ?url=cliente");
                exit();
            }
            header("Location: ?url=cliente");
            exit();
        }

        elseif ($_GET['type'] === 'main') {
            if (isset($_POST['deleteCliente'])) {
                $result = $object->deleteCliente($_POST['idcliente']);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit();
            }
            header("Location: ?url=cliente");
            exit();
        }

        else {
            header("Location: ?url=cliente");
            exit();
        }
    }

    // Carga por defecto: Listado de clientes
    $clientes = $object->getAllClientes();
    include 'app/views/cliente/viewCliente.php';
?>