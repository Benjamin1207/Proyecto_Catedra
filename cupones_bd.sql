-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 10-06-2023 a las 06:11:42
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cupones_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_completo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `admin`
--

INSERT INTO `admin` (`id`, `user`, `password`, `nombre_completo`, `email`) VALUES
(1, 'admin1', 'admin123456', 'Administrador del sistema 1', 'admin.admin@admin.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_completo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `fecha_nac` date NOT NULL,
  `dui` int NOT NULL,
  `direccion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`id`, `user`, `password`, `nombre_completo`, `fecha_nac`, `dui`, `direccion`, `email`) VALUES
(1, 'cliente1', 'cliente123456', 'cliente de prueba', '1991-06-13', 48123336, 'san salvador, san salvador', 'cliente.cliente@cliente.com'),
(2, 'gerardoramirez', 'rg110604', 'Ronald Gerardo Ramírez Guardado', '1993-03-17', 48125559, 'Res. JDLC, pol. K, #99M, Senda 4, San Salvador', 'gerardoudb@udb.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compra`
--

DROP TABLE IF EXISTS `compra`;
CREATE TABLE IF NOT EXISTS `compra` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cod_compra` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_cupon` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_empresa` int NOT NULL,
  `cantidad` int NOT NULL,
  `total` float NOT NULL,
  `fecha_compra` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cupon` (`id_cupon`),
  KEY `FK_cliente` (`id_cliente`),
  KEY `FK_empresa` (`id_empresa`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compra`
--

INSERT INTO `compra` (`id`, `cod_compra`, `id_cupon`, `id_cliente`, `id_empresa`, `cantidad`, `total`, `fecha_compra`) VALUES
(25, '6484100077', 1, 2, 1, 1, 200, '2023-06-10'),
(26, '6484106a0c', 1, 2, 1, 1, 200, '2023-06-10'),
(27, '6484116af2', 1, 2, 1, 1, 200, '2023-06-10'),
(28, '6484117c33', 1, 2, 1, 1, 200, '2023-06-10');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cupon`
--

DROP TABLE IF EXISTS `cupon`;
CREATE TABLE IF NOT EXISTS `cupon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_empresa` int NOT NULL,
  `precio_regular` float NOT NULL,
  `precio_oferta` float NOT NULL,
  `fecha_inic` date NOT NULL COMMENT 'Fecha de inicio oferta',
  `fecha_fin` date NOT NULL COMMENT 'Fecha fin oferta',
  `fecha_canje` date NOT NULL COMMENT 'Fecha limite para uso de cupon',
  `cantidad` int NOT NULL,
  `descripcion` varchar(480) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_id_estado` (`id_estado`) USING BTREE,
  KEY `FK_empresa` (`id_empresa`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cupon`
--

INSERT INTO `cupon` (`id`, `titulo`, `id_empresa`, `precio_regular`, `precio_oferta`, `fecha_inic`, `fecha_fin`, `fecha_canje`, `cantidad`, `descripcion`, `id_estado`) VALUES
(1, 'Ejemplo de titulo', 1, 350, 200, '2023-06-10', '2023-07-31', '2023-08-07', 54, 'Ejemplo de descripcion', 1),
(2, 'EJEMPLO DE TITULO 2', 1, 400, 200, '2023-06-10', '2023-06-22', '2023-09-23', 80, 'EJEMPLO DE DESCRIPCION DE OFERTA 2', 1),
(3, 'Lorem Ipsum', 2, 50.69, 39.99, '2023-06-04', '2023-12-09', '2024-01-10', 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sed pellentesque est. Donec ornare, metus id laoreet luctus, ante sem iaculis neque, in sollicitudin lorem nisi porta augue. Nam libero ex, efficitur vel fermentum ac, venenatis molestie elit. Aliquam nec nunc non augue rutrum sagittis. Nulla malesuada non massa nec fringilla. Maecenas leo elit, rhoncus vitae elementum quis, dictum vel libero. Nunc sodales, nisi ac consectetur tincidunt, arcu justo ornare dui, vit', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

DROP TABLE IF EXISTS `empresa`;
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_empresa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `direccion` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `NIT` bigint NOT NULL,
  `NRC` int NOT NULL,
  `telefono` int NOT NULL,
  `email` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci NOT NULL,
  `porcentaje_comis` float NOT NULL COMMENT 'campo de comision solo utilizada por admin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`id`, `user`, `password`, `nombre_empresa`, `direccion`, `NIT`, `NRC`, `telefono`, `email`, `porcentaje_comis`) VALUES
(1, 'udb_virtual', 'udb123456', 'UDB EL SALVADOR', 'CIUDADELA DON BOSCO, SOYAPANGO', 12345678912345, 12125566, 55665522, 'udbvirtual@gmail.com', 0.02),
(2, 'systemdbsv', 'sy1234', 'SystemDB, S.A. de C.V', 'Aenean ac tincidunt nisi, eget malesuada turpis. Aenean ac enim nec ex luctus porttitor ut pharetra purus', 123456789456, 123644, 22563369, 'systemdb_sv@gmail.com', 0.01);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado`
--

DROP TABLE IF EXISTS `estado`;
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estado`
--

INSERT INTO `estado` (`id`, `estado`) VALUES
(1, 'Disponible'),
(2, 'No Disponible');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_2` FOREIGN KEY (`id_cupon`) REFERENCES `cupon` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `compra_ibfk_3` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cupon`
--
ALTER TABLE `cupon`
  ADD CONSTRAINT `cupon_ibfk_1` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cupon_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
