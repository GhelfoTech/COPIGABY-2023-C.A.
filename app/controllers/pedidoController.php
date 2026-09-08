<?php

    use App\models\pedidoModel;
    use App\models\medidaModel;

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header('Location: ?url=login');
        exit();
    }

    $object = new pedidoModel();
    $medidaModel = new medidaModel();

    if (isset($_GET['type'])) {

        if ($_GET['type'] === 'register') {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                header('Location: ?url=pedido');
                exit();
            }

            $isAjax = (
                !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
            ) || (isset($_POST['ajax']) && $_POST['ajax'] === '1');

            if (!isset($_POST['codigo_cliente'], $_POST['items'], $_POST['codigo_metodo'])) {
                $payload = [
                    'status'  => 'error',
                    'message' => 'Datos incompletos: cliente, ítems y método de pago son obligatorios.',
                ];
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($payload);
                    exit();
                }
                $_SESSION['pedido_flash'] = $payload;
                header('Location: ?url=pedido');
                exit();
            }

            $items = json_decode((string) $_POST['items'], true);
            if (!is_array($items) || $items === []) {
                $payload = [
                    'status'  => 'error',
                    'message' => 'El pedido debe incluir al menos un ítem válido.',
                ];
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($payload);
                    exit();
                }
                $_SESSION['pedido_flash'] = $payload;
                header('Location: ?url=pedido');
                exit();
            }

            if (!isset($_POST['codigo_IVA']) || (int) $_POST['codigo_IVA'] <= 0) {
                $payload = [
                    'status'  => 'error',
                    'message' => 'Debe seleccionar un porcentaje de IVA válido.',
                ];
                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($payload);
                    exit();
                }
                $_SESSION['pedido_flash'] = $payload;
                header('Location: ?url=pedido');
                exit();
            }

            $datos = [
                'cedula_cliente'  => trim((string) $_POST['codigo_cliente']),
                'cedula_usuario'  => $_SESSION['user_id'],
                'codigo_IVA'      => (int) $_POST['codigo_IVA'],
                'subtotal'        => $_POST['subtotal'] ?? null,
                'monto_iva'       => $_POST['monto_iva'] ?? null,
                'porcentaje_iva'  => $_POST['porcentaje_iva'] ?? null,
                'monto_total'     => $_POST['monto_total'] ?? null,
                'tasa_actual'     => $_POST['tasa_actual'] ?? null,
            ];

            $pago = [
                'codigo_metodo' => (int) $_POST['codigo_metodo'],
                'codigo_banco'  => isset($_POST['codigo_banco']) ? (int) $_POST['codigo_banco'] : null,
                'referencia'    => trim((string) ($_POST['referencia_pago'] ?? '')),
            ];

            $result = $object->addPedido($datos, $items, $pago);

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
                exit();
            }

            $_SESSION['pedido_flash'] = $result;
            header('Location: ?url=pedido');
            exit();
        }

        elseif ($_GET['type'] === 'update') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_pedido'], $_POST['codigo_cliente'], $_POST['items'])) {
                $isAjax = (
                    !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
                    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
                ) || (isset($_POST['ajax']) && $_POST['ajax'] === '1');

                $items = json_decode((string) $_POST['items'], true);
                if (!is_array($items) || $items === []) {
                    $payload = ['status' => 'error', 'message' => 'El pedido debe incluir al menos un ítem válido.'];
                    if ($isAjax) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode($payload);
                        exit();
                    }
                    $_SESSION['pedido_flash'] = $payload;
                    header('Location: ?url=pedido');
                    exit();
                }

                $pago = [
                    'codigo_metodo' => (int) ($_POST['codigo_metodo'] ?? 0),
                    'codigo_banco'  => isset($_POST['codigo_banco']) ? (int) $_POST['codigo_banco'] : null,
                    'referencia'    => trim((string) ($_POST['referencia_pago'] ?? '')),
                ];

                if (!isset($_POST['codigo_IVA']) || (int) $_POST['codigo_IVA'] <= 0) {
                    $payload = ['status' => 'error', 'message' => 'Debe seleccionar un porcentaje de IVA válido.'];
                    if ($isAjax) {
                        header('Content-Type: application/json; charset=utf-8');
                        echo json_encode($payload);
                        exit();
                    }
                    $_SESSION['pedido_flash'] = $payload;
                    header('Location: ?url=pedido');
                    exit();
                }

                $result = $object->updatePedido((int) $_POST['codigo_pedido'], [
                    'cedula_cliente'  => trim((string) $_POST['codigo_cliente']),
                    'estado'          => isset($_POST['estado']) ? (int) $_POST['estado'] : 1,
                    'codigo_IVA'      => (int) $_POST['codigo_IVA'],
                    'subtotal'        => $_POST['subtotal'] ?? null,
                    'monto_iva'       => $_POST['monto_iva'] ?? null,
                    'porcentaje_iva'  => $_POST['porcentaje_iva'] ?? null,
                    'monto_total'     => $_POST['monto_total'] ?? null,
                    'tasa_actual'     => $_POST['tasa_actual'] ?? null,
                ], $items, $pago);

                if ($isAjax) {
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                    exit();
                }

                $_SESSION['pedido_flash'] = $result;
                header('Location: ?url=pedido');
                exit();
            }
            header('Location: ?url=pedido');
            exit();
        }

        elseif ($_GET['type'] === 'main') {
            if (isset($_POST['deletePedido'])) {
                $result = $object->deletePedido((int) $_POST['idpedido']);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($result);
                exit();
            }
            header('Location: ?url=pedido');
            exit();
        }

        elseif ($_GET['type'] === 'details') {
            if (isset($_GET['id'])) {
                $header = $object->getPedidoById((int) $_GET['id']);
                $items  = $object->getItemsByPedido((int) $_GET['id']);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['header' => $header, 'items' => $items]);
                exit();
            }
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['header' => null, 'items' => []]);
            exit();
        }

        else {
            header('Location: ?url=pedido');
            exit();
        }
    }

    $pedidos      = $object->getAllPedidos();
    $clientes     = $object->getClientesActivos();
    $productos    = $object->getProductosActivos();
    $servicios    = $object->getServiciosActivos();
    $metodos      = $object->getMetodosActivos();
    $bancos       = $object->getBancosActivos();
    $monedaActiva = $object->getMonedaActiva();
    $tasaActual   = $object->getTasaActual();
    $ivas         = $object->getIvasActivos();
    $ivaActivo    = $object->getIvaActivo();
    $medidas      = $medidaModel->getMedidasActivas();
    $pedidoFlash  = $_SESSION['pedido_flash'] ?? null;
    unset($_SESSION['pedido_flash']);

    include 'app/views/pedidos/viewPedido.php';
