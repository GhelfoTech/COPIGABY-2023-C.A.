<?php

    use App\models\medidaModel;

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ?url=login");
        exit();
    }

    $object = new medidaModel();

    if (isset($_GET['type'])) {

        if ($_GET['type'] === 'register') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
                $abreviatura = trim($_POST['abreviatura'] ?? '');
                $cantidadUnidad = isset($_POST['cantidad_unidad']) ? max(1, (int) $_POST['cantidad_unidad']) : 1;
                $object->addMedida($_POST['nombre'], $abreviatura ?: null, $cantidadUnidad);
                header("Location: ?url=medida");
                exit();
            }
            header("Location: ?url=medida");
            exit();
        }

        elseif ($_GET['type'] === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_media'])) {
                $estado = isset($_POST['estado']) ? 1 : 0;
                $abreviatura = trim($_POST['abreviatura'] ?? '');
                $cantidadUnidad = isset($_POST['cantidad_unidad']) ? max(1, (int) $_POST['cantidad_unidad']) : 1;
                $object->updateMedida((int) $_POST['codigo_media'], $_POST['nombre'], $abreviatura ?: null, $cantidadUnidad, $estado);
                header("Location: ?url=medida");
                exit();
            }
            header("Location: ?url=medida");
            exit();
        }

        elseif ($_GET['type'] === 'main') {
            if (isset($_POST['deleteMedida'])) {
                $result = $object->deleteMedida((int) $_POST['codigo_media']);
                header('Content-Type: application/json');
                echo json_encode($result);
                exit();
            }
            header("Location: ?url=medida");
            exit();
        }

        else {
            header("Location: ?url=medida");
            exit();
        }
    }

    $medidas = $object->getAllMedidasFull();
    include 'app/views/medida/viewMedida.php';
