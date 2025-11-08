-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-11-2025 a las 18:05:18
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
(48, 2, 'Reinaldo', 'Polanco', '27463791', '04129837722', 'reipola17', 'reipola@gmail.com', '$2y$10$5OgbEx63kyfUscbj6KR40uGfEY4NahQsGzZblhvxp/vv4TUPl.HgO'),
(49, 1, 'miguel', 'flores', '30667634', '04149027363', 'legumin', 'mafr737@gmail.com', '$2y$10$QfLfkVxaovhC1LKnciT42ei9QI2sX6wBwK3HBACG.r1fqbDloSHVO'),
(50, 2, 'miguel', 'a', '31860457', '04149027563', 'usuario', 'maf737@gmail.com', '$2y$10$CbOBRZXk92ijBuecaHyLWuELJnKbbWTear8O/4qILdAJw/WvJAPYK');

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
-- Estructura de tabla para la tabla `detalles_pago`
--

CREATE TABLE `detalles_pago` (
  `id_detalle_pago` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `metodo_pago` enum('divisas','bolivares','tarjeta','pago_movil') NOT NULL,
  `monto_recibido` decimal(10,2) DEFAULT NULL,
  `vuelto` decimal(10,2) DEFAULT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `referencia_pago` varchar(20) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
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
(8, 15, 'los teques');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id_pago` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `monto_unico` float NOT NULL,
  `metodo_pago` enum('divisas','bolivares','tarjeta','pago_movil') NOT NULL,
  `estado_pago` enum('pendiente','pagado','rechazado','') NOT NULL,
  `banco` varchar(100) DEFAULT NULL,
  `referencia` varchar(20) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id_pago`, `id_venta`, `id_usuario`, `monto_unico`, `metodo_pago`, `estado_pago`, `banco`, `referencia`, `fecha_pago`) VALUES
