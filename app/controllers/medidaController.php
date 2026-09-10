<?php

    use App\models\medidaModel;

    if (session_status() === PHP_SESSION_NONE) session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: ?url=login");
        exit();
    }

    $object = new medidaModel();

    $flash = null;

    if (isset($_GET['type'])) {

        if ($_GET['type'] === 'register') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
                $abreviatura = trim($_POST['abreviatura'] ?? '');
                $cantidadUnidad = isset($_POST['cantidad_unidad']) ? max(1, (int) $_POST['cantidad_unidad']) : 1;
                $result = $object->addMedida($_POST['nombre'], $abreviatura ?: null, $cantidadUnidad);
                if ($result === true) {
                    $flash = ['status' => 'success', 'message' => 'Unidad registrada correctamente'];
                } elseif (is_array($result)) {
                    $flash = ['status' => 'error', 'message' => $result['message'] ?? 'No se pudo registrar la unidad'];
                } else {
                    $flash = ['status' => 'error', 'message' => 'No se pudo registrar la unidad'];
                }
                $_SESSION['medida_flash'] = $flash;
            }
            session_write_close();
            header("Location: ?url=medida");
            exit();
        }

        elseif ($_GET['type'] === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_media'])) {
                $estado = isset($_POST['estado']) ? 1 : 0;
                $abreviatura = trim($_POST['abreviatura'] ?? '');
                $cantidadUnidad = isset($_POST['cantidad_unidad']) ? max(1, (int) $_POST['cantidad_unidad']) : 1;
                $result = $object->updateMedida((int) $_POST['codigo_media'], $_POST['nombre'], $abreviatura ?: null, $cantidadUnidad, $estado);
                $flash = $result === true ? ['status' => 'success', 'message' => 'Unidad actualizada correctamente'] : ['status' => 'error', 'message' => 'No se pudo actualizar la unidad'];
                $_SESSION['medida_flash'] = $flash;
            }
            session_write_close();
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
            session_write_close();
            header("Location: ?url=medida");
            exit();
        }

        else {
            session_write_close();
            header("Location: ?url=medida");
            exit();
        }
    }

    $medidas = $object->getAllMedidasFull();
    include 'app/views/medida/viewMedida.php';
