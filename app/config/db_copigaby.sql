-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-06-2026 a las 22:13:11
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_copigaby`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `banco`
--

CREATE TABLE `banco` (
  `codigo_banco` int(4) NOT NULL,
  `nombre_banco` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `banco`
--

INSERT INTO `banco` (`codigo_banco`, `nombre_banco`, `estado`) VALUES
(102, 'Banco de Venezuela', 1),
(104, 'Banco Venezolano de Crédito', 1),
(105, 'Banco Mercantil', 1),
(108, 'Banco Provincial (BBVA)', 1),
(114, 'Banco del Caribe (Bancaribe)', 1),
(115, 'Banco Exterior', 1),
(128, 'Banco Banco Caroní', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `codigo_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(50) NOT NULL,
  `porcentaje_ganancia` decimal(10,0) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`codigo_categoria`, `nombre_categoria`, `porcentaje_ganancia`, `estado`) VALUES
(101, 'PAPELERIA', 0, 1),
(102, 'MERCERIA', 0, 1),
(103, 'BISUTERIA', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cedula_cliente` int(12) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `direccion` varchar(50) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cedula_cliente`, `nombre`, `telefono`, `correo`, `direccion`, `estado`) VALUES
(23456654, 'MICHELLE', '04245555555', 'MICHELLE@gmail.com', 'BASIL', 1),
(32137731, 'Yeilyn', '04228690511', 'Yeilyn32gmail.com', 'Andres Eloy', 1),
(32345654, 'JUAN GOMEZ', '04245555555', 'JUAN@gmail.com', 'NICARAGUA', 1),
(32456432, 'VICTORIA', '04245678976', 'VICTORIA@gmail.com', 'LA VICTORIA', 1),
(123454321, 'RAMON', '04245555555', 'RAMON@gmail.com', 'ARGENTINA', 1),
(342123344, 'CAROLINA', '04245678976', 'CAROLINA@gmail.com', 'MERIDA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

CREATE TABLE `compra` (
  `codigo_compra` int(11) NOT NULL,
  `codigo_proveedor` int(11) NOT NULL,
  `cedula_usuario` int(11) NOT NULL,
  `numero_factura_proveedor` varchar(10) NOT NULL,
  `fecha_compra` date NOT NULL,
  `monto_total` decimal(10,2) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`codigo_compra`, `codigo_proveedor`, `cedula_usuario`, `numero_factura_proveedor`, `fecha_compra`, `monto_total`, `estado`) VALUES
(512, 317317661, 2121212121, '00512', '2026-03-04', 43563.00, 1),
(518, 317317661, 323232323, '', '2026-06-25', 0.50, 1),
(519, 317317661, 323232323, '', '2026-06-25', 1.00, 1),
(520, 317317661, 323232323, '', '2026-06-25', 0.80, 1),
(521, 317317661, 323232323, '', '2026-06-25', 3.10, 1),
(522, 317317661, 323232323, '', '2026-06-25', 0.15, 1),
(523, 317317661, 323232323, '', '2026-06-25', 0.20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `codigo_detalle_compra` int(11) NOT NULL,
  `codigo_compra` int(11) NOT NULL,
  `codigo_producto` int(11) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`codigo_detalle_compra`, `codigo_compra`, `codigo_producto`, `cantidad`, `costo_unitario`, `subtotal`) VALUES
(5568, 512, 759160, 12.00, 1500.00, 17000.00),
(5976, 512, 759765, 5.00, 2500.00, 12500.00),
(8845, 512, 759199, 8.00, 2600.00, 12000.00),
(87653, 512, 759765, 12.00, 2600.00, 12600.00),
(87657, 518, 7654, 1.00, 0.50, 0.50),
(87658, 519, 76543, 2.00, 0.50, 1.00),
(87659, 520, 759767, 1.00, 0.30, 0.30),
(87660, 520, 759766, 1.00, 0.50, 0.50),
(87661, 521, 759160, 1.00, 3.00, 3.00),
(87662, 521, 759199, 1.00, 0.10, 0.10),
(87663, 522, 759765, 1.00, 0.15, 0.15),
(87664, 523, 98765, 1.00, 0.20, 0.20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pago`
--

CREATE TABLE `detalle_pago` (
  `codigo_detalle_pago` int(11) NOT NULL,
  `codigo_pago` int(11) NOT NULL,
  `codigo_moneda` int(11) NOT NULL,
  `codigo_metodo` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pago`
--

INSERT INTO `detalle_pago` (`codigo_detalle_pago`, `codigo_pago`, `codigo_moneda`, `codigo_metodo`, `monto`) VALUES
(582, 580, 510, 562, 140.36),
(587, 585, 510, 561, 10.44),
(599, 597, 510, 564, 5901.18),
(600, 598, 510, 561, 0.70),
(601, 599, 510, 562, 69.60);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `codigo_detalle_pedido` int(11) NOT NULL,
  `codigo_pedido` int(11) NOT NULL,
  `codigo_producto` int(11) DEFAULT NULL,
  `codigo_servicio` int(11) DEFAULT NULL,
  `codigo_media` int(11) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`codigo_detalle_pedido`, `codigo_pedido`, `codigo_producto`, `codigo_servicio`, `codigo_media`, `cantidad`, `precio_venta`, `subtotal`) VALUES
(587, 571, 76543, NULL, 21, 2.00, 0.50, 1.00),
(588, 571, NULL, 7, NULL, 3.00, 40.00, 120.00),
(593, 576, 759160, NULL, 21, 3.00, 3.00, 9.00),
(608, 581, 759766, NULL, 21, 2.00, 0.50, 1.00),
(609, 581, NULL, 8, NULL, 2.00, 2500.00, 5000.00),
(610, 580, 759765, NULL, 21, 4.00, 0.15, 0.60),
(611, 582, NULL, 9, NULL, 2.00, 30.00, 60.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_transferencia`
--

CREATE TABLE `detalle_transferencia` (
  `codigo_detalle_transferencia` int(11) NOT NULL,
  `codigo_detalle_pago` int(11) NOT NULL,
  `codigo_banco` int(11) NOT NULL,
  `codigo_referencia` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_transferencia`
--

INSERT INTO `detalle_transferencia` (`codigo_detalle_transferencia`, `codigo_detalle_pago`, `codigo_banco`, `codigo_referencia`) VALUES
(589, 599, 102, '2345');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dolar`
--

CREATE TABLE `dolar` (
  `codigo_dolar` int(11) NOT NULL,
  `codigo_taza` int(11) NOT NULL,
  `codigo_efectivo` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `efectivo`
--

CREATE TABLE `efectivo` (
  `cidigo_efectivo` int(11) NOT NULL,
  `codigo_dolar` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `efectivo`
--

INSERT INTO `efectivo` (`cidigo_efectivo`, `codigo_dolar`, `estado`) VALUES
(999, 2222, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `codigo_empresa` int(11) NOT NULL,
  `rif_empresa` varchar(12) NOT NULL,
  `nombre_empresa` varchar(30) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `direccion` varchar(80) NOT NULL,
  `logo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`codigo_empresa`, `rif_empresa`, `nombre_empresa`, `telefono`, `correo`, `direccion`, `logo`) VALUES
(1111, 'J504149357', 'COPI GABY 2023 C.A', '04120583967', 'Inpresionescopigaby@gmail.com', 'PUEBLO NUEVO', 'LOGOOOOOOOOO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iva`
--

CREATE TABLE `iva` (
  `codigo_IVA` int(11) NOT NULL,
  `porcentaje_iva` decimal(5,2) NOT NULL,
  `fecha` date NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `iva`
--

INSERT INTO `iva` (`codigo_IVA`, `porcentaje_iva`, `fecha`, `estado`) VALUES
(1, 16.00, '2025-02-12', 1),
(2, 18.00, '2026-06-09', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `codigo_metodo` int(11) NOT NULL,
  `nombre_metodo` varchar(20) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `metodo_pago`
--

INSERT INTO `metodo_pago` (`codigo_metodo`, `nombre_metodo`, `estado`) VALUES
(561, 'PUNTO DE VENTA', 1),
(562, 'EFECTIVO', 1),
(563, 'TRANSFERENCIA', 1),
(564, 'PAGO MOVIL', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `moneda`
--

CREATE TABLE `moneda` (
  `codigo_moneda` int(11) NOT NULL,
  `nombre_moneda` varchar(15) NOT NULL,
  `simbolo` varchar(2) NOT NULL,
  `codigo_tasa` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `activa` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `moneda`
--

INSERT INTO `moneda` (`codigo_moneda`, `nombre_moneda`, `simbolo`, `codigo_tasa`, `estado`, `activa`) VALUES
(510, 'DOLARES', '$', 109, 1, 1),
(511, 'EURO', '€', 107, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `codigo_movimiento` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `cedula_usuario` int(11) NOT NULL,
  `tipo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`codigo_movimiento`, `fecha`, `cedula_usuario`, `tipo`) VALUES
(8732, '2026-06-12 03:20:20', 2121212121, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `codigo_pago` int(11) NOT NULL,
  `codigo_pedido` int(11) NOT NULL,
  `fecha_pago` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`codigo_pago`, `codigo_pedido`, `fecha_pago`, `estado`) VALUES
(580, 571, '2026-06-28 15:35:48', 1),
(585, 576, '2026-06-28 15:45:11', 1),
(597, 581, '2026-06-28 16:08:20', 0),
(598, 580, '2026-06-28 16:08:30', 1),
(599, 582, '2026-06-28 16:10:07', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `codigo_pedido` int(11) NOT NULL,
  `cedula_cliente` int(12) NOT NULL,
  `cedula_usuario` int(11) NOT NULL,
  `fecha_pedido` datetime NOT NULL,
  `estado` tinyint(1) NOT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `monto_iva` decimal(10,2) DEFAULT NULL,
  `porcentaje_iva` decimal(5,2) DEFAULT NULL,
  `monto_total` decimal(10,2) DEFAULT NULL,
  `tasa_cambio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`codigo_pedido`, `cedula_cliente`, `cedula_usuario`, `fecha_pedido`, `estado`, `subtotal`, `monto_iva`, `porcentaje_iva`, `monto_total`, `tasa_cambio`) VALUES
(571, 123454321, 323232323, '2026-06-28 15:34:00', 1, 121.00, 19.36, 16.00, 140.36, 450.00),
(576, 23456654, 323232323, '2026-06-28 15:45:11', 1, 9.00, 1.44, 16.00, 10.44, 450.00),
(580, 32345654, 323232323, '2026-06-28 16:05:26', 1, 0.60, 0.10, 16.00, 0.70, 450.00),
(581, 123454321, 323232323, '2026-06-28 16:07:32', 0, 5001.00, 900.18, 18.00, 5901.18, 450.00),
(582, 32137731, 323232323, '2026-06-28 16:10:07', 1, 60.00, 9.60, 16.00, 69.60, 450.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_insumo`
--

CREATE TABLE `producto_insumo` (
  `codigo_producto` int(11) NOT NULL,
  `nombre_producto` varchar(50) NOT NULL,
  `codigo_categoria` int(11) NOT NULL,
  `codigo_IVA` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `stock_actual` int(11) NOT NULL,
  `stock_minimo` int(11) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_insumo`
--

INSERT INTO `producto_insumo` (`codigo_producto`, `nombre_producto`, `codigo_categoria`, `codigo_IVA`, `descripcion`, `stock_actual`, `stock_minimo`, `estado`) VALUES
(7654, 'Lapiz', 101, 2, 'xxxxxxxxxxxxxxxxxxxx', 3, 6, 1),
(76543, 'CINTA', 102, 2, 'XXXXXXXXXXXXXXXXXXX', 54, 10, 1),
(98765, 'PULSERA', 103, 2, 'XXXXXXXXXXXXXXXXX', 14, 2, 1),
(759160, 'BLOCK', 101, 1, 'TTTTT', 17, 2, 1),
(759199, 'HOJAS CARTA', 101, 1, 'XXXXXXXXXXXXXXXXX', 21, 2, 1),
(759765, 'HOJAS OFICIO', 101, 1, 'XXXXXXXXXXXXXXXXXXXXXXXXXXX', 13, 2, 1),
(759766, 'Borra', 101, 1, 'Borra', 11, 2, 1),
(759767, 'lapicero', 101, 1, 'mmmmmmmmmm', 2, 5, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `codigo_proveedor` int(11) NOT NULL,
  `rif_proveedor` varchar(12) NOT NULL,
  `razon_social` varchar(50) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `correo` varchar(60) NOT NULL,
  `direccion` varchar(60) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`codigo_proveedor`, `rif_proveedor`, `razon_social`, `telefono`, `correo`, `direccion`, `estado`) VALUES
(317317661, 'J317317661', 'INVERSIONES BURBUJA', '04245555555', 'BURBUJA@gmail.com', 'calle carrera', 1),
(988760998, '54924', 'POINTER', '04228690511', 'mprende@gmail.com', 'calle 6 valencia', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `referencia`
--

CREATE TABLE `referencia` (
  `referencia` varchar(50) NOT NULL,
  `numero_comprobante` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `referencia`
--

INSERT INTO `referencia` (`referencia`, `numero_comprobante`) VALUES
('2345', '2345'),
('6789', '6789'),
('77777', '8888888'),
('7890', '7890'),
('8790', '8790'),
('8899', '8899'),
('8908', '8908'),
('8970', '8970'),
('8976', '8976'),
('987', '0987');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `codigo_rol` int(11) NOT NULL,
  `nombre_rol` varchar(15) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`codigo_rol`, `nombre_rol`, `descripcion`, `estado`) VALUES
(1, 'admin', 'Administrador', 1),
(2, 'EMPLEADO', 'Empleado', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `codigo_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(50) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`codigo_servicio`, `nombre_servicio`, `descripcion`, `precio`, `estado`) VALUES
(7, 'IMPRESION ', 'XXXXXXXXXXXXXXXXXX', 40.00, 1),
(8, 'PLASTIFICACION', 'XXXXXXXXXX', 2500.00, 1),
(9, 'Copia - B&N', 'Blanco y negro', 30.00, 1),
(10, 'Copia - Color', 'Color', 50.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_material`
--

CREATE TABLE `servicio_material` (
  `codigo_servicio_material` int(11) NOT NULL,
  `codigo_producto` int(11) NOT NULL,
  `codigo_servicio` int(11) NOT NULL,
  `cantidad_usada` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio_material`
--

INSERT INTO `servicio_material` (`codigo_servicio_material`, `codigo_producto`, `codigo_servicio`, `cantidad_usada`) VALUES
(7, 759765, 7, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa_cambio`
--

CREATE TABLE `tasa_cambio` (
  `codigo_tasa` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `monto_bolivares` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasa_cambio`
--

INSERT INTO `tasa_cambio` (`codigo_tasa`, `fecha`, `monto_bolivares`) VALUES
(105, '2026-06-16 23:24:22', 500.00),
(106, '2026-06-17 00:02:28', 450.00),
(107, '2026-06-17 00:02:34', 600.00),
(108, '2026-06-24 01:11:09', 450.00),
(109, '2026-06-24 01:11:21', 450.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `unidad_medida`
--

CREATE TABLE `unidad_medida` (
  `codigo_media` int(11) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `abreviatura` varchar(10) DEFAULT NULL,
  `cantidad_unidad` int(11) NOT NULL DEFAULT 1,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `unidad_medida`
--

INSERT INTO `unidad_medida` (`codigo_media`, `nombre`, `abreviatura`, `cantidad_unidad`, `estado`) VALUES
(21, 'BULTO', 'BLT', 1, 1),
(22, 'DOCENA', 'DOC', 12, 1),
(24, 'CAJA', 'CJ', 1, 1),
(26, 'METRO', 'MT', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cedula_usuario` int(12) NOT NULL,
  `telefono` varchar(12) NOT NULL,
  `nombre_usuario` varchar(20) NOT NULL,
  `codigo_rol` int(11) NOT NULL,
  `password` varchar(15) NOT NULL,
  `estado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cedula_usuario`, `telefono`, `nombre_usuario`, `codigo_rol`, `password`, `estado`) VALUES
(222222222, '04245555555', 'empleado', 2, '123', 1),
(323232323, '04245555555', 'admin', 1, '123', 1),
(2121212121, '04245555555', 'ADMIN1', 1, '123A', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `banco`
--
ALTER TABLE `banco`
  ADD PRIMARY KEY (`codigo_banco`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`codigo_categoria`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cedula_cliente`);

--
-- Indices de la tabla `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`codigo_compra`),
  ADD KEY `codigo_proveedor` (`codigo_proveedor`),
  ADD KEY `codigo_usuario` (`cedula_usuario`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`codigo_detalle_compra`),
  ADD KEY `codigo_compra` (`codigo_compra`),
  ADD KEY `codigo_producto` (`codigo_producto`);

--
-- Indices de la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD PRIMARY KEY (`codigo_detalle_pago`),
  ADD KEY `codigo_pago` (`codigo_pago`),
  ADD KEY `codigo_moneda` (`codigo_moneda`),
  ADD KEY `codigo_metodo` (`codigo_metodo`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`codigo_detalle_pedido`),
  ADD KEY `codigo_pedido` (`codigo_pedido`),
  ADD KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `codigo_servicio` (`codigo_servicio`),
  ADD KEY `codigo_media` (`codigo_media`);

--
-- Indices de la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD PRIMARY KEY (`codigo_detalle_transferencia`),
  ADD KEY `codigo_referencia` (`codigo_referencia`),
  ADD KEY `codigo_banco` (`codigo_banco`),
  ADD KEY `idx_codigo_detalle_pago` (`codigo_detalle_pago`);

--
-- Indices de la tabla `dolar`
--
ALTER TABLE `dolar`
  ADD PRIMARY KEY (`codigo_dolar`),
  ADD KEY `codigo_taza` (`codigo_taza`),
  ADD KEY `codigo_efectivo` (`codigo_efectivo`);

--
-- Indices de la tabla `efectivo`
--
ALTER TABLE `efectivo`
  ADD PRIMARY KEY (`cidigo_efectivo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`codigo_empresa`);

--
-- Indices de la tabla `iva`
--
ALTER TABLE `iva`
  ADD PRIMARY KEY (`codigo_IVA`);

--
-- Indices de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`codigo_metodo`);

--
-- Indices de la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD PRIMARY KEY (`codigo_moneda`),
  ADD KEY `codigo_tasa` (`codigo_tasa`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`codigo_movimiento`),
  ADD KEY `codigo_usuario` (`cedula_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`codigo_pago`),
  ADD KEY `codigo_pedido` (`codigo_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`codigo_pedido`),
  ADD KEY `codigo_usuario` (`cedula_usuario`),
  ADD KEY `codigo_cliente` (`cedula_cliente`);

--
-- Indices de la tabla `producto_insumo`
--
ALTER TABLE `producto_insumo`
  ADD PRIMARY KEY (`codigo_producto`),
  ADD KEY `codigo_categoria` (`codigo_categoria`),
  ADD KEY `codigo_IVA` (`codigo_IVA`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`codigo_proveedor`);

--
-- Indices de la tabla `referencia`
--
ALTER TABLE `referencia`
  ADD PRIMARY KEY (`referencia`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`codigo_rol`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`codigo_servicio`);

--
-- Indices de la tabla `servicio_material`
--
ALTER TABLE `servicio_material`
  ADD PRIMARY KEY (`codigo_servicio_material`),
  ADD KEY `codigo_servicio` (`codigo_servicio`),
  ADD KEY `nombre_producto` (`codigo_producto`);

--
-- Indices de la tabla `tasa_cambio`
--
ALTER TABLE `tasa_cambio`
  ADD PRIMARY KEY (`codigo_tasa`);

--
-- Indices de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  ADD PRIMARY KEY (`codigo_media`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cedula_usuario`),
  ADD KEY `codigo_rol` (`codigo_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `codigo_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT de la tabla `compra`
--
ALTER TABLE `compra`
  MODIFY `codigo_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=524;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `codigo_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87665;

--
-- AUTO_INCREMENT de la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  MODIFY `codigo_detalle_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=602;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `codigo_detalle_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=612;

--
-- AUTO_INCREMENT de la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  MODIFY `codigo_detalle_transferencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=590;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `codigo_empresa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1112;

--
-- AUTO_INCREMENT de la tabla `iva`
--
ALTER TABLE `iva`
  MODIFY `codigo_IVA` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `codigo_metodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=565;

--
-- AUTO_INCREMENT de la tabla `moneda`
--
ALTER TABLE `moneda`
  MODIFY `codigo_moneda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=983;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `codigo_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8733;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `codigo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=600;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `codigo_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=583;

--
-- AUTO_INCREMENT de la tabla `producto_insumo`
--
ALTER TABLE `producto_insumo`
  MODIFY `codigo_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=759768;

--
-- AUTO_INCREMENT de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  MODIFY `codigo_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=988760999;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `codigo_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `codigo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `servicio_material`
--
ALTER TABLE `servicio_material`
  MODIFY `codigo_servicio_material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tasa_cambio`
--
ALTER TABLE `tasa_cambio`
  MODIFY `codigo_tasa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT de la tabla `unidad_medida`
--
ALTER TABLE `unidad_medida`
  MODIFY `codigo_media` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`codigo_proveedor`) REFERENCES `proveedor` (`codigo_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`cedula_usuario`) REFERENCES `usuario` (`cedula_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`codigo_compra`) REFERENCES `compra` (`codigo_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`codigo_producto`) REFERENCES `producto_insumo` (`codigo_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_pago`
--
ALTER TABLE `detalle_pago`
  ADD CONSTRAINT `detalle_pago_ibfk_1` FOREIGN KEY (`codigo_pago`) REFERENCES `pagos` (`codigo_pago`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_ibfk_2` FOREIGN KEY (`codigo_moneda`) REFERENCES `moneda` (`codigo_moneda`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pago_ibfk_3` FOREIGN KEY (`codigo_metodo`) REFERENCES `metodo_pago` (`codigo_metodo`);

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`codigo_pedido`) REFERENCES `pedido` (`codigo_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`codigo_producto`) REFERENCES `producto_insumo` (`codigo_producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_3` FOREIGN KEY (`codigo_servicio`) REFERENCES `servicio` (`codigo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_pedido_ibfk_4` FOREIGN KEY (`codigo_media`) REFERENCES `unidad_medida` (`codigo_media`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_transferencia`
--
ALTER TABLE `detalle_transferencia`
  ADD CONSTRAINT `detalle_transferencia_ibfk_1` FOREIGN KEY (`codigo_referencia`) REFERENCES `referencia` (`referencia`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_transferencia_ibfk_2` FOREIGN KEY (`codigo_banco`) REFERENCES `banco` (`codigo_banco`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_transferencia_ibfk_3` FOREIGN KEY (`codigo_detalle_pago`) REFERENCES `detalle_pago` (`codigo_detalle_pago`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `dolar`
--
ALTER TABLE `dolar`
  ADD CONSTRAINT `dolar_ibfk_1` FOREIGN KEY (`codigo_taza`) REFERENCES `tasa_cambio` (`codigo_tasa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `dolar_ibfk_2` FOREIGN KEY (`codigo_efectivo`) REFERENCES `efectivo` (`cidigo_efectivo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `moneda`
--
ALTER TABLE `moneda`
  ADD CONSTRAINT `moneda_ibfk_1` FOREIGN KEY (`codigo_tasa`) REFERENCES `tasa_cambio` (`codigo_tasa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`cedula_usuario`) REFERENCES `usuario` (`cedula_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`codigo_pedido`) REFERENCES `pedido` (`codigo_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`cedula_usuario`) REFERENCES `usuario` (`cedula_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_ibfk_4` FOREIGN KEY (`cedula_cliente`) REFERENCES `cliente` (`cedula_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_insumo`
--
ALTER TABLE `producto_insumo`
  ADD CONSTRAINT `producto_insumo_ibfk_1` FOREIGN KEY (`codigo_categoria`) REFERENCES `categoria` (`codigo_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_insumo_ibfk_2` FOREIGN KEY (`codigo_IVA`) REFERENCES `iva` (`codigo_IVA`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `servicio_material`
--
ALTER TABLE `servicio_material`
  ADD CONSTRAINT `servicio_material_ibfk_1` FOREIGN KEY (`codigo_servicio`) REFERENCES `servicio` (`codigo_servicio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `servicio_material_ibfk_2` FOREIGN KEY (`codigo_producto`) REFERENCES `producto_insumo` (`codigo_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`codigo_rol`) REFERENCES `rol` (`codigo_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
