-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-11-2019 a las 10:42:07
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `mydbpdo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE `alumnos` (
  `CODIGO` int(6) UNSIGNED NOT NULL,
  `NOMBRE` varchar(30) NOT NULL,
  `APELLIDOS` varchar(30) NOT NULL,
  `TELEFONO` int(9) DEFAULT NULL,
  `CORREO` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`CODIGO`, `NOMBRE`, `APELLIDOS`, `TELEFONO`, `CORREO`) VALUES
(1, '﻿MARTA', 'BLANCO MELÉNDEZ', 634315599, 'martabl@gmail.com'),
(2, 'MARGARITA', 'MORÓN SÁNCHEZ', 687177286, 'margaritamo@gmail.com'),
(3, 'MARÍA', 'MORALES RODRÍGUEZ', 641787645, 'maríamo@gmail.com'),
(4, 'LAURA', 'JIMÉNEZ JIMÉNEZ', 640132314, 'lauraji@gmail.com'),
(5, 'SARA MARÍA', 'ARZUMENDI TOCÓN', 649118051, 'saramaríaar@gmail.com'),
(6, 'MARÍA DE LA SIERRA', 'ROLDAN GÓMEZ', 621513631, 'maríadelasierraro@gmail.com'),
(7, 'MIGUEL ÁNGEL', 'CARO CRUZ', 647143792, 'miguelángelca@gmail.com'),
(8, 'PABLO', 'FERNÁNDEZ SÁNCHEZ', 619699166, 'pablofe@gmail.com'),
(9, 'ANA', 'ESPEJO MORÁN', 651029704, 'anaes@gmail.com'),
(10, 'JOAQUÍN', 'DEL RIO SÁNCHEZ-MATAMOROS', 654379763, 'joaquínde@gmail.com'),
(11, 'PAULO', 'CALVO AGUILAR', 683888151, 'pauloca@gmail.com'),
(12, 'ELENA', 'BLAZQUEZ GIL', 620469594, 'elenabl@gmail.com'),
(13, 'DIEGO', 'MORILLA MAZA', 631773291, 'diegomo@gmail.com'),
(14, 'RODRIGO ENRIQUE', 'MIGENS FOURNÓN', 628667709, 'rodrigoenriquemi@gmail.com'),
(15, 'ROCIO', 'FERNANDEZ RAMOS', 625948808, 'rociofe@gmail.com'),
(16, 'FÁTIMA', 'JIMÉNEZ SÁNCHEZ', 659779143, 'fátimaji@gmail.com'),
(17, 'MARTA', 'JIMÉNEZ ROLDÁN', 623908275, 'martaji@gmail.com'),
(18, 'CAROLINE', 'BERGER', 615609938, 'carolinebe@gmail.com'),
(19, 'SILVIA', 'MERCADO SANCHEZ', 665219155, 'silviame@gmail.com'),
(20, 'ANDREA', 'FAJARDO PRADO', 636602874, 'andreafa@gmail.com'),
(21, 'MARTA', 'DE TARNO SÁNCHEZ', 661234638, 'martade@gmail.com'),
(22, 'MARÍA ROSARIO', 'MONTERO BALLESTEROS', 651005834, 'maríarosariomo@gmail.com'),
(23, 'CARLOTA', 'DEL CASTILLO PEREDA', 646547147, 'carlotade@gmail.com'),
(24, 'KAREN', 'PLAZA NÚÑEZ', 649554197, 'karenpl@gmail.com'),
(25, 'ANA', 'DE VICENTE BOCANEGRA', 669576891, 'anade@gmail.com'),
(26, 'BLANCA', 'POZAS VIZOSO', 648580297, 'blancapo@gmail.com'),
(27, 'JOSE ALBERTO', 'RODRÍGUEZ RUIZ', 613524341, 'josealbertoro@gmail.com'),
(28, 'LAURA', 'PISANI-FERRY', 680576186, 'laurapi@gmail.com'),
(29, 'IGNACIO', 'MOYANO GÓMEZ', 641534035, 'ignaciomo@gmail.com'),
(30, 'PEDRO MANUEL', 'MERÓN PINO', 682353949, 'pedromanuelme@gmail.com'),
(31, 'CARLOS', 'MARTÍNEZ DOMÍNGUEZ', 622701243, 'carlosma@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`CODIGO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `CODIGO` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
