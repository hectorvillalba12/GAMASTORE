CREATE DATABASE  IF NOT EXISTS `modelorelacional2` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `modelorelacional2`;
-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: modelorelacional2
-- ------------------------------------------------------
-- Server version	9.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `auditoria`
--

DROP TABLE IF EXISTS `auditoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `auditoria` (
  `id_auditoria` int NOT NULL AUTO_INCREMENT,
  `tabla` varchar(50) NOT NULL,
  `registro_id` int NOT NULL,
  `accion` enum('crear','editar','baja','reactivar') NOT NULL,
  `datos_anteriores` text,
  `datos_nuevos` text,
  `usuario_id_usuario` int DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`),
  KEY `usuario_id_usuario` (`usuario_id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'producto',38,'editar','{\"id_producto\":38,\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":5000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"38\",\"nombre\":\"topper\",\"descripcion\":\"\",\"tipo\":\"correr\",\"precio\":\"30000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"16\"}',6,'2026-08-31 17:20:38');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `tipodezapatilla` varchar(45) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (14,'running'),(15,'basket'),(16,'futbol'),(17,'casual');
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `preferencias` varchar(45) DEFAULT NULL,
  `numero_ficha` int DEFAULT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'activo',
  `persona_idpersona` int NOT NULL,
  `email_token` varchar(64) DEFAULT NULL,
  `email_verificado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cliente`),
  KEY `fk_cliente_persona1_idx` (`persona_idpersona`),
  CONSTRAINT `fk_cliente_persona1` FOREIGN KEY (`persona_idpersona`) REFERENCES `persona` (`id_persona`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (19,'2026-05-22 21:41:09',NULL,NULL,'activo',21,NULL,0),(20,'2026-05-22 21:44:57',NULL,NULL,'activo',22,NULL,0),(21,'2026-06-06 20:12:24',NULL,NULL,'activo',23,NULL,0),(22,'2026-06-15 01:57:17',NULL,NULL,'activo',24,NULL,0);
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_has_promocion`
--

DROP TABLE IF EXISTS `cliente_has_promocion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_has_promocion` (
  `cliente_id_cliente` int NOT NULL,
  `promocion_id_promocion` int NOT NULL,
  PRIMARY KEY (`cliente_id_cliente`,`promocion_id_promocion`),
  KEY `fk_cliente_has_promocion_promocion1_idx` (`promocion_id_promocion`),
  KEY `fk_cliente_has_promocion_cliente1_idx` (`cliente_id_cliente`),
  CONSTRAINT `fk_cliente_has_promocion_cliente1` FOREIGN KEY (`cliente_id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_cliente_has_promocion_promocion1` FOREIGN KEY (`promocion_id_promocion`) REFERENCES `promocion` (`id_promocion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_has_promocion`
--

LOCK TABLES `cliente_has_promocion` WRITE;
/*!40000 ALTER TABLE `cliente_has_promocion` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_has_promocion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `color` (
  `id_color` int NOT NULL AUTO_INCREMENT,
  `colores_disponibles` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_color`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (13,'rojo','rojo'),(14,'azul','azul'),(15,'blanco','blanco'),(16,'negro','negro');
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color_has_producto`
--

DROP TABLE IF EXISTS `color_has_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `color_has_producto` (
  `color_id_color` int NOT NULL,
  `producto_id_producto` int NOT NULL,
  PRIMARY KEY (`color_id_color`,`producto_id_producto`),
  KEY `fk_color_has_producto_producto1_idx` (`producto_id_producto`),
  KEY `fk_color_has_producto_color1_idx` (`color_id_color`),
  CONSTRAINT `fk_color_has_producto_color1` FOREIGN KEY (`color_id_color`) REFERENCES `color` (`id_color`),
  CONSTRAINT `fk_color_has_producto_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_has_producto`
--

LOCK TABLES `color_has_producto` WRITE;
/*!40000 ALTER TABLE `color_has_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `color_has_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color_has_producto1`
--

DROP TABLE IF EXISTS `color_has_producto1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `color_has_producto1` (
  `color_id_color` int NOT NULL,
  `producto_id_producto` int NOT NULL,
  PRIMARY KEY (`color_id_color`,`producto_id_producto`),
  KEY `fk_color_has_producto1_producto1_idx` (`producto_id_producto`),
  KEY `fk_color_has_producto1_color1_idx` (`color_id_color`),
  CONSTRAINT `fk_color_has_producto1_color1` FOREIGN KEY (`color_id_color`) REFERENCES `color` (`id_color`),
  CONSTRAINT `fk_color_has_producto1_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color_has_producto1`
--

LOCK TABLES `color_has_producto1` WRITE;
/*!40000 ALTER TABLE `color_has_producto1` DISABLE KEYS */;
/*!40000 ALTER TABLE `color_has_producto1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_ordenes`
--

DROP TABLE IF EXISTS `detalle_ordenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_ordenes` (
  `ordenes_idordenes` int NOT NULL,
  `productos_idproducto` int NOT NULL,
  `descripcion` varchar(45) NOT NULL,
  `cantidad` int NOT NULL,
  `precio_costo_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`ordenes_idordenes`,`productos_idproducto`),
  KEY `fk_ordenes_has_productos_productos1_idx` (`productos_idproducto`),
  KEY `fk_ordenes_has_productos_ordenes1_idx` (`ordenes_idordenes`),
  CONSTRAINT `fk_ordenes_has_productos_ordenes1` FOREIGN KEY (`ordenes_idordenes`) REFERENCES `ordenes` (`id_ordenes`),
  CONSTRAINT `fk_ordenes_has_productos_productos1` FOREIGN KEY (`productos_idproducto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_ordenes`
--

LOCK TABLES `detalle_ordenes` WRITE;
/*!40000 ALTER TABLE `detalle_ordenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_ordenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id_detalleventa` int NOT NULL AUTO_INCREMENT,
  `IVA` int DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_producto` int DEFAULT NULL,
  `descuento` decimal(10,2) DEFAULT '0.00',
  `total_venta` int DEFAULT NULL,
  `precio_final` int DEFAULT NULL,
  `venta_idventa` int NOT NULL,
  `productos_idproducto` int NOT NULL,
  `promocion_id_promocion` int DEFAULT NULL,
  PRIMARY KEY (`id_detalleventa`),
  KEY `fk_productos_has_venta_venta1_idx` (`venta_idventa`),
  KEY `fk_productos_has_venta_productos1_idx` (`productos_idproducto`),
  KEY `fk_detalle_venta_promocion` (`promocion_id_promocion`),
  CONSTRAINT `fk_detalle_venta_promocion` FOREIGN KEY (`promocion_id_promocion`) REFERENCES `promocion` (`id_promocion`),
  CONSTRAINT `fk_productos_has_venta_productos1` FOREIGN KEY (`productos_idproducto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_productos_has_venta_venta1` FOREIGN KEY (`venta_idventa`) REFERENCES `venta` (`id_venta`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (3,0,'Producto #37 x1',1,3333,0.00,3333,4033,16,37,NULL),(4,0,'topper (Stock: 6.00) x1',1,5000,0.00,5000,6050,17,38,NULL),(5,0,'topper (Stock: 5.00) x2',2,5000,0.00,10000,12100,18,38,NULL);
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empleado`
--

DROP TABLE IF EXISTS `empleado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empleado` (
  `id_empleado` int NOT NULL AUTO_INCREMENT,
  `cargo` varchar(45) DEFAULT NULL,
  `fecha_ingreso` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_salida` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `salario` varchar(45) DEFAULT NULL,
  `persona_id_persona` int NOT NULL,
  `usuario_id_usuario` int NOT NULL,
  PRIMARY KEY (`id_empleado`),
  KEY `fk_empleado_persona1_idx` (`persona_id_persona`),
  KEY `fk_empleado_usuario1_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_empleado_persona1` FOREIGN KEY (`persona_id_persona`) REFERENCES `persona` (`id_persona`),
  CONSTRAINT `fk_empleado_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empleado`
--

LOCK TABLES `empleado` WRITE;
/*!40000 ALTER TABLE `empleado` DISABLE KEYS */;
/*!40000 ALTER TABLE `empleado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario` (
  `id_inventario` int NOT NULL AUTO_INCREMENT,
  `stock_actual` decimal(10,2) DEFAULT NULL,
  `ubicacion` varchar(45) DEFAULT NULL,
  `stock_maximo` int DEFAULT NULL,
  `stock_minimo` int DEFAULT NULL,
  `producto_id_producto` int NOT NULL,
  PRIMARY KEY (`id_inventario`),
  KEY `fk_inventario_producto1_idx` (`producto_id_producto`),
  CONSTRAINT `fk_inventario_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (6,6.00,'Deposito C - Estante 1',10,4,37),(7,3.00,'Deposito B  - Estante 2',9,4,38);
/*!40000 ALTER TABLE `inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca`
--

DROP TABLE IF EXISTS `marca`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca` (
  `id_marca` int NOT NULL AUTO_INCREMENT,
  `marcas_disponibles` varchar(45) DEFAULT NULL,
  `tipodezapatilla` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_marca`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (12,'nike','nike'),(13,'diadora','diadora'),(15,'adidas','libre'),(16,'nike','sss');
/*!40000 ALTER TABLE `marca` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marca_has_producto`
--

DROP TABLE IF EXISTS `marca_has_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marca_has_producto` (
  `marca_id_marca` int NOT NULL,
  `producto_id_producto` int NOT NULL,
  PRIMARY KEY (`marca_id_marca`,`producto_id_producto`),
  KEY `fk_marca_has_producto_producto1_idx` (`producto_id_producto`),
  KEY `fk_marca_has_producto_marca1_idx` (`marca_id_marca`),
  CONSTRAINT `fk_marca_has_producto_marca1` FOREIGN KEY (`marca_id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `fk_marca_has_producto_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca_has_producto`
--

LOCK TABLES `marca_has_producto` WRITE;
/*!40000 ALTER TABLE `marca_has_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `marca_has_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimiento_inventario`
--

DROP TABLE IF EXISTS `movimiento_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimiento_inventario` (
  `id_movimiento` int NOT NULL AUTO_INCREMENT,
  `inventario_id_inventario` int NOT NULL,
  `tipo` enum('entrada','salida','ajuste') NOT NULL,
  `cantidad` int NOT NULL,
  `stock_anterior` int NOT NULL,
  `stock_nuevo` int NOT NULL,
  `motivo` varchar(150) DEFAULT NULL,
  `usuario_id_usuario` int DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento`),
  KEY `fk_movimiento_inventario_idx` (`inventario_id_inventario`),
  KEY `fk_movimiento_usuario_idx` (`usuario_id_usuario`),
  CONSTRAINT `fk_movimiento_inventario` FOREIGN KEY (`inventario_id_inventario`) REFERENCES `inventario` (`id_inventario`),
  CONSTRAINT `fk_movimiento_usuario` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_inventario`
--

LOCK TABLES `movimiento_inventario` WRITE;
/*!40000 ALTER TABLE `movimiento_inventario` DISABLE KEYS */;
INSERT INTO `movimiento_inventario` VALUES (1,6,'ajuste',4,0,4,'Ajuste manual desde edición de inventario',6,'2026-08-20 20:57:49'),(2,6,'ajuste',3,3,6,'Ajuste manual desde edición de inventario',6,'2026-08-25 19:48:45'),(3,7,'ajuste',6,0,6,'Ajuste manual desde edición de inventario',6,'2026-08-25 19:52:43'),(4,7,'salida',1,6,5,'Venta #17',6,'2026-08-25 19:53:13'),(5,7,'salida',2,5,3,'Venta #18',6,'2026-08-25 21:07:21');
/*!40000 ALTER TABLE `movimiento_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ordenes`
--

DROP TABLE IF EXISTS `ordenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ordenes` (
  `id_ordenes` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `estado` varchar(45) NOT NULL,
  `proveedor` varchar(45) NOT NULL,
  `productos_solicitados` varchar(45) NOT NULL,
  `cantidad` int NOT NULL,
  PRIMARY KEY (`id_ordenes`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ordenes`
--

LOCK TABLES `ordenes` WRITE;
/*!40000 ALTER TABLE `ordenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ordenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil` (
  `id_perfil` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador','Acceso total al sistema','inactivo'),(2,'Administrador','Acceso total al sistema','inactivo'),(3,'Eduardo','empleado del mes','inactivo'),(4,'Empleado','empleado nuevo','inactivo'),(5,'ariel','profesor','inactivo'),(6,'hector','empleado nuevo','inactivo'),(7,'mbappe','empleado nuevo','activo'),(8,'juan','empleado nuevo','activo'),(9,'milena','empleada','activo'),(10,'fulano2','nuevo empleado','activo');
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_has_modulo`
--

DROP TABLE IF EXISTS `perfil_has_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perfil_has_modulo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `perfil_id` int NOT NULL,
  `modulo` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `perfil_id` (`perfil_id`),
  CONSTRAINT `perfil_has_modulo_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_has_modulo`
--

LOCK TABLES `perfil_has_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_has_modulo` DISABLE KEYS */;
INSERT INTO `perfil_has_modulo` VALUES (1,1,'dashboard'),(2,1,'productos'),(3,1,'clientes'),(4,1,'inventario'),(5,1,'ventas'),(6,1,'dashboard'),(7,1,'productos'),(8,1,'clientes'),(9,1,'inventario'),(10,1,'ventas'),(11,3,'dashboard'),(12,3,'clientes'),(13,4,'productos'),(14,4,'clientes'),(15,5,'dashboard'),(16,5,'productos'),(17,5,'clientes'),(20,1,'usuarios'),(21,1,'perfiles'),(26,6,'dashboard'),(27,6,'productos'),(28,6,'clientes'),(29,6,'inventario'),(30,6,'ventas'),(33,8,'dashboard'),(34,8,'clientes'),(36,7,'dashboard'),(37,7,'clientes'),(45,9,'dashboard'),(46,9,'productos'),(47,9,'clientes'),(48,9,'inventario'),(49,9,'ventas'),(50,10,'clientes'),(51,1,'promociones'),(52,1,'reportes');
/*!40000 ALTER TABLE `perfil_has_modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `persona`
--

DROP TABLE IF EXISTS `persona`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `persona` (
  `id_persona` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `apellido` varchar(45) DEFAULT NULL,
  `dni` int DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `telefono` varchar(45) DEFAULT NULL,
  `direccion` varchar(155) DEFAULT NULL,
  `promocion_id_promocion` int DEFAULT NULL,
  PRIMARY KEY (`id_persona`),
  KEY `fk_persona_promocion1_idx` (`promocion_id_promocion`),
  CONSTRAINT `fk_persona_promocion1` FOREIGN KEY (`promocion_id_promocion`) REFERENCES `promocion` (`id_promocion`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (21,'daniela','villalba',41176704,'danielavillalba379@gmail.com','3704713654',NULL,NULL),(22,'dani','villa',33323225,'danivillalba277@gmail.com','23355663',NULL,NULL),(23,'ariel','montenegro',402332454,'montenegroariel@gmail.com','3704442233',NULL,NULL),(24,'milena','vera',20211223,'milenaverarenaut18@gmail.com','233443344',NULL,NULL);
/*!40000 ALTER TABLE `persona` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `descripcion` text,
  `tipodezapatillas` varchar(45) DEFAULT NULL,
  `precio` int DEFAULT NULL,
  `talle_id_talle` int NOT NULL,
  `marca_id_marca` int NOT NULL,
  `color_id_color` int NOT NULL,
  `categoria_id_categoria` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_producto`),
  KEY `fk_productos_talle1_idx` (`talle_id_talle`),
  KEY `fk_productos_marca1_idx` (`marca_id_marca`),
  KEY `fk_productos_color1_idx` (`color_id_color`),
  KEY `fk_producto_categoria1_idx` (`categoria_id_categoria`),
  CONSTRAINT `fk_producto_categoria1` FOREIGN KEY (`categoria_id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `fk_productos_color1` FOREIGN KEY (`color_id_color`) REFERENCES `color` (`id_color`),
  CONSTRAINT `fk_productos_marca1` FOREIGN KEY (`marca_id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `fk_productos_talle1` FOREIGN KEY (`talle_id_talle`) REFERENCES `talle` (`id_talle`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (37,'airmax','addads','deportiva',3333,15,16,14,14,1),(38,'topper','','correr',30000,16,16,15,16,1);
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto_has_proveedor`
--

DROP TABLE IF EXISTS `producto_has_proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto_has_proveedor` (
  `producto_id_producto` int NOT NULL,
  `proveedor_id_proveedor` int NOT NULL,
  PRIMARY KEY (`producto_id_producto`,`proveedor_id_proveedor`),
  KEY `fk_producto_has_proveedor_proveedor1_idx` (`proveedor_id_proveedor`),
  KEY `fk_producto_has_proveedor_producto1_idx` (`producto_id_producto`),
  CONSTRAINT `fk_producto_has_proveedor_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_producto_has_proveedor_proveedor1` FOREIGN KEY (`proveedor_id_proveedor`) REFERENCES `proveedor` (`id_proveedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto_has_proveedor`
--

LOCK TABLES `producto_has_proveedor` WRITE;
/*!40000 ALTER TABLE `producto_has_proveedor` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto_has_proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos_has_promocion`
--

DROP TABLE IF EXISTS `productos_has_promocion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos_has_promocion` (
  `productos_id_producto` int NOT NULL,
  `promocion_id_promocion` int NOT NULL,
  PRIMARY KEY (`productos_id_producto`,`promocion_id_promocion`),
  KEY `fk_productos_has_promocion_promocion1_idx` (`promocion_id_promocion`),
  KEY `fk_productos_has_promocion_productos1_idx` (`productos_id_producto`),
  CONSTRAINT `fk_productos_has_promocion_productos1` FOREIGN KEY (`productos_id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_productos_has_promocion_promocion1` FOREIGN KEY (`promocion_id_promocion`) REFERENCES `promocion` (`id_promocion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos_has_promocion`
--

LOCK TABLES `productos_has_promocion` WRITE;
/*!40000 ALTER TABLE `productos_has_promocion` DISABLE KEYS */;
INSERT INTO `productos_has_promocion` VALUES (38,6);
/*!40000 ALTER TABLE `productos_has_promocion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promocion`
--

DROP TABLE IF EXISTS `promocion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promocion` (
  `id_promocion` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  `tipo_descuento` enum('porcentaje','monto_fijo') NOT NULL DEFAULT 'porcentaje',
  `descuento_porcentaje` decimal(5,2) DEFAULT NULL,
  `monto_fijo` decimal(10,2) DEFAULT NULL,
  `fecha_inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_fin` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activa` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_promocion`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocion`
--

LOCK TABLES `promocion` WRITE;
/*!40000 ALTER TABLE `promocion` DISABLE KEYS */;
INSERT INTO `promocion` VALUES (6,'verano','promo de ferano','porcentaje',30.00,NULL,'2026-03-31 03:00:00','2026-04-06 03:00:00',1);
/*!40000 ALTER TABLE `promocion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proveedor`
--

DROP TABLE IF EXISTS `proveedor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `proveedor` (
  `id_proveedor` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) DEFAULT NULL,
  `telefono` int DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
/*!40000 ALTER TABLE `proveedor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talle`
--

DROP TABLE IF EXISTS `talle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talle` (
  `id_talle` int NOT NULL AUTO_INCREMENT,
  `talles_disponibles` varchar(45) DEFAULT NULL,
  `descripcion` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id_talle`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talle`
--

LOCK TABLES `talle` WRITE;
/*!40000 ALTER TABLE `talle` DISABLE KEYS */;
INSERT INTO `talle` VALUES (15,'43',''),(16,'45',''),(17,'43',''),(18,'44','');
/*!40000 ALTER TABLE `talle` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `talle_has_producto`
--

DROP TABLE IF EXISTS `talle_has_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `talle_has_producto` (
  `talle_id_talle` int NOT NULL,
  `producto_id_producto` int NOT NULL,
  PRIMARY KEY (`talle_id_talle`,`producto_id_producto`),
  KEY `fk_talle_has_producto_producto1_idx` (`producto_id_producto`),
  KEY `fk_talle_has_producto_talle1_idx` (`talle_id_talle`),
  CONSTRAINT `fk_talle_has_producto_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`),
  CONSTRAINT `fk_talle_has_producto_talle1` FOREIGN KEY (`talle_id_talle`) REFERENCES `talle` (`id_talle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talle_has_producto`
--

LOCK TABLES `talle_has_producto` WRITE;
/*!40000 ALTER TABLE `talle_has_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `talle_has_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `email` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` varchar(45) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expira` varchar(255) DEFAULT NULL,
  `perfil_id` int DEFAULT NULL,
  `estado` varchar(45) NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`id_usuario`),
  KEY `perfil_id` (`perfil_id`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id_perfil`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (6,'villalbahector257@gmail.com','$2y$10$Scg8QlaFNliC4a..Mcpyw.sYZv5abAvEWXZsLECamaMOmAuPjtmZG','admin',NULL,NULL,1,'activo'),(7,'roman@gmail.com','$2y$10$dGymR12vK/ZSlAx.ozzjC.NfEiXpi/qvqLRVUArZcbT3iwGqE/4wO','empleado',NULL,NULL,6,'inactivo'),(8,'riquelme@gmail.com','$2y$10$Bob.rMDR6rEDnFkhYYKmAunlud6Iigbp4xL5A00QYwhE1HLDAa5Qi','admin',NULL,NULL,1,'inactivo'),(9,'hv6282533@gmail.com','$2y$10$mq/tMI4ZzqPjcov5b71JbuWRtsZKLGWYbJ0rm19Pc/AhZPldTyvx6','admin',NULL,NULL,1,'inactivo'),(10,'dev@gmail.com','$2y$10$Z9cC4ihfVuDNRj3MXw.SAe8/efr/wJxRr9QUB9icaZA8iv6AgbONK','admin',NULL,NULL,1,'inactivo'),(11,'daniel@gmail.com','$2y$10$Jp20cnS/ppDcdUjSFtY8vuBOmysyECayTsZJ33iUMfCDkh1EJQhgi','empleado',NULL,NULL,1,'inactivo'),(12,'agustin@gmail.com','$2y$10$yMGHB8U5uxyXtsdgzvI5UeLKFBqusY7WAoiIia49BmiUnTyAxvaTO','admin',NULL,NULL,1,'inactivo'),(13,'ariel@gmail.com','$2y$10$eDQboIYOq290vOp4oPjuCuPZa6iLqksr9Nw9Om6PO8laMTJi19vUi','admin',NULL,NULL,1,'inactivo'),(14,'mbappe@gmail.com','$2y$10$nRrjQpDYsYJXmF4VFBSQzeE17hVfpkk/lV3rLiclFd/ebxAEMK406','empleado',NULL,NULL,7,'inactivo'),(15,'messi@gmail.com','$2y$10$8JH17FgV42Zfd4SxjR/q1OC0.JvQwJQqdsIFWiYRRpakIjxrSqu9y','empleado',NULL,NULL,1,'inactivo'),(16,'merentiel@gmail.com','$2y$10$wq.sxwpfuSUiPL06T4A3VeGdxCj/oTa2uB3f6mGA6CagKlmehiGyu','empleado',NULL,NULL,8,'activo'),(17,'milena@gmail.com','$2y$10$KLYsv1vUC.hWd0nqz1o4P.mOudxKviPQXMBIdDGl.DjxgDfDFXwD6','empleado',NULL,NULL,9,'activo'),(18,'fulano@gmail.com','$2y$10$nGfPTAuEth6MgCAyHFaQyebiLdGw0wra9zlDS4kBtCdTEI2yG.ldG','admin',NULL,NULL,NULL,'inactivo'),(19,'fulano2@gmail.com','$2y$10$hBh0RTyXf4NaQBXabKrFBenMk8izCHUEyR4CxmBvSSXNe3kGABTDq','admin',NULL,NULL,10,'activo'),(20,'pepito@gmail.com','$2y$10$x51Mp302xaaZWt8DplmMl.DdDSHneiph96VjJtmoPUD4HtHHzuaZK','empleado',NULL,NULL,NULL,'activo'),(21,'haland@gmail.com','$2y$10$/x6kxnk2IpNrzvcMWWqGv.fsiiHg28h.gYdOFd4DR1XNvThc7WT8m','empleado',NULL,NULL,NULL,'activo'),(22,'gabi@gmail.com','$2y$10$Yb4fidjDtKdPH/Nd24VK6u5HB5dLQL2pOUYrI7NqG/xC9bRBJLkaO','admin',NULL,NULL,NULL,'activo');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `metodo_de_pago` varchar(45) DEFAULT NULL,
  `cliente_idcliente` int DEFAULT NULL,
  `empleado_id_empleado` int DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `fk_venta_cliente1_idx` (`cliente_idcliente`),
  KEY `fk_venta_empleado1_idx` (`empleado_id_empleado`),
  CONSTRAINT `fk_venta_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_venta_empleado1` FOREIGN KEY (`empleado_id_empleado`) REFERENCES `empleado` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` VALUES (16,'2026-08-20 20:58:12',3333.00,'Tarjeta Crédito',NULL,NULL),(17,'2026-08-25 19:53:13',6050.00,'Efectivo',22,NULL),(18,'2026-08-25 21:07:21',12100.00,'Tarjeta Débito',22,NULL);
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-31 18:34:50
