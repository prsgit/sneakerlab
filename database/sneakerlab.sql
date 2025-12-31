-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-12-2025 a las 15:52:40
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
-- Base de datos: `sneakerlab`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_de_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_item`
--

CREATE TABLE `carrito_item` (
  `id_carrito_item` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedido`
--

CREATE TABLE `linea_pedido` (
  `id_linea` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `precio_unitario` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `linea_pedido`
--

INSERT INTO `linea_pedido` (`id_linea`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 2, 1, 1, 99.99),
(2, 3, 1, 1, 80.00),
(3, 4, 1, 1, 80.00),
(4, 5, 1, 1, 80.00),
(5, 6, 3, 1, 120.00),
(6, 7, 1, 1, 80.00),
(7, 7, 4, 1, 80.00),
(8, 7, 7, 1, 160.00),
(9, 8, 1, 1, 80.00),
(10, 8, 3, 1, 120.00),
(11, 9, 1, 1, 80.00),
(12, 9, 4, 1, 80.00),
(13, 10, 1, 1, 80.00),
(14, 10, 3, 1, 120.00),
(15, 11, 7, 1, 160.00),
(16, 12, 8, 1, 110.00),
(17, 13, 5, 1, 55.00),
(18, 14, 5, 1, 55.00),
(19, 14, 8, 1, 110.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `id_usuario`, `fecha`, `total`, `estado`) VALUES
(2, 1, '2025-12-15 18:26:44', 99.99, 'pendiente'),
(3, 1, '2025-12-26 10:35:12', 80.00, 'pendiente'),
(4, 1, '2025-12-29 08:35:31', 80.00, 'pendiente'),
(5, 1, '2025-12-29 11:31:26', 80.00, 'pendiente'),
(6, 1, '2025-12-29 14:18:58', 120.00, 'pendiente'),
(7, 1, '2025-12-29 18:09:18', 320.00, 'pendiente'),
(8, 1, '2025-12-29 18:11:07', 200.00, 'pendiente'),
(9, 6, '2025-12-29 18:24:09', 160.00, 'pendiente'),
(10, 10, '2025-12-30 13:53:33', 200.00, 'pendiente'),
(11, 7, '2025-12-30 18:30:39', 160.00, 'pendiente'),
(12, 7, '2025-12-30 18:39:01', 110.00, 'pendiente'),
(13, 7, '2025-12-31 08:33:30', 55.00, 'pendiente'),
(14, 18, '2025-12-31 13:35:59', 165.00, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen_url` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio`, `stock`, `imagen_url`, `activo`) VALUES
(1, 'Nike Air Max', 'Zapatillas deportivas para running', 80.00, 5, 'img_694bf8e18206e.jpg', 1),
(3, 'New Balance 530', 'Zapatilla de running', 120.00, 5, 'img_6954076d51e0a.jpg', 1),
(4, 'Reebok Energen Lux', 'Zapatilla runnig', 80.00, 3, 'img_69527d537712b.jpg', 1),
(5, 'Nike Classic Cortez', 'Zapatilla casual', 55.00, 5, 'img_69527d9348e7b.jpg', 1),
(7, 'Nike Vomero 5', 'Zapatilla de running', 160.00, 5, 'img_69527e4090b16.jpg', 0),
(8, 'New Balance 2002r', 'Zapatilla de runnig', 110.00, 5, 'img_69527ebeb5dbc.jpg', 1),
(9, 'Nike Dunk Low', 'Zapatilla casual', 111.00, 10, 'img_6954bdd1cb8ab6.43161072.jpg', 0),
(10, 'Nike Dunk Low', 'Zapatilla casual', 111.00, 5, 'img_6954ce4aafad44.21205345.jpg', 0),
(11, 'Nike Vomero 5', 'Zapatilla de runnig', 160.00, 5, 'img_6954ce87901552.79013455.jpg', 0),
(12, 'Nike Vomero 5', 'Zapatilla de runnig', 150.00, 5, 'img_6954d0db792426.53080840.jpg', 1),
(13, 'Adidas', 'Zapatilla de runnig', 100.00, 5, 'img_695514335ff394.84972288.jpg', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `contrasena`, `rol`, `direccion`, `telefono`, `activo`) VALUES
(1, 'Cliente_Prueba', 'cliente@hotmail.com', '$2y$10$yJdsEG2qwTBrznZSdehfs.AGdRhFI4FJmhPLAEenDHLRIAqC4Gt8K', 'cliente', 'calle Alegría 22', '628302587', 0),
(6, 'prueba', 'prueba@prueba.es', '$2y$10$ySgC3i/fr7Nl/OaQBCGg3.OKEugxHsnVMsTVCti66mL5j7HFfM.Vq', 'cliente', 'Calle Prueba 10', '96325874', 0),
(7, 'Administrador', 'admin@sneakerlab.com', '$2y$10$XGukTVWoBk.gIXVcOfruZOkkd2QrqnaGryPwtb1laqm.zUO3gsIjm', 'admin', NULL, NULL, 1),
(8, 'Admin_prueba', 'admin_prueba@hotmail.es', '$2y$10$Tr6t5ivhOwdWPFQYi291b.u4mJDhYlJ6sz4jbbj6uBzPuK3XkVkYK', 'admin', 'Calle Prueba 78', '96325874', 0),
(9, 'Cliente1', 'cliente1@gmail.com', '$2y$10$RpHxLgwG.EBtBeZvgGaaHeqjTYSEyW7c2CRADoFF.nLTwEqb64NNG', 'cliente', 'Calle Esperanza 15', '96325874', 0),
(10, 'cliente2', 'cliente2@hotmail.com', '$2y$10$1ye5ug3LglwD/fUUDJBmhOj0MNQ9ZuW52gukttJ/0yB8NRO1oQ.4y', 'cliente', 'Calle Jacinto 33', '96325874', 0),
(11, 'cliente3', 'cliente3@gmail.com', '$2y$10$nXgIAczQDWhq91AaRYOhhu933cSAyGammlPkjT6rc6BU4xx0IVjrO', 'cliente', 'Calle Luna 78', '74125896', 1),
(12, 'Pepe', 'pepe@pepe.es', '$2y$10$0SEQLnwHLxo8y3eMm2zGKuvz1pNS5Kz1HXDDQZ5xxms.P57O89wey', 'cliente', 'Calle Lirio 85', '8521471263', 1),
(13, 'Juan', 'juan@juan.es', '$2y$10$O3hbZLsYflKVhLbQtbPnw.dYxvWcbI/iqDySu8WfKzscZCMLu/PMG', 'cliente', 'Calle Vera 5', '96325874', 0),
(14, 'Pedro', 'pedro@gmail.com', '$2y$10$lSx/L5eRxLZhMSPbXjasOOtAeBEbgaUHzdPlDNab2NMO1QkSh6n/O', 'cliente', 'Calle Talavera 45', '85214785', 1),
(15, 'admin1', 'admin1@hotmail.com', '$2y$10$QAMyczWSIj7X.6Agmdhy2.B9s6FAe/HDLedvQxFwVZ5DfqOi6kYrO', 'admin', 'Calle Ilusión 55', '85214785', 0),
(16, 'admin', 'admin@admin.es', '$2y$10$Rj4nLD0ILDdhqs9iqoSG2e/QnoqmQaPa1sg3i314rq1f2cv9DpmmW', 'admin', 'AVDA. López de Vega 33', '85214785', 0),
(17, 'cliente', 'clienteprueba@gmail.com', '$2y$10$qACTsMiPhscRq1CuRHRSLewZx.gPMt82nrIM71tLzoCAOp5CGrFy2', 'cliente', 'Calle Laurel 12', '74125896', 0),
(18, 'cliente', 'cliente@cliente.es', '$2y$10$2o4pk3P4LqLg.5Ri2i8ZVe448vGu7k33EaSNGnu0ILhM2A34jt4Yu', 'cliente', 'Calle Marbella 25', '85214787', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  ADD PRIMARY KEY (`id_carrito_item`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD PRIMARY KEY (`id_linea`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  MODIFY `id_carrito_item` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  MODIFY `id_linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_item`
--
ALTER TABLE `carrito_item`
  ADD CONSTRAINT `carrito_item_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id_carrito`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_item_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD CONSTRAINT `linea_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `linea_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
