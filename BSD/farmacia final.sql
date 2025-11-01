-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-10-2025 a las 02:44:18
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
(43, 2, 'Jose', 'Leite', '8908908', '98977989', 'jose112', 'jose@gmail.com', '$2y$10$SsE/e3xq6rhe/FD.jpvV1ORXgOx2qu4oRPnGvrTb0ED2IFFwhYz7C'),
(47, 1, 'miguel', 'flores', '30667634', '04149027363', 'legumin', 'mafr737@gmail.com', '$2y$10$CK0f75DiIkAAOJAeYjRSb.1qDoQxnKtPIReSFnpjRUm1m.5cMSdN6');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_compras`
--

CREATE TABLE `carrito_compras` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_creacion` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito_compras`
--

INSERT INTO `carrito_compras` (`id_carrito`, `id_usuario`, `fecha_creacion`) VALUES
(1, 12, '2025-10-25 '),
(3, 13, '2025-10-30 ');

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

--
-- Volcado de datos para la tabla `detalles_carrito`
--

INSERT INTO `detalles_carrito` (`id_detalle_car`, `id_carrito`, `id_producto`, `dc_cantidad`) VALUES
(6, 1, 24, 3),
(7, 1, 16, 1),
(8, 1, 21, 3),
(12, 3, 20, 1);

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

--
-- Volcado de datos para la tabla `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`id_detallle`, `id_venta`, `id_producto`, `dp_cantidad`, `monto_unico`) VALUES
(1, 1, 16, 1, 100),
(2, 1, 21, 5, 990);

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
(2, 9, 'Mondalandia'),
(4, 11, 'los teques'),
(5, 12, 'los teques'),
(6, 13, 'los teques');

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
(15, 'bulbasur', 'Bulbasaur, conocido como Fushigidane en Japón, es una especie ficticia de Pokémon de la franquicia Pokémon de Nintendo y Game Freak. Presentado por primera vez en los videojuegos Pokémon Rojo y Azul, fue creado por Atsuko Nishida y su diseño finalizado por Ken Sugimori.', 1500, 1, 2, 'img/prod_68f25d6e8f1da.jpeg'),
(16, 'squirtle', 'Squirtle, conocido como Zenigame en Japón, es una especie de Pokémon de la franquicia Pokémon de Nintendo y Game Freak. Fue diseñado por Atsuko Nishida. Su nombre se cambió de Zenigame a Squirtle durante la localización de la serie al inglés para hacerlo más ingenioso y descriptivo.', 100, 1, 1, 'img/prod_68f25df1dedf6.jpeg'),
(17, 'charmander', 'Charizard es una de las criaturas de la franquicia Pokémon. Se trata de un pokémon tipo fuego/volador, que aparece por primera vez en Pokémon Red y Blue, donde puede ser obtenido si el jugador elige ...', 777777, 2, 3, 'img/prod_68f25e8c232ed.jpeg'),
(18, 'gotas para la nariz', 'Indicaciones: 2 a 3 gotas 3 veces al día en cada fosa nasal (cada 8 horas).  Advertencias: si los síntomas persisten y no se observa mejoría después de 2 a 3 días con el uso de este medicamento, suspéndase y consulte al médico. La administración prolongada de este producto puede causar nerviosismo, intranquilidad e insomnio.  ', 550, 10, 2, 'img/prod_68f26d30631cc.jpg'),
(19, 'Dorixina Flex Lisina ', '+ Ciclobenzaprina 125mg/5mg Megalabs x 20 Comprimidos Ciclobenzaprina 125mg/5mg Megalabs x 20 Comprimidos Tratamiento coadyuvante de patologías que cursan con contractura muscular.', 3500, 5, 2, 'img/prod_68f38438edb86.jpg'),
(20, 'Champu Farmatodo Reparador Macademia y Aguacate 500Ml', 'Regenera y tonifica el cuero cabelludo', 680, 1, 3, 'img/prod_68f38458116ea.jpg'),
(21, 'Refresco Pepsi Cola x 1 Lt', 'Refresco sabor a cola negra 1lt', 198, 5, 1, 'img/prod_68f38472cb8b1.jpg'),
(22, 'Diclofenac Potásico 50 mg Caja x 10 Tabletas', 'Composición: Diclofenac Potasico', 69.3, 5, 2, 'img/prod_68f3856ca3985.jpg'),
(23, 'Galletas Le Biscuit Mini Piruetas x 150 gr', 'Mini barquillas rellenas de chocolate y avellanas.', 240, 5, 1, 'img/prod_68f38586b2cd9.jpg'),
(24, 'Samba Fresa x 32 gr', 'Galleta cubierta rellena sabor a fresa', 136.72, 5, 1, 'img/prod_68f385ab03317.png');

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
(5, 9, 2147483647),
(7, 11, 2147483647),
(8, 12, 2147483647),
(9, 13, 2147483647);

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
(9, 'mondamonda', 'monda@gmail.com', '123'),
(11, 'Barbara Antoima', 'm@gmail.com', '$2y$10$tiLPUFqYusZKHnJ6EF034.4eCtoBbzlrmPmx/uvC0OFBOF..qAW6a'),
(12, 'Main Shaco', 'shaco@gmail.com', '$2y$10$SagoFjUykq/fw2kutW0bduFong5UWPlGHNEpqFk6Vp0dhqSB0yW76'),
(13, 'miguel', 'mafr737@gmail.com', '$2y$10$0f1c.SQXmktCvrf8HrlluOBQDoKtSxk4rEAUx/rb63Ci5V/bAqqBu');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `codigo_unico` varchar(20) DEFAULT NULL,
  `estado` enum('apartado','pagado','finalizado','cancelado') DEFAULT 'apartado',
  `metodo_pago` varchar(50) DEFAULT NULL,
  `fecha_apartado` datetime DEFAULT NULL,
  `fecha_pagado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_usuario`, `codigo_unico`, `estado`, `metodo_pago`, `fecha_apartado`, `fecha_pagado`) VALUES
(1, 13, 'ygRcGd2p1N', 'apartado', NULL, '2025-10-30 20:47:54', NULL);

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
  ADD UNIQUE KEY `codigo_unico` (`codigo_unico`),
  ADD KEY `id_cliente` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_trabajadores`
--
ALTER TABLE `admin_trabajadores`
  MODIFY `id_trabajador` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  MODIFY `id_detalle_car` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detallle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
