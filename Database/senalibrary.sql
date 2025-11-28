-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2025 a las 04:24:21
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
-- Base de datos: `senalibrary`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id_libro` int(11) NOT NULL,
  `titulo_libro` varchar(45) NOT NULL,
  `autor_libro` varchar(45) NOT NULL,
  `ISBN_libro` varchar(45) NOT NULL,
  `categoria_libro` varchar(45) NOT NULL,
  `cantidad_libro` int(11) NOT NULL,
  `disponibilidad_libro` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `libro`
--

INSERT INTO `libro` (`id_libro`, `titulo_libro`, `autor_libro`, `ISBN_libro`, `categoria_libro`, `cantidad_libro`, `disponibilidad_libro`) VALUES
(1, '100 años de soledad', 'Gabriel Garcia Marquez', '1a', 'Ficcion', 9, 'Disponible'),
(2, 'El tunel', 'Pablo Neruda', '2a', 'Ficcion', 9, 'Disponible'),
(3, 'El arte de la guerra', 'Frank', '3a', 'No Ficcion', 8, 'Disponible');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `id_prestamo` int(11) NOT NULL,
  `fk_reserva` int(11) NOT NULL,
  `fecha_prestamo` date NOT NULL,
  `fecha_devolucion_prestamo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`id_prestamo`, `fk_reserva`, `fecha_prestamo`, `fecha_devolucion_prestamo`) VALUES
(1, 4, '2025-11-06', '0000-00-00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `fecha_recogida` date NOT NULL,
  `estado_reserva` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reserva`
--

INSERT INTO `reserva` (`id_reserva`, `fk_usuario`, `fecha_reserva`, `fecha_recogida`, `estado_reserva`) VALUES
(4, 1, '2025-11-05', '2025-11-06', 'Aprobada'),
(5, 1, '2025-11-05', '2025-11-07', 'Cancelada'),
(6, 1, '2025-11-06', '2025-11-08', 'Rechazada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_has_libro`
--

CREATE TABLE `reserva_has_libro` (
  `reserva_id_reserva` int(11) NOT NULL,
  `libro_id_libro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `reserva_has_libro`
--

INSERT INTO `reserva_has_libro` (`reserva_id_reserva`, `libro_id_libro`) VALUES
(4, 1),
(5, 1),
(5, 2),
(6, 1),
(6, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(45) NOT NULL,
  `apellido_usuario` varchar(45) NOT NULL,
  `email_usuario` varchar(45) NOT NULL,
  `password_usuario` varchar(255) DEFAULT NULL,
  `tipo_usuario` varchar(45) NOT NULL,
  `estado` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `apellido_usuario`, `email_usuario`, `password_usuario`, `tipo_usuario`, `estado`) VALUES
(1, 'invi', 'invi', 'invi@gmail.com', '$2y$10$7gwbcGnfglTkPtfc3i1PsO6qPGejJvOiNCOCDriGoDSoUxpxflRou', 'Cliente', 'Activo'),
(2, 'admin', 'admin', 'admin@gmail.com', '$2y$10$Mtgnw50skf9RwGo2iSdWV.VgQJMgDV9F2JpF6sk/oVYfuDnd.K2Ei', 'Administrador', 'Activo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
  ADD PRIMARY KEY (`id_libro`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`id_prestamo`),
  ADD KEY `fk_prestamo_reserva1_idx` (`fk_reserva`);

--
-- Indices de la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reserva_usuario_idx` (`fk_usuario`);

--
-- Indices de la tabla `reserva_has_libro`
--
ALTER TABLE `reserva_has_libro`
  ADD PRIMARY KEY (`reserva_id_reserva`,`libro_id_libro`),
  ADD KEY `fk_reserva_has_libro_libro1_idx` (`libro_id_libro`),
  ADD KEY `fk_reserva_has_libro_reserva1_idx` (`reserva_id_reserva`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `id_prestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `reserva`
--
ALTER TABLE `reserva`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD CONSTRAINT `fk_prestamo_reserva1` FOREIGN KEY (`fk_reserva`) REFERENCES `reserva` (`id_reserva`);

--
-- Filtros para la tabla `reserva`
--
ALTER TABLE `reserva`
  ADD CONSTRAINT `fk_reserva_usuario` FOREIGN KEY (`fk_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `reserva_has_libro`
--
ALTER TABLE `reserva_has_libro`
  ADD CONSTRAINT `fk_reserva_has_libro_libro1` FOREIGN KEY (`libro_id_libro`) REFERENCES `libro` (`id_libro`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_reserva_has_libro_reserva1` FOREIGN KEY (`reserva_id_reserva`) REFERENCES `reserva` (`id_reserva`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
