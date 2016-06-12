-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2016 a las 17:19:52
-- Versión del servidor: 5.7.12-log
-- Versión de PHP: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `643b4d11c092cc1ea32a96bdb4f8da93`
--

drop database if exists 643b4d11c092cc1ea32a96bdb4f8da93;

create database 643b4d11c092cc1ea32a96bdb4f8da93;

grant all on 643b4d11c092cc1ea32a96bdb4f8da93.* to '643b4d11c092cc1e'@'localhost' identified by 'sekret';

USE `643b4d11c092cc1ea32a96bdb4f8da93` ;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descripcion` varchar(200) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  `idsubasta` int(11) DEFAULT NULL,
  `idproducto` int(11) DEFAULT NULL,
  `idpuja` int(11) DEFAULT NULL,
  `idlote` int(11) DEFAULT NULL,
  `nombreproducto` varchar(45) DEFAULT NULL,
  `nombrelote` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id`, `descripcion`, `idusuario`, `idsubasta`, `idproducto`, `idpuja`, `idlote`, `nombreproducto`, `nombrelote`) VALUES
(67, 'Se ha creado el administrador "admin"', 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(200) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `idsubasta` int(11) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `fecha` date NOT NULL,
  `imagen` varchar(45) DEFAULT NULL,
  `idlote` int(11) DEFAULT NULL,
  `idsubasta` int(11) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `fecha`, `imagen`, `idlote`, `idsubasta`, `idusuario`) VALUES
(1, 'Coche Tesla', 'Poco utilizado, en perfectas condiciones.', '2016-06-12', 'img/imagen1.jpeg', NULL, NULL, 2),
(2, 'Ordenador Asus GL-552', 'Memoria Ram: 16GB\r\nIntel Core i7 6700 HQ\r\nDisco Duro SSD: 128 GB', '2016-06-12', '', NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pujas`
--

CREATE TABLE `pujas` (
  `id` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `cantidad` float NOT NULL,
  `idsubasta` int(11) NOT NULL,
  `idpostor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subastas`
--

CREATE TABLE `subastas` (
  `id` int(11) NOT NULL,
  `tipo` int(11) NOT NULL,
  `precioinicial` float DEFAULT NULL,
  `fechainicio` datetime NOT NULL,
  `fechacierre` datetime DEFAULT NULL,
  `fechasegundapuja` datetime DEFAULT NULL,
  `cambioprecio` float DEFAULT NULL,
  `tiempocambioprecio` int(11) DEFAULT NULL,
  `precioactual` float DEFAULT NULL,
  `fechaactual` date DEFAULT NULL,
  `idsubastador` int(11) DEFAULT NULL,
  `idpujaganadora` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `apellidos` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `tipo`, `usuario`, `password`, `nombre`, `apellidos`) VALUES
(1, 'administrador', 'admin', 'sekret', 'Juan', 'García'),
(2, 'subastador', 'subastador', 'sekrets', 'María', 'González'),
(3, 'postor', 'postor', 'sekretp', 'Daniel', 'Pérez');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idusuario_idx` (`idusuario`),
  ADD KEY `idsubasta_idx` (`idsubasta`),
  ADD KEY `idproducto_idx` (`idproducto`),
  ADD KEY `idpujaindex` (`idpuja`),
  ADD KEY `idloteindex` (`idlote`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idsubasta_idx` (`idsubasta`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idlote_idx` (`idlote`),
  ADD KEY `idsubasta_idx` (`idsubasta`),
  ADD KEY `proidusuario_index` (`idusuario`);

--
-- Indices de la tabla `pujas`
--
ALTER TABLE `pujas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idsubasta_idx` (`idsubasta`),
  ADD KEY `idpujador_idx` (`idpostor`);

--
-- Indices de la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idsubastador_idx` (`idsubastador`),
  ADD KEY `indexganador` (`idpujaganadora`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;
--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT de la tabla `pujas`
--
ALTER TABLE `pujas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;
--
-- AUTO_INCREMENT de la tabla `subastas`
--
ALTER TABLE `subastas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `lotesubasta` FOREIGN KEY (`idsubasta`) REFERENCES `subastas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `lotesusuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productolote` FOREIGN KEY (`idlote`) REFERENCES `lotes` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `productosubasta` FOREIGN KEY (`idsubasta`) REFERENCES `subastas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `productousuario` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `pujas`
--
ALTER TABLE `pujas`
  ADD CONSTRAINT `pujapujador` FOREIGN KEY (`idpostor`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `pujasubasta` FOREIGN KEY (`idsubasta`) REFERENCES `subastas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Filtros para la tabla `subastas`
--
ALTER TABLE `subastas`
  ADD CONSTRAINT `subastapujaganadora` FOREIGN KEY (`idpujaganadora`) REFERENCES `pujas` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `subastasubastador` FOREIGN KEY (`idsubastador`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
