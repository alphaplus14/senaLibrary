-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2025 a las 01:03:42
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bjc8wdksbxeffnx2flqs-mysql_services_clever-cloud_com`
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
  `disponibilidad_libro` varchar(45) NOT NULL,
  `cantidad_libro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva`
--

CREATE TABLE `reserva` (
  `id_reserva` int(11) NOT NULL,
  `fk_usuario` int(11) NOT NULL,
  `fecha_reserva` date NOT NULL,
  `estado_reserva` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_has_libro`
--

CREATE TABLE `reserva_has_libro` (
  `reserva_id_reserva` int(11) NOT NULL,
  `libro_id_libro` int(11) NOT NULL,
  `libro_disponibilidad_libro` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(1, 'juan', 'camilo', 'juanCamilo@gmail.com', '123', 'administrador', 'Inactivo'),
(2, 'cesar', 'rodas', 'admin@gmail.com', '$2y$10$KnBkLQBkP7LMUDotGqK/QeKukbZ6wfPlf5TCxNMq5QjrtAAH7v4pq', 'Administrador', 'Activo'),
(3, 'Makalov', 'Piedrahita', 'makalov@gmail.com', '$2y$10$dFh8.mBdF5/QK2R8fLe9Su1IFe8a/20177Bu0zSNJzdYN9NCP7lD.', 'Administrador', 'Activo');

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
  ADD KEY `fk_reserva_has_libro_libro1_idx` (`libro_id_libro`,`libro_disponibilidad_libro`),
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
  MODIFY `id_libro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `fk_reserva_has_libro_reserva1` FOREIGN KEY (`reserva_id_reserva`) REFERENCES `reserva` (`id_reserva`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
