-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 27-05-2021 a las 15:27:13
-- Versión del servidor: 10.4.18-MariaDB
-- Versión de PHP: 7.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tutienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `direccion` varchar(150) NOT NULL,
  `codigoPostal` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `fechaNacimiento` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enviosDespachados`
--

CREATE TABLE `enviosDespachados` (
  `codigoEnvio` varchar(100) NOT NULL,
  `fechaDespacho` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `enviosEnEspera`
--

CREATE TABLE `enviosEnEspera` (
  `codigoEnvio` varchar(100) NOT NULL,
  `fechaCreado` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `codigo` varchar(100) NOT NULL,
  `emailComprador` varchar(100) DEFAULT NULL,
  `codigoEnvio` varchar(100) NOT NULL,
  `fechaCreado` date NOT NULL,
  `cantidadTotal` int(11) NOT NULL,
  `precioTotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidosContienenProds`
--

CREATE TABLE `pedidosContienenProds` (
  `codigoPedido` varchar(100) NOT NULL,
  `codigoProducto` varchar(100) NOT NULL,
  `cantidadPedida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigo` varchar(100) NOT NULL,
  `emailVendedor` varchar(100) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(600) NOT NULL,
  `precio` double NOT NULL,
  `cantidadDisponible` int(11) NOT NULL,
  `fechaCreado` date NOT NULL,
  `rutaImagen` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `telefonosCliente`
--

CREATE TABLE `telefonosCliente` (
  `email` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `email` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasenia` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `UNIQUE_USUARIO` (`usuario`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `UNIQUE_USUARIO` (`usuario`);

--
-- Indices de la tabla `enviosDespachados`
--
ALTER TABLE `enviosDespachados`
  ADD PRIMARY KEY (`codigoEnvio`);

--
-- Indices de la tabla `enviosEnEspera`
--
ALTER TABLE `enviosEnEspera`
  ADD PRIMARY KEY (`codigoEnvio`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `UNIQUE_CODIGO_ENVIO` (`codigoEnvio`),
  ADD KEY `emailComprador` (`emailComprador`);

--
-- Indices de la tabla `pedidosContienenProds`
--
ALTER TABLE `pedidosContienenProds`
  ADD PRIMARY KEY (`codigoPedido`,`codigoProducto`),
  ADD KEY `codigoProducto` (`codigoProducto`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `emailVendedor` (`emailVendedor`);

--
-- Indices de la tabla `telefonosCliente`
--
ALTER TABLE `telefonosCliente`
  ADD PRIMARY KEY (`email`,`telefono`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `UNIQUE_USUARIO` (`usuario`);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `administradores_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `enviosDespachados`
--
ALTER TABLE `enviosDespachados`
  ADD CONSTRAINT `enviosDespachados_ibfk_1` FOREIGN KEY (`codigoEnvio`) REFERENCES `pedidos` (`codigoEnvio`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `enviosEnEspera`
--
ALTER TABLE `enviosEnEspera`
  ADD CONSTRAINT `enviosEnEspera_ibfk_1` FOREIGN KEY (`codigoEnvio`) REFERENCES `pedidos` (`codigoEnvio`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`emailComprador`) REFERENCES `clientes` (`email`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedidosContienenProds`
--
ALTER TABLE `pedidosContienenProds`
  ADD CONSTRAINT `pedidosContienenProds_ibfk_1` FOREIGN KEY (`codigoProducto`) REFERENCES `productos` (`codigo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidosContienenProds_ibfk_2` FOREIGN KEY (`codigoPedido`) REFERENCES `pedidos` (`codigo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`emailVendedor`) REFERENCES `clientes` (`email`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `telefonosCliente`
--
ALTER TABLE `telefonosCliente`
  ADD CONSTRAINT `telefonosCliente_ibfk_1` FOREIGN KEY (`email`) REFERENCES `clientes` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
