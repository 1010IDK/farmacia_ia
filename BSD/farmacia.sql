-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-10-2025 a las 22:27:04
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
-- Base de datos: `farmacia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_trabajadores`
--

CREATE TABLE `admin_trabajadores` (
  `id_trabajador` int(255) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `nombre` text NOT NULL,
  `apellido` text NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `numero_de_telefono` varchar(255) NOT NULL,
  `nombre_de_usuario` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admin_trabajadores`
--

INSERT INTO `admin_trabajadores` (`id_trabajador`, `id_rol`, `nombre`, `apellido`, `cedula`, `numero_de_telefono`, `nombre_de_usuario`, `correo_electronico`, `contraseña`) VALUES
(41, 1, 'Sebastian', 'Ramirez', '12312321', '123123214', 'sebas1234', 'sebas@gmail.com', '$2y$10$I0ZuhzxbQovGOeEOiVl86.b.VnYtvRVtQA604hOkPJaG2SPhbXkC6'),
(42, 1, 'Reinaldo', 'Polanco', '31035538', '04241583015', 'reipola', 'reinaldopolanco14@gmail.com', '$2y$10$LsxbTUU5d8cQRMfTdK.FT.qwgRfj4fnKEC86knrN0ECuSFLPmPZLe'),
(43, 2, 'Jose', 'Leite', '8908908', '98977989', 'jose112', 'jose@gmail.com', '$2y$10$SsE/e3xq6rhe/FD.jpvV1ORXgOx2qu4oRPnGvrTb0ED2IFFwhYz7C');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `categoria` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `categoria`) VALUES
(1, 'comida y bebidas'),
(2, 'medicamentos'),
(3, 'cuidado personal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_carrito`
--

CREATE TABLE `detalles_carrito` (
  `id_detalle_car` int(11) NOT NULL,
  `id_carrito` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `dc_cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `id_detallle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `dp_cantidad` int(11) NOT NULL,
  `monto_unico` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `direcciones_usuarios`
--

CREATE TABLE `direcciones_usuarios` (
  `id_direccion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `direcciones_usuarios`
--

INSERT INTO `direcciones_usuarios` (`id_direccion`, `id_usuario`, `direccion`) VALUES
(1, 8, 'Los Teques'),
(2, 9, 'Mondalandia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `monto_unico` float NOT NULL,
  `metodo_pago` enum('tarjeta','efectivo','pago movil','bio pago') NOT NULL,
  `estado_pago` enum('pendiente','pagado','rechazado','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` float NOT NULL,
  `cantidad` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `descripcion`, `precio`, `cantidad`, `id_categoria`, `imagen`) VALUES
(14, 'Yan Franco', 'farmaceutico', 12, 2, 2, 'img/prod_68f153025adee.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'administrador'),
(2, 'trabajador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonos_usuarios`
--

CREATE TABLE `telefonos_usuarios` (
  `id_telefono` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `numero_tlf` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `telefonos_usuarios`
--

INSERT INTO `telefonos_usuarios` (`id_telefono`, `id_usuario`, `numero_tlf`) VALUES
(4, 8, 2147483647),
(5, 9, 2147483647);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `correo` varchar(80) NOT NULL,
  `contraseña_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `correo`, `contraseña_hash`) VALUES
(8, 'Reinaldo ', 'reinaldopolanco14@gmail.com', '123'),
(9, 'mondamonda', 'monda@gmail.com', '123');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('activo','inactiva','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_trabajadores`
--
ALTER TABLE `admin_trabajadores`
  ADD PRIMARY KEY (`id_trabajador`),
  ADD UNIQUE KEY `nombre_de_usuario` (`nombre_de_usuario`),
  ADD KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD PRIMARY KEY (`id_carrito`),
  ADD KEY `cantidad` (`id_usuario`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  ADD PRIMARY KEY (`id_detalle_car`),
  ADD KEY `id_carrito` (`id_carrito`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD PRIMARY KEY (`id_detallle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  ADD PRIMARY KEY (`id_direccion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`),
  ADD UNIQUE KEY `id_rol` (`id_rol`);

--
-- Indices de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  ADD PRIMARY KEY (`id_telefono`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_trabajadores`
--
ALTER TABLE `admin_trabajadores`
  MODIFY `id_trabajador` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  MODIFY `id_detalle_car` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detallle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin_trabajadores`
--
ALTER TABLE `admin_trabajadores`
  ADD CONSTRAINT `id_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  ADD CONSTRAINT `carrito_compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  ADD CONSTRAINT `detalles_carrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito_compras` (`id_carrito`),
  ADD CONSTRAINT `detalles_carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `detalles_pedido_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalles_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`);

--
-- Filtros para la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  ADD CONSTRAINT `direcciones_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `id_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  ADD CONSTRAINT `telefonos_usuarios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