(17, 15, 14, 40, 'divisas', 'pagado', NULL, NULL, '2025-11-02 12:22:40'),
(18, 16, 14, 40, 'bolivares', 'pagado', NULL, NULL, '2025-11-02 12:22:57'),
(19, 17, 14, 40, 'tarjeta', 'pagado', 'Banco del Tesoro', NULL, '2025-11-02 12:23:17'),
(20, 18, 14, 40, 'pago_movil', 'pagado', 'Banco Mercantil', '4590', '2025-11-02 12:23:39'),
(21, 19, 14, 69, 'pago_movil', 'pagado', 'Banco Nacional de Crédito (BNC)', '8742', '2025-11-02 12:37:44'),
(22, 20, 14, 69, 'bolivares', 'pagado', NULL, NULL, '2025-11-02 12:38:28'),
(23, 21, 14, 250, 'divisas', 'pagado', NULL, NULL, '2025-11-02 12:40:56'),
(24, 22, 15, 12250, 'tarjeta', 'pagado', 'Banco de Venezuela (BDV)', NULL, '2025-11-02 13:49:23'),
(25, 23, 15, 250, 'tarjeta', 'pagado', 'Banco Venezolano de Crédito (BVC)', NULL, '2025-11-08 10:38:19'),
(26, 25, 15, 16000, 'tarjeta', 'pagado', 'Banco de Venezuela (BDV)', NULL, '2025-11-08 10:52:47'),
(27, 26, 15, 4430, 'tarjeta', 'pagado', 'Banco Nacional de Crédito (BNC)', NULL, '2025-11-08 12:15:16'),
(28, 29, 15, 5440, 'divisas', 'pagado', NULL, NULL, '2025-11-08 13:03:25');

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
(19, 'Dorixina Flex Lisina ', '+ Ciclobenzaprina 125mg/5mg Megalabs x 20 Comprimidos Ciclobenzaprina 125mg/5mg Megalabs x 20 Comprimidos Tratamiento coadyuvante de patologías que cursan con contractura muscular.', 250, 34, 2, 'img/prod_68f38438edb86.jpg'),
(28, 'Acetaminofén ', '+ clorfenamina maleato 500mg/4mg X10 tabletas CLORACE', 200, 20, 2, 'img/prod_690f5b58b5a0f.jpg'),
(29, 'Acetaminofen 650mg', '10 comprimidos genven', 360, 47, 2, 'img/prod_690f5bb826217.jpg'),
(30, 'Ibuprofeno 400mg', 'x 10tab BRUGESIC ', 550, 30, 2, 'img/prod_690f5bf3bc19e.png'),
(31, 'Diclofenac Potásico 50 mg', 'Medigen Oftalmi Caja x 10 Tabletas', 100, 8, 2, 'img/prod_690f5c31631f8.jpg'),
(32, 'Acetaminofén 650 mg Atamel', 'Forte Calox Caja x 10 Tabletas Atamel Forte tratamiento sintomàtico de la fiebre y del dolor de leve intensidad.', 380, 15, 2, 'img/prod_690f5c6448b7a.jpg'),
(33, 'Gasa Estéril 4x4 Compomedica Sobre x 2 und', 'Gasa estéril 100% algodón. Bordes doblados para evitar el desprendimiento de hilos. Tejido de alta absorción que no deja pelusa ni residuos. Posee una textura suave ideal para la limpieza de heridas.', 70, 10, 2, 'img/prod_690f5cb676b82.jpg'),
(34, 'Azitromicina 375ml', 'Saver Elmor Polvo Para Suspensión x 200mg', 1585, 8, 2, 'img/prod_690f5d0f87919.jpg'),
(35, 'Flavoxato Clorhidrato 200 mg', 'Genurin Elmor Caja x 10 Gragea', 1462, 5, 2, 'img/prod_690f5d39d577e.png'),
(36, 'Albicar Albendazol 200Mg', 'Elmor Caja x 2 Tabletas', 300, 5, 2, 'img/prod_690f5d8e4d0b9.png'),
(37, 'Refresco Coca-Cola Sabor Original 2 Lt', 'Bebida gaseosa con sabor a cola negra en presentación de 2 L. Ideal para compartir y acompañar tus comidas, celebrar la vida y abrazar la magia de cada momento.', 300, 145, 1, 'img/prod_690f5dc8c8521.jpg'),
(38, 'Refresco Frescolita 2 Lt', 'Bebida gaseosa con sabor a colita en presentación de 2 L. Ideal para refrescar y compartir tus comidas y momentos especiales con su perfecto balance de burbujas, color único, sabor inconfundible y aroma original.', 380, 123, 1, 'img/prod_690f5e128ae43.png'),
(39, 'Refresco Fanta Toronja 2 Lt', 'refresco', 450, 33, 1, 'img/prod_690f5ea2584cd.jpg'),
(40, 'Refresco Fanta Toronja Lata x 355 ml', 'Descubre el burbujeante sabor de Fanta Toronja en presentación Lata 355 ml y haz tus snacks más divertidos.', 200, 234, 1, 'img/prod_690f5efdb9e6b.jpg'),
(41, 'Schweppes Aguakina Lata 355 ml', 'Bebida gaseosa sin alcohol aromatizada con quinina, en presentación de lata 355 ML. Ideal para combinar con tus bebidas favoritas y realzar su sabor dándole mayor efervescencia y sofisticación. Mezclador por excelencia y acompañante perfecto para tu vida social. Disponible para bebedores exigentes y magistrales cocteleras.', 450, 213, 1, 'img/prod_690f5f2e00d17.jpg'),
(42, 'Bebida energética ', 'Monster Clásico Lata 473 ml', 740, 33, 1, 'img/prod_690f5f6752b10.png'),
(43, 'Bebida Energética', 'Monster Mango Loco Lata 473 ml', 715, 12, 1, 'img/prod_690f5f8dd2be9.png'),
(44, 'Galletas Soda Puig 240Gr', 'Galletas crujientes y tostaditas.', 400, 123, 1, 'img/prod_690f6148ce10c.jpg'),
(45, 'Galleta de Chocolate Samba Savoy Fresa x 32 gr', 'Galleta cubierta rellena sabor a fresa', 100, 22, 1, 'img/prod_690f61835d645.png'),
(46, 'Galletas Oreo Navidad Americano Tubo x 96 gr', 'Galletas de chocolate con vainilla en el centro version navidad', 120, 213, 1, 'img/prod_690f61aba3c12.png'),
(47, 'Snack Cheese Tris x 150 gr', 'Producto de Frito Lay, presentación 150 gr. Cereal de maíz inflado con queso', 212, 123, 1, 'img/prod_690f61ddbd5c1.png'),
(48, 'Snack Pepito 80Gr', 'Cereal de maíz inflado con sabor a queso', 300, 122, 1, 'img/prod_690f61fcd2476.jpg'),
(49, 'Chocolate St Moritz Flaquito Nevado 30Gr', 'Galleta crocante de vainilla', 122, 22, 1, 'img/prod_690f62288c8de.jpg'),
(50, 'Chocolate Savoy Con Leche x 30 gr', 'Chocolate con leche.', 100, 122, 1, 'img/prod_690f6246504fa.png'),
(51, 'Caramelos Freegells Extra Fuerte 31.7Gr', 'Libérate del aburrimiento y sumérgete en la frescura de los Caramelos Freegells. ¡Tu paladar te lo agradecerá!', 50, 222, 1, 'img/prod_690f62605b0a0.jpg'),
(52, 'Snack Doritos Mega Queso x 45 gr', 'Hojuelas de maíz tostadas con sabor a queso', 220, 122, 1, 'img/prod_690f627bbfba4.jpg'),
(53, 'Crema Dental Colgate Triple Accion 75Ml', 'Obtén triple beneficio: Protección, Blancura, y Frescura, con tu crema dental Colgate Triple Acción.', 500, 122, 3, 'img/prod_690f644140e29.jpg'),
(54, 'Toallas Desmaquillantes Farmatodo x 25 und', 'Toallas desmaquillantes Farmatodo para todo tipo de piel. Una manera cómoda y rápida de obtener una limpieza facial completa.', 250, 50, 3, 'img/prod_690f646a93cbd.png'),
(55, 'Exfoliante Corporal BACC Coco Regenerador Y Suavizante x 200 ml', 'El Exfoliante Bath Salt de Coco elimina las células muertas de la piel dejándola suave e iluminada, prepara tu piel para la depilación y permite que la epidermis quede limpia y tonificada para el bronceado.', 1000, 120, 3, 'img/prod_690f64b485147.jpg'),
(56, 'Máquina Afeitar Dorco 2Hoj X5Unid Hombre Desechable', 'Afeitadora', 340, 96, 3, 'img/prod_690f64dfc7471.jpg'),
(57, 'Desodorante Dioxogen Hipoalergénico ', 'Desodorante Antitraspirante Hipoalergénico con Bicarbonato Neutralizador de Olores. Dermatológicamente Probado. Libre de Alcohol. 48 Horas de Protección. Roll-On Bicarbonato x 90 gr', 650, 12, 3, 'img/prod_690f6519e9ea9.png'),
(58, 'Desodorante Rollon Every Night Bio Baby Pink 90 Gr', 'Desodorante Roll on Bionutriente con Aloe Vera y Vitamina E Baby Pink', 500, 122, 3, 'img/prod_690f653f8dfd0.jpg'),
(59, 'Jabón En Barra Protex Avena x 110 gr', 'Jabón Protex Avena elimina 99.9% de las bacterias naturalmente.', 300, 122, 3, 'img/prod_690f657f94edd.jpg'),
(60, 'Jabón En Barra Rexona Limpieza Profunda x 120 gr', 'Jabón en barra antibacterial', 122, 22, 3, 'img/prod_690f659d0b941.jpg'),
(61, 'Champu Anticaspa Farmatodo Frescura x 200 ml', 'shampoo', 550, 122, 3, 'img/prod_690f65ba2c287.jpg'),
(62, 'Champu Drene Proh Complex Cabello Seco Maltratado 370Ml', 'Drene con ProH Complex te ayuda a tener el cabello que deseas con ingredientes ideales Keratina, Vitamina E y Pantenol.', 1200, 122, 3, 'img/prod_690f65d284aaa.jpg'),
(63, 'Champu Pantene Restauración 200Ml', 'Pantene con su fórmula multivitaminas nutre tu cabello desde la raíz para que crezca largo y fuerte hasta las puntas.', 1500, 212, 3, 'img/prod_690f65ec30ed1.jpg');

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
(11, 15, 2147483647);

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
(14, 'Reinaldo ', 'reinaldopolanco14@gmail.com', '$2y$10$h18zIEA8HmRAJM3nJ5JTM.8TtDzP/dpB3zUIt.t0/eeGj6bgyDBbq'),
(15, 'Barbara Antoima', 'mafr737@gmail.com', '$2y$10$qh39hRM22rVLA9vWC6cyQujs3/Ab5jmIhywqhIcc2EoaKe9P3LAo.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `codigo_unico` varchar(20) DEFAULT NULL,
  `estado` enum('apartado','pagado','finalizado','cancelado') DEFAULT 'apartado',
  `fecha_apartado` datetime DEFAULT NULL
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
-- Indices de la tabla `detalles_pago`
--
ALTER TABLE `detalles_pago`
  ADD PRIMARY KEY (`id_detalle_pago`),
  ADD KEY `id_venta` (`id_venta`);

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
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_usuario` (`id_usuario`);

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
  MODIFY `id_trabajador` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `carrito_compras`
--
ALTER TABLE `carrito_compras`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalles_carrito`
--
ALTER TABLE `detalles_carrito`
  MODIFY `id_detalle_car` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT de la tabla `detalles_pago`
--
ALTER TABLE `detalles_pago`
  MODIFY `id_detalle_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  MODIFY `id_detallle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `direcciones_usuarios`
--
ALTER TABLE `direcciones_usuarios`
  MODIFY `id_direccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `telefonos_usuarios`
--
ALTER TABLE `telefonos_usuarios`
  MODIFY `id_telefono` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

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
-- Filtros para la tabla `detalles_pago`
--
ALTER TABLE `detalles_pago`
  ADD CONSTRAINT `detalles_pago_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

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
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

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
