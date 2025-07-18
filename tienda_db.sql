-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_db`
--
CREATE DATABASE IF NOT EXISTS `tienda_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tienda_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text,
  `precio` decimal(10,2) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `imagen_nombre` varchar(255) DEFAULT NULL,
  `imagen_tipo` varchar(50) DEFAULT NULL,
  `stock` int DEFAULT '0',
  `id_categoria` int DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `talla`
--

CREATE TABLE `talla` (
  `id` int NOT NULL AUTO_INCREMENT,
  `talla` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `producto_talla`
--

CREATE TABLE `producto_talla` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_producto` int NOT NULL,
  `id_talla` int NOT NULL,
  `stock` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_producto` (`id_producto`),
  KEY `id_talla` (`id_talla`),
  CONSTRAINT `producto_talla_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `producto_talla_ibfk_2` FOREIGN KEY (`id_talla`) REFERENCES `talla` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `detallecarrito`
--

CREATE TABLE `detallecarrito` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_carrito` int NOT NULL,
  `id_producto_talla` int NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_carrito` (`id_carrito`),
  KEY `id_producto_talla` (`id_producto_talla`),
  CONSTRAINT `detallecarrito_ibfk_1` FOREIGN KEY (`id_carrito`) REFERENCES `carrito` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detallecarrito_ibfk_2` FOREIGN KEY (`id_producto_talla`) REFERENCES `producto_talla` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `fecha_pedido` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` enum('pendiente','procesando','enviado','entregado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `id_producto_talla` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  KEY `id_producto_talla` (`id_producto_talla`),
  CONSTRAINT `detallepedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detallepedido_ibfk_2` FOREIGN KEY (`id_producto_talla`) REFERENCES `producto_talla` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `direccionenvio`
--

CREATE TABLE `direccionenvio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `calle` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `colonia` varchar(100) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cp` varchar(10) DEFAULT NULL,
  `referencias` text,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `direccionenvio_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pedido` int NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_pago` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado` varchar(50) DEFAULT 'procesando',
  PRIMARY KEY (`id`),
  KEY `id_pedido` (`id_pedido`),
  CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Insertar usuario administrador por defecto
--

INSERT INTO `usuario` (`nombre`, `correo`, `contraseña`, `rol`) VALUES 
('admin', 'admin@admin', 'admin123', 'admin')
ON DUPLICATE KEY UPDATE nombre = 'admin';

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 