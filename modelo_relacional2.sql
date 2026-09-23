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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'producto',38,'editar','{\"id_producto\":38,\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":5000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"38\",\"nombre\":\"topper\",\"descripcion\":\"\",\"tipo\":\"correr\",\"precio\":\"30000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"16\"}',6,'2026-08-31 17:20:38'),(2,'producto',39,'crear',NULL,'{\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"16\"}',6,'2026-08-31 21:56:26'),(3,'producto',37,'baja','{\"id_producto\":37,\"nombre\":\"airmax\",\"descripcion\":\"addads\",\"tipodezapatillas\":\"deportiva\",\"precio\":3333,\"talle_id_talle\":15,\"marca_id_marca\":16,\"color_id_color\":14,\"categoria_id_categoria\":14,\"activo\":1}','{\"activo\":0}',6,'2026-08-31 21:56:49'),(4,'producto',40,'crear',NULL,'{\"nombre\":\"diadora\",\"descripcion\":\"msamsmadm\",\"tipodezapatillas\":\"urbana\",\"precio\":\"40000\",\"talle\":\"16\",\"marca\":\"13\",\"color\":\"16\",\"categoria\":\"14\"}',23,'2026-08-31 22:02:56'),(5,'producto',38,'baja','{\"id_producto\":38,\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":30000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":16,\"activo\":1}','{\"activo\":0}',23,'2026-08-31 22:03:44'),(6,'producto',41,'crear',NULL,'{\"nombre\":\"airmax\",\"descripcion\":\"zapatillas para correr\",\"tipodezapatillas\":\"running\",\"precio\":\"70000\",\"talle\":\"18\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"14\"}',6,'2026-09-01 19:34:38'),(7,'producto',42,'crear',NULL,'{\"nombre\":\"adidasmagic\",\"descripcion\":\"zapatillas nuevas\",\"tipodezapatillas\":\"urbana\",\"precio\":\"60000\",\"talle\":\"18\",\"marca\":\"15\",\"color\":\"14\",\"categoria\":\"15\"}',6,'2026-09-01 19:36:01'),(8,'producto',39,'editar','{\"id_producto\":39,\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"39\",\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipo\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"14\",\"categoria\":\"16\"}',6,'2026-09-01 20:40:30'),(9,'producto',43,'crear',NULL,'{\"nombre\":\"nike\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"60000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"13\",\"categoria\":\"14\"}',6,'2026-09-01 20:52:50'),(10,'producto',44,'crear',NULL,'{\"nombre\":\"ojotas nike\",\"descripcion\":\"mmm\",\"tipodezapatillas\":\"playeras\",\"precio\":\"10000\",\"talle\":\"15\",\"marca\":\"12\",\"color\":\"15\",\"categoria\":\"17\"}',6,'2026-09-01 21:14:03'),(11,'producto',40,'baja','{\"id_producto\":40,\"nombre\":\"diadora\",\"descripcion\":\"msamsmadm\",\"tipodezapatillas\":\"urbana\",\"precio\":40000,\"talle_id_talle\":16,\"marca_id_marca\":13,\"color_id_color\":16,\"categoria_id_categoria\":14,\"activo\":1}','{\"activo\":0}',6,'2026-09-04 15:34:22'),(12,'producto',45,'crear',NULL,'{\"nombre\":\"vans\",\"descripcion\":\"\",\"tipodezapatillas\":\"training\",\"precio\":\"50000\",\"talle\":\"21\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"18\"}',6,'2026-09-04 15:36:45'),(13,'producto',46,'crear',NULL,'{\"nombre\":\"vans\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":\"50000\",\"talle\":\"20\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"14\"}',6,'2026-09-04 15:49:09'),(14,'producto',47,'crear',NULL,'{\"nombre\":\"pumas\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"40000\",\"talle\":\"21\",\"marca\":\"17\",\"color\":\"20\",\"categoria\":\"19\"}',6,'2026-09-04 19:27:49'),(15,'producto',48,'crear',NULL,'{\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"urbana\",\"precio\":\"45000\",\"talle\":\"17\",\"marca\":\"20\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 19:37:13'),(16,'producto',49,'crear',NULL,'{\"nombre\":\"nike messi\",\"descripcion\":\"\",\"tipodezapatillas\":\"fubol\",\"precio\":\"30000\",\"talle\":\"20\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"16\"}',6,'2026-09-04 20:30:56'),(17,'producto',50,'crear',NULL,'{\"nombre\":\"air max nike\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"25\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:33:49'),(18,'producto',51,'crear',NULL,'{\"nombre\":\"top700\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"30000\",\"talle\":\"20\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:37:04'),(19,'producto',52,'crear',NULL,'{\"nombre\":\"heelies\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"16\",\"marca\":\"17\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:40:08'),(20,'producto',53,'crear',NULL,'{\"nombre\":\"rebook\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"30000\",\"talle\":\"19\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"19\"}',6,'2026-09-04 20:44:37'),(21,'producto',54,'crear',NULL,'{\"nombre\":\"knu\",\"descripcion\":\"\",\"tipodezapatillas\":\"urbana\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"18\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:47:09'),(22,'producto',55,'crear',NULL,'{\"nombre\":\"maracaibo\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"20\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:48:48'),(23,'producto',56,'crear',NULL,'{\"nombre\":\"air\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"10000\",\"talle\":\"19\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"19\"}',6,'2026-09-04 20:50:36'),(24,'producto',57,'crear',NULL,'{\"nombre\":\"nike pirane\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"26\",\"marca\":\"16\",\"color\":\"17\",\"categoria\":\"17\"}',6,'2026-09-04 20:52:55'),(25,'producto',39,'editar','{\"id_producto\":39,\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":14,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"39\",\"nombre\":\"topper\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipo\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"20\",\"color\":\"14\",\"categoria\":\"16\"}',25,'2026-09-15 13:42:19'),(26,'producto',56,'baja','{\"id_producto\":56,\"nombre\":\"air\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":10000,\"talle_id_talle\":19,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":19,\"activo\":1}','{\"activo\":0}',6,'2026-09-15 19:23:16'),(27,'producto',42,'baja','{\"id_producto\":42,\"nombre\":\"adidasmagic\",\"descripcion\":\"zapatillas nuevas\",\"tipodezapatillas\":\"urbana\",\"precio\":60000,\"talle_id_talle\":18,\"marca_id_marca\":15,\"color_id_color\":14,\"categoria_id_categoria\":15,\"activo\":1}','{\"activo\":0}',6,'2026-09-15 20:15:21');
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (14,'running'),(15,'basket'),(16,'futbol'),(17,'casual'),(18,'training'),(19,'competicion'),(20,'tenis'),(21,'');
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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (19,'2026-05-22 21:41:09',NULL,NULL,'activo',21,NULL,0),(20,'2026-05-22 21:44:57',NULL,NULL,'activo',22,NULL,0),(21,'2026-06-06 20:12:24',NULL,NULL,'activo',23,NULL,0),(22,'2026-06-15 01:57:17',NULL,NULL,'activo',24,NULL,0),(23,'2026-09-02 00:45:38',NULL,NULL,'activo',25,NULL,0),(24,'2026-09-04 18:51:17',NULL,NULL,'activo',26,NULL,0),(25,'2026-09-04 23:01:16',NULL,NULL,'activo',27,NULL,0),(26,'2026-09-04 23:05:58',NULL,NULL,'activo',28,NULL,0),(27,'2026-09-04 23:08:42',NULL,NULL,'activo',29,NULL,0),(28,'2026-09-04 23:11:05',NULL,NULL,'activo',30,NULL,0),(29,'2026-09-04 23:13:22',NULL,NULL,'activo',31,NULL,0),(30,'2026-09-04 23:16:00',NULL,NULL,'activo',32,NULL,0),(31,'2026-09-04 23:18:36',NULL,NULL,'activo',33,NULL,0),(32,'2026-09-04 23:20:53',NULL,NULL,'activo',34,NULL,0),(33,'2026-09-04 23:26:08',NULL,NULL,'activo',35,NULL,0),(34,'2026-09-04 23:26:53',NULL,NULL,'activo',36,NULL,0),(35,'2026-09-04 23:29:59',NULL,NULL,'activo',37,NULL,0),(36,'2026-09-04 23:32:56',NULL,NULL,'activo',38,NULL,0),(37,'2026-09-04 23:35:36',NULL,NULL,'activo',39,NULL,0);
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
INSERT INTO `cliente_has_promocion` VALUES (25,7);
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (13,'rojo','rojo'),(14,'azul','azul'),(15,'blanco','blanco'),(16,'negro','negro'),(17,'lila',NULL),(18,'amarillo',NULL),(19,'verde',NULL),(20,'celeste',NULL),(21,'naranja',NULL);
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
  `descripcion` varchar(255) DEFAULT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (3,0,'Producto #37 x1',1,3333,0.00,3333,4033,16,37,NULL),(4,0,'topper (Stock: 6.00) x1',1,5000,0.00,5000,6050,17,38,NULL),(5,0,'topper (Stock: 5.00) x2',2,5000,0.00,10000,12100,18,38,NULL),(6,0,'nike (Stock: 10.00) x3',3,60000,0.00,180000,217800,19,43,NULL),(7,0,'nike (Stock: 9.00) x5',5,60000,0.00,300000,363000,20,43,NULL),(8,0,'ojotas nike (Stock: 9.00) x5',5,10000,0.00,50000,60500,21,44,NULL),(9,0,'nike (Stock: 17.00) x2',2,60000,0.00,120000,145200,22,43,NULL),(10,0,'ojotas nike (Stock: 14.00) x2',2,10000,0.00,20000,24200,22,44,NULL),(11,0,'ojotas nike (Stock: 12.00) x1',1,10000,0.00,10000,12100,23,44,NULL),(12,0,'nike (Stock: 15.00) x1',1,60000,0.00,60000,72600,24,43,NULL),(13,0,'nike (Stock: 9.00) x1',1,60000,0.00,60000,60000,25,43,NULL),(14,0,'vans (Stock: 9.00) x1',1,50000,0.00,50000,50000,26,45,NULL),(15,0,'ojotas nike (Stock: 11.00) x1',1,10000,0.00,10000,10000,26,44,NULL),(16,0,'nike (Stock: 13.00) x1',1,60000,0.00,60000,60000,26,43,NULL),(17,0,'vans (Stock: 7.00) x1',1,50000,0.00,50000,50000,27,46,NULL),(18,0,'vans (Stock: 6.00) x6',6,50000,0.00,300000,300000,28,46,NULL),(19,0,'nike (Stock: 9.00) x8',8,60000,0.00,480000,480000,28,43,NULL),(20,0,'ojotas nike (Stock: 10.00) x1',1,10000,0.00,10000,10000,29,44,NULL),(21,0,'nike (Stock: 19.00) x2',2,60000,0.00,120000,120000,30,43,NULL),(22,0,'vans (Stock: 8.00) x3',3,50000,0.00,150000,150000,30,45,NULL),(23,0,'ojotas nike (Stock: 9.00) x3',3,10000,0.00,30000,30000,30,44,NULL),(24,0,'vans (Stock: 19.00) x1',1,50000,0.00,50000,50000,31,46,NULL),(25,0,'pumas (Stock: 7.00) x1',1,40000,0.00,40000,40000,32,47,NULL),(26,0,'nike (Stock: 17.00) x2',2,60000,0.00,120000,120000,32,43,NULL),(27,0,'nike (Stock: 15.00) x1',1,60000,0.00,60000,60000,33,43,NULL),(28,0,'pumas (Stock: 6.00) x1',1,40000,0.00,40000,40000,33,47,NULL),(29,0,'vans (Stock: 9.00) x1',1,50000,0.00,50000,50000,34,45,NULL),(30,0,'nike (Stock: 14.00) x2',2,60000,0.00,120000,120000,34,43,NULL),(31,0,'vans (Stock: 18.00) x2',2,50000,0.00,100000,100000,35,46,NULL),(32,0,'pumas (Stock: 5.00) x1',1,40000,0.00,40000,40000,35,47,NULL),(33,0,'topper (Stock: 8.00) x1',1,45000,0.00,45000,45000,36,48,NULL),(34,0,'nike (Stock: 12.00) x1',1,60000,0.00,60000,60000,36,43,NULL),(35,0,'nike (Stock: 11.00) x2',2,60000,0.00,120000,120000,37,43,NULL),(36,0,'nike (Stock: 9.00) x1',1,60000,0.00,60000,60000,38,43,NULL),(37,0,'pumas (Stock: 12.00) x1',1,40000,0.00,40000,40000,38,47,NULL),(38,0,'vans (Stock: 16.00) x1',1,50000,0.00,50000,50000,39,46,NULL),(39,0,'topper (Stock: 7.00) x1',1,45000,0.00,45000,45000,39,48,NULL),(40,0,'nike (Stock: 9.00) x2',2,60000,0.00,120000,120000,40,43,NULL),(41,0,'vans (Stock: 15.00) x1',1,50000,0.00,50000,50000,41,46,NULL),(42,0,'pumas (Stock: 11.00) x1',1,40000,0.00,40000,40000,41,47,NULL),(43,0,'vans (Stock: 14.00) x2',2,50000,0.00,100000,100000,42,46,NULL),(44,0,'nike (Stock: 12.00) x1',1,60000,0.00,60000,60000,42,43,NULL),(45,0,'nike messi (Stock: 10.00) x1',1,30000,0.00,30000,30000,43,49,NULL),(46,0,'air max nike (Stock: 10.00) x2',2,20000,0.00,40000,40000,44,50,NULL),(47,0,'top700 (Stock: 10.00) x5',5,30000,0.00,150000,150000,45,51,NULL),(48,0,'heelies (Stock: 10.00) x6',6,20000,0.00,120000,120000,46,52,NULL),(49,0,'rebook (Stock: 10.00) x3',3,30000,0.00,90000,90000,47,53,NULL),(50,0,'knu (Stock: 10.00) x5',5,20000,0.00,100000,100000,48,54,NULL),(51,0,'maracaibo (Stock: 10.00) x3',3,20000,0.00,60000,60000,49,55,NULL),(52,0,'air (Stock: 10.00) x2',2,10000,0.00,20000,20000,50,56,NULL),(53,0,'nike pirane (Stock: 10.00) x10',10,20000,0.00,200000,200000,51,57,NULL),(54,0,'air (Stock: 8.00) x1',1,10000,0.00,10000,10000,52,56,NULL),(55,0,'air max nike (Stock: 8.00) x2',2,20000,0.00,40000,40000,53,50,NULL),(56,0,'nike pirane (Stock: 10.00) x1',1,20000,0.00,20000,20000,56,57,NULL),(57,0,'nike messi (Stock: 9.00) x1 — Promo: primavera-verano',1,30000,20000.00,30000,10000,59,49,8),(58,0,'nike pirane x1 — Promo: primavera-verano',1,20000,20000.00,20000,0,65,57,8),(59,0,'nike messi x1 — Promo: primavera-verano',1,30000,20000.00,30000,10000,66,49,8);
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (6,6.00,'Deposito C - Estante 1',10,4,37),(7,11.00,'Deposito B  - Estante 2',12,4,38),(12,11.00,'Deposito A - Estante 2',20,8,43),(13,9.00,'Deposito A - Estante 2',12,5,43),(15,16.00,'Deposito B  - Estante 2',20,8,44),(17,8.00,'Deposito A - Estante 2',10,6,45),(18,12.00,'Deposito B  - Estante 2',10,6,46),(19,10.00,'Deposito A - Estante 2',8,4,47),(20,6.00,'',0,0,48),(21,7.00,'Deposito A - Estante 2',11,6,49),(22,20.00,'Deposito B  - Estante 2',11,6,50),(23,5.00,'Deposito B  - Estante 2',11,6,51),(24,4.00,'Deposito A - Estante 2',10,6,52),(25,7.00,'Deposito B  - Estante 2',8,6,53),(26,5.00,'Deposito C - Estante 1',10,7,54),(27,7.00,'Deposito A - Estante 2',10,7,55),(28,8.00,'Deposito B  - Estante 2',10,7,56),(29,8.00,'Deposito A - Estante 2',11,6,57);
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (12,'nike','nike'),(13,'diadora','diadora'),(15,'adidas','libre'),(16,'nike','sss'),(17,'pumas',NULL),(18,'vans',NULL),(19,'crocs',NULL),(20,'topper',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_inventario`
--

LOCK TABLES `movimiento_inventario` WRITE;
/*!40000 ALTER TABLE `movimiento_inventario` DISABLE KEYS */;
INSERT INTO `movimiento_inventario` VALUES (1,6,'ajuste',4,0,4,'Ajuste manual desde edición de inventario',6,'2026-08-20 20:57:49'),(2,6,'ajuste',3,3,6,'Ajuste manual desde edición de inventario',6,'2026-08-25 19:48:45'),(3,7,'ajuste',6,0,6,'Ajuste manual desde edición de inventario',6,'2026-08-25 19:52:43'),(4,7,'salida',1,6,5,'Venta #17',6,'2026-08-25 19:53:13'),(5,7,'salida',2,5,3,'Venta #18',6,'2026-08-25 21:07:21'),(6,13,'entrada',9,0,9,'Carga inicial de inventario',6,'2026-09-01 20:53:15'),(7,12,'entrada',10,0,10,'proveedor',6,'2026-09-01 20:59:04'),(8,12,'salida',3,10,7,'Venta #19',6,'2026-09-01 21:00:34'),(9,15,'entrada',5,0,5,'Carga inicial de inventario',6,'2026-09-01 21:15:01'),(10,15,'ajuste',7,5,12,'Ajuste manual desde edición de inventario',6,'2026-09-01 21:15:33'),(11,15,'ajuste',3,12,9,'Ajuste manual desde edición de inventario',6,'2026-09-01 21:39:00'),(12,7,'entrada',8,3,11,'proveedor',6,'2026-09-01 21:39:42'),(13,12,'salida',5,7,2,'Venta #20',6,'2026-09-01 21:47:20'),(14,15,'salida',5,9,4,'Venta #21',6,'2026-09-01 21:49:42'),(15,15,'entrada',10,4,14,'proveedor',6,'2026-09-01 21:52:58'),(16,12,'entrada',15,2,17,'proveedor',6,'2026-09-01 21:53:31'),(17,12,'salida',2,17,15,'Venta #22',6,'2026-09-03 21:13:18'),(18,15,'salida',2,14,12,'Venta #22',6,'2026-09-03 21:13:18'),(19,15,'salida',1,12,11,'Venta #23',6,'2026-09-04 15:18:19'),(20,12,'salida',1,15,14,'Venta #24',6,'2026-09-04 15:19:50'),(21,12,'salida',1,14,13,'Venta #25',6,'2026-09-04 15:23:44'),(22,17,'entrada',10,0,10,'Carga inicial de inventario',6,'2026-09-04 15:37:29'),(23,17,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,'2026-09-04 15:38:13'),(24,17,'salida',1,9,8,'Venta #26',6,'2026-09-04 15:39:08'),(25,15,'salida',1,11,10,'Venta #26',6,'2026-09-04 15:39:08'),(26,12,'salida',1,13,12,'Venta #26',6,'2026-09-04 15:39:08'),(27,18,'ajuste',7,0,7,'Ajuste manual desde edición de inventario',6,'2026-09-04 15:49:37'),(28,18,'salida',1,7,6,'Venta #27',6,'2026-09-04 15:50:04'),(29,18,'salida',6,6,0,'Venta #28',6,'2026-09-04 16:06:37'),(30,12,'salida',8,12,4,'Venta #28',6,'2026-09-04 16:06:37'),(31,12,'entrada',20,4,24,'proveedor',6,'2026-09-04 19:28:06'),(32,12,'ajuste',5,24,19,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:28:18'),(33,19,'ajuste',10,0,10,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:28:41'),(34,19,'ajuste',10,10,0,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:28:57'),(35,19,'entrada',10,0,10,'provedor',6,'2026-09-04 19:29:27'),(36,18,'entrada',10,0,10,'proveedor',6,'2026-09-04 19:30:02'),(37,19,'ajuste',3,10,7,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:30:20'),(38,18,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:31:02'),(39,15,'salida',1,10,9,'Venta #29',6,'2026-09-04 19:38:28'),(40,12,'salida',2,19,17,'Venta #30',6,'2026-09-04 19:39:26'),(41,17,'salida',3,8,5,'Venta #30',6,'2026-09-04 19:39:26'),(42,15,'salida',3,9,6,'Venta #30',6,'2026-09-04 19:39:26'),(43,15,'entrada',10,6,16,'proveedor',6,'2026-09-04 19:41:02'),(44,20,'entrada',8,0,8,'proveedor',6,'2026-09-04 19:41:19'),(45,17,'entrada',5,5,10,'proveedor',6,'2026-09-04 19:41:35'),(46,17,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,'2026-09-04 19:41:44'),(47,18,'entrada',10,9,19,'proveedor',6,'2026-09-04 19:43:40'),(48,18,'salida',1,19,18,'Venta #31',6,'2026-09-04 19:44:07'),(49,19,'salida',1,7,6,'Venta #32',6,'2026-09-04 20:02:14'),(50,12,'salida',2,17,15,'Venta #32',6,'2026-09-04 20:02:14'),(51,12,'salida',1,15,14,'Venta #33',6,'2026-09-04 20:06:45'),(52,19,'salida',1,6,5,'Venta #33',6,'2026-09-04 20:06:45'),(53,17,'salida',1,9,8,'Venta #34',6,'2026-09-04 20:09:20'),(54,12,'salida',2,14,12,'Venta #34',6,'2026-09-04 20:09:20'),(55,18,'salida',2,18,16,'Venta #35',6,'2026-09-04 20:11:45'),(56,19,'salida',1,5,4,'Venta #35',6,'2026-09-04 20:11:45'),(57,20,'salida',1,8,7,'Venta #36',6,'2026-09-04 20:12:21'),(58,12,'salida',1,12,11,'Venta #36',6,'2026-09-04 20:12:21'),(59,12,'salida',2,11,9,'Venta #37',6,'2026-09-04 20:13:55'),(60,19,'entrada',8,4,12,'provedor',6,'2026-09-04 20:14:17'),(61,12,'salida',1,9,8,'Venta #38',6,'2026-09-04 20:16:40'),(62,19,'salida',1,12,11,'Venta #38',6,'2026-09-04 20:16:40'),(63,18,'salida',1,16,15,'Venta #39',6,'2026-09-04 20:19:38'),(64,20,'salida',1,7,6,'Venta #39',6,'2026-09-04 20:19:38'),(65,12,'salida',2,8,6,'Venta #40',6,'2026-09-04 20:21:51'),(66,12,'entrada',6,6,12,'proveedor',6,'2026-09-04 20:23:29'),(67,18,'salida',1,15,14,'Venta #41',6,'2026-09-04 20:27:29'),(68,19,'salida',1,11,10,'Venta #41',6,'2026-09-04 20:27:29'),(69,18,'salida',2,14,12,'Venta #42',6,'2026-09-04 20:28:14'),(70,12,'salida',1,12,11,'Venta #42',6,'2026-09-04 20:28:14'),(71,21,'entrada',10,0,10,'provedor',6,'2026-09-04 20:31:11'),(72,21,'salida',1,10,9,'Venta #43',6,'2026-09-04 20:31:44'),(73,22,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:34:03'),(74,22,'salida',2,10,8,'Venta #44',6,'2026-09-04 20:34:36'),(75,23,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:37:18'),(76,23,'salida',5,10,5,'Venta #45',6,'2026-09-04 20:37:58'),(77,24,'entrada',10,0,10,'provedor',6,'2026-09-04 20:40:25'),(78,24,'salida',6,10,4,'Venta #46',6,'2026-09-04 20:41:29'),(79,25,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:44:55'),(80,25,'salida',3,10,7,'Venta #47',6,'2026-09-04 20:45:46'),(81,26,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:47:22'),(82,26,'salida',5,10,5,'Venta #48',6,'2026-09-04 20:48:02'),(83,27,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:49:04'),(84,27,'salida',3,10,7,'Venta #49',6,'2026-09-04 20:49:43'),(85,28,'entrada',10,0,10,'proveedor',6,'2026-09-04 20:50:51'),(86,28,'salida',2,10,8,'Venta #50',6,'2026-09-04 20:51:29'),(87,29,'entrada',10,0,10,'provedor',6,'2026-09-04 20:53:11'),(88,29,'salida',10,10,0,'Venta #51',6,'2026-09-04 20:54:08'),(89,29,'entrada',10,0,10,'Cancelación venta #51',6,'2026-09-12 15:37:57'),(90,28,'salida',1,8,7,'Venta #52',6,'2026-09-12 20:34:11'),(91,28,'entrada',1,7,8,'Cancelación venta #52',6,'2026-09-12 20:34:17'),(92,22,'salida',2,8,6,'Venta #53',6,'2026-09-15 19:47:15'),(93,22,'entrada',12,6,18,'provedor',6,'2026-09-15 20:17:50'),(94,22,'entrada',2,18,20,'Cancelación venta #53',6,'2026-09-18 21:43:45'),(95,29,'salida',1,10,9,'Venta #56',6,'2026-09-21 22:49:33'),(96,21,'salida',1,9,8,'Venta #59',6,'2026-09-22 15:30:35'),(97,29,'salida',1,9,8,'Venta #65',6,'2026-09-22 16:13:58'),(98,21,'salida',1,8,7,'Venta #66',6,'2026-09-22 16:15:51');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador','Acceso total al sistema','inactivo'),(2,'Administrador','Acceso total al sistema','inactivo'),(3,'Eduardo','empleado del mes','inactivo'),(4,'Empleado','empleado nuevo','inactivo'),(5,'ariel','profesor','inactivo'),(6,'hector','empleado nuevo','inactivo'),(7,'mbappe','empleado nuevo','activo'),(8,'juan','empleado nuevo','activo'),(9,'milena','empleada','activo'),(10,'fulano2','nuevo empleado','activo'),(11,'pepe','empleado del mes','activo'),(12,'falcioni','nuevo empleado','activo'),(13,'patricio','nuevo empleado','activo'),(14,'vasco','nuevo administrador','activo');
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
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_has_modulo`
--

LOCK TABLES `perfil_has_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_has_modulo` DISABLE KEYS */;
INSERT INTO `perfil_has_modulo` VALUES (1,1,'dashboard'),(2,1,'productos'),(3,1,'clientes'),(4,1,'inventario'),(5,1,'ventas'),(6,1,'dashboard'),(7,1,'productos'),(8,1,'clientes'),(9,1,'inventario'),(10,1,'ventas'),(11,3,'dashboard'),(12,3,'clientes'),(13,4,'productos'),(14,4,'clientes'),(15,5,'dashboard'),(16,5,'productos'),(17,5,'clientes'),(20,1,'usuarios'),(21,1,'perfiles'),(26,6,'dashboard'),(27,6,'productos'),(28,6,'clientes'),(29,6,'inventario'),(30,6,'ventas'),(33,8,'dashboard'),(34,8,'clientes'),(36,7,'dashboard'),(37,7,'clientes'),(45,9,'dashboard'),(46,9,'productos'),(47,9,'clientes'),(48,9,'inventario'),(49,9,'ventas'),(50,10,'clientes'),(51,1,'promociones'),(52,1,'reportes'),(53,11,'dashboard'),(54,11,'productos'),(55,11,'clientes'),(56,11,'inventario'),(57,11,'ventas'),(58,1,'auditoria'),(60,12,'dashboard'),(61,12,'productos'),(62,12,'clientes'),(63,12,'inventario'),(64,12,'ventas'),(65,12,'promociones'),(66,12,'reportes'),(67,13,'clientes'),(68,13,'inventario'),(69,14,'dashboard'),(70,14,'productos'),(71,14,'clientes'),(72,14,'inventario'),(73,14,'ventas');
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (21,'daniela','villalba',41176704,'danielavillalba379@gmail.com','3704713654',NULL,NULL),(22,'dani','villa',33323225,'danivillalba277@gmail.com','23355663',NULL,NULL),(23,'ariel','montenegro',402332454,'montenegroariel@gmail.com','3704442233',NULL,NULL),(24,'milena','vera',20211223,'milenaverarenaut18@gmail.com','233443344',NULL,NULL),(25,'gabriela','vera',30455932,'gabrielabetianavera@gmail.com','3704553222',NULL,NULL),(26,'Hector','Villalba',46154857,'villalbahector257@gmail.com','3704219257',NULL,NULL),(27,'alan','ramirez',46394081,'sebalr4m@gmail.com','3704875949',NULL,NULL),(28,'alexander','alarcon',42037330,'alexanderalarcon949@gmail.com','3718463858',NULL,NULL),(29,'facundo','esquivel',44982636,'facundoesquivel03@gmail.com','3704245651',NULL,NULL),(30,'santiago','mora',41415458,'scdm0407@gmail.com','3704784457',NULL,NULL),(31,'gonzalo','gauna',45901768,'gg10exequiel@gmail.com','3705046143',NULL,NULL),(32,'lujan','benitez',45903304,'lub899176@gmail.com','3704819925',NULL,NULL),(33,'marcos','kayser',46321944,'marquitosk05@gmail.com','3704526038',NULL,NULL),(34,'patricio','sosa',45899843,'sosapatricio2025@gmail.com','370506554',NULL,NULL),(35,'erick','britez',45901294,'erikbrite@gmail.com','3704564622',NULL,NULL),(36,'tobias','soto',4597103,'sototobias855@gmail.com','3704048734',NULL,NULL),(37,'miguel','romero',43329003,'miguelangelromero2o1553@gmail.com','3704544014',NULL,NULL),(38,'lucia','davis',46388971,'lucydavis144@gmail.com','3704960038',NULL,NULL),(39,'tobias','almiron',46065755,'tobiias398@gmail.com','3704001122',NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (37,'airmax','addads','deportiva',3333,15,16,14,14,0),(38,'topper','','correr',30000,16,16,15,16,0),(39,'topper','lhkgjhfgjjhjg','deportiva',50000,16,20,14,16,1),(40,'diadora','msamsmadm','urbana',40000,16,13,16,14,0),(41,'airmax','zapatillas para correr','running',70000,18,16,16,14,1),(42,'adidasmagic','zapatillas nuevas','urbana',60000,18,15,14,15,0),(43,'nike','','casual',60000,16,16,13,14,1),(44,'ojotas nike','mmm','playeras',10000,15,12,15,17,1),(45,'vans','','training',50000,21,18,15,18,1),(46,'vans','','correr',50000,20,18,15,14,1),(47,'pumas','','deportiva',40000,21,17,20,19,1),(48,'topper','','urbana',45000,17,20,16,17,1),(49,'nike messi','','fubol',30000,20,16,16,16,1),(50,'air max nike','','casual',20000,25,16,16,17,1),(51,'top700','','casual',30000,20,16,16,17,1),(52,'heelies','','casual',20000,16,17,16,17,1),(53,'rebook','','deportiva',30000,19,18,15,19,1),(54,'knu','','urbana',20000,17,18,16,17,1),(55,'maracaibo','','casual',20000,17,20,16,17,1),(56,'air','','deportiva',10000,19,16,15,19,0),(57,'nike pirane','','casual',20000,26,16,17,17,1);
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
INSERT INTO `productos_has_promocion` VALUES (38,6),(47,7),(53,7),(56,7),(39,8),(49,8),(57,8);
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocion`
--

LOCK TABLES `promocion` WRITE;
/*!40000 ALTER TABLE `promocion` DISABLE KEYS */;
INSERT INTO `promocion` VALUES (6,'verano','promo de ferano','porcentaje',30.00,NULL,'2026-03-31 03:00:00','2026-04-06 03:00:00',0),(7,'primavera','tempara de flores','porcentaje',35.00,NULL,'2026-09-13 03:00:00','2026-09-23 03:00:00',1),(8,'primavera-verano','flores','monto_fijo',NULL,20000.00,'2026-09-14 03:00:00','2026-10-01 03:00:00',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talle`
--

LOCK TABLES `talle` WRITE;
/*!40000 ALTER TABLE `talle` DISABLE KEYS */;
INSERT INTO `talle` VALUES (15,'43',''),(16,'45',''),(17,'43',''),(18,'44',''),(19,'42',NULL),(20,'41',NULL),(21,'40',NULL),(22,'39',NULL),(23,'38',NULL),(24,'37',NULL),(25,'36',NULL),(26,'35',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (6,'villalbahector257@gmail.com','$2y$10$TmMINXKhWPEKharI/j5rOOzOUsTgGzf0VBq/Vi0mt4YHX.ciz3rEm','admin',NULL,NULL,1,'activo'),(7,'roman@gmail.com','$2y$10$dGymR12vK/ZSlAx.ozzjC.NfEiXpi/qvqLRVUArZcbT3iwGqE/4wO','empleado',NULL,NULL,6,'inactivo'),(8,'riquelme@gmail.com','$2y$10$Bob.rMDR6rEDnFkhYYKmAunlud6Iigbp4xL5A00QYwhE1HLDAa5Qi','admin',NULL,NULL,1,'inactivo'),(9,'hv6282533@gmail.com','$2y$10$mq/tMI4ZzqPjcov5b71JbuWRtsZKLGWYbJ0rm19Pc/AhZPldTyvx6','admin',NULL,NULL,1,'inactivo'),(10,'dev@gmail.com','$2y$10$Z9cC4ihfVuDNRj3MXw.SAe8/efr/wJxRr9QUB9icaZA8iv6AgbONK','admin',NULL,NULL,1,'inactivo'),(11,'daniel@gmail.com','$2y$10$Jp20cnS/ppDcdUjSFtY8vuBOmysyECayTsZJ33iUMfCDkh1EJQhgi','empleado',NULL,NULL,1,'inactivo'),(12,'agustin@gmail.com','$2y$10$yMGHB8U5uxyXtsdgzvI5UeLKFBqusY7WAoiIia49BmiUnTyAxvaTO','admin',NULL,NULL,1,'inactivo'),(13,'ariel@gmail.com','$2y$10$eDQboIYOq290vOp4oPjuCuPZa6iLqksr9Nw9Om6PO8laMTJi19vUi','admin',NULL,NULL,1,'inactivo'),(14,'mbappe@gmail.com','$2y$10$nRrjQpDYsYJXmF4VFBSQzeE17hVfpkk/lV3rLiclFd/ebxAEMK406','empleado',NULL,NULL,7,'inactivo'),(15,'messi@gmail.com','$2y$10$8JH17FgV42Zfd4SxjR/q1OC0.JvQwJQqdsIFWiYRRpakIjxrSqu9y','empleado',NULL,NULL,1,'inactivo'),(16,'merentiel@gmail.com','$2y$10$wq.sxwpfuSUiPL06T4A3VeGdxCj/oTa2uB3f6mGA6CagKlmehiGyu','empleado',NULL,NULL,8,'activo'),(17,'milena@gmail.com','$2y$10$KLYsv1vUC.hWd0nqz1o4P.mOudxKviPQXMBIdDGl.DjxgDfDFXwD6','empleado',NULL,NULL,NULL,'activo'),(18,'fulano@gmail.com','$2y$10$nGfPTAuEth6MgCAyHFaQyebiLdGw0wra9zlDS4kBtCdTEI2yG.ldG','admin',NULL,NULL,NULL,'inactivo'),(19,'fulano2@gmail.com','$2y$10$hBh0RTyXf4NaQBXabKrFBenMk8izCHUEyR4CxmBvSSXNe3kGABTDq','admin',NULL,NULL,10,'inactivo'),(20,'pepito@gmail.com','$2y$10$x51Mp302xaaZWt8DplmMl.DdDSHneiph96VjJtmoPUD4HtHHzuaZK','empleado',NULL,NULL,NULL,'inactivo'),(21,'haland@gmail.com','$2y$10$/x6kxnk2IpNrzvcMWWqGv.fsiiHg28h.gYdOFd4DR1XNvThc7WT8m','empleado',NULL,NULL,NULL,'inactivo'),(22,'gabi@gmail.com','$2y$10$Yb4fidjDtKdPH/Nd24VK6u5HB5dLQL2pOUYrI7NqG/xC9bRBJLkaO','admin',NULL,NULL,NULL,'inactivo'),(23,'pepe@gmail.com','$2y$10$h7Ho196FkQ0rcQ5jDqC9Uu4a/uSjg0HAvloLfGDgzlAmTeWS2j/CS','empleado',NULL,NULL,11,'activo'),(24,'milton@gmail.com','$2y$10$Wv4O3u5hJZ/Ce1WhgOi2XeX3tUOR6Qi.2sVZp1kNvv0ByD.GWUsF2','admin',NULL,NULL,NULL,'activo'),(25,'falcioni@gmail.com','$2y$10$bYBUryhSuweWwmpNA3eXuOFdwmghOzz0e3s15QoBkBlEiJaqj0.kS','empleado',NULL,NULL,12,'activo'),(26,'patricio@gmail.com','$2y$10$PD4PQmt3s6xg.ebnMnBY1ObGnUzkBjaI7nvP.oRBw2z7QHaPrJt1m','empleado',NULL,NULL,13,'activo'),(27,'vasco@gmail.com','$2y$10$FIzt5S/qnVIgp8dOqQLf9OCF5WQk.4e1SfTwDqXIQ6zjhio8jKqDi','admin',NULL,NULL,14,'activo');
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
  `estado` varchar(20) NOT NULL DEFAULT 'completada',
  `cliente_idcliente` int DEFAULT NULL,
  `empleado_id_empleado` int DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `fk_venta_cliente1_idx` (`cliente_idcliente`),
  KEY `fk_venta_empleado1_idx` (`empleado_id_empleado`),
  CONSTRAINT `fk_venta_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_venta_empleado1` FOREIGN KEY (`empleado_id_empleado`) REFERENCES `empleado` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` VALUES (16,'2026-08-20 20:58:12',3333.00,'Tarjeta Crédito','completada',NULL,NULL),(17,'2026-08-25 19:53:13',6050.00,'Efectivo','completada',22,NULL),(18,'2026-08-25 21:07:21',12100.00,'Tarjeta Débito','completada',22,NULL),(19,'2026-09-01 21:00:34',217800.00,'Efectivo','completada',19,NULL),(20,'2026-09-01 21:47:20',363000.00,'Efectivo','completada',23,NULL),(21,'2026-09-01 21:49:42',60500.00,'Efectivo','completada',23,NULL),(22,'2026-09-03 21:13:18',169400.00,'Efectivo','completada',21,NULL),(23,'2026-09-04 15:18:19',12100.00,'Efectivo','completada',23,NULL),(24,'2026-09-04 15:19:50',72600.00,'Efectivo','completada',21,NULL),(25,'2026-09-04 15:23:44',60000.00,'Efectivo','completada',21,NULL),(26,'2026-09-04 15:39:08',120000.00,'Transferencia','completada',22,NULL),(27,'2026-09-04 15:50:03',50000.00,'Transferencia','completada',19,NULL),(28,'2026-09-04 16:06:37',780000.00,'Efectivo','completada',24,NULL),(29,'2026-09-04 19:38:28',10000.00,'Efectivo','completada',24,NULL),(30,'2026-09-04 19:39:26',300000.00,'Tarjeta Crédito','completada',19,NULL),(31,'2026-09-04 19:44:07',50000.00,'Efectivo','completada',21,NULL),(32,'2026-09-04 20:02:14',160000.00,'Efectivo','completada',25,NULL),(33,'2026-09-04 20:06:45',100000.00,'Transferencia','completada',26,NULL),(34,'2026-09-04 20:09:20',170000.00,'Transferencia','completada',27,NULL),(35,'2026-09-04 20:11:45',140000.00,'Transferencia','completada',28,NULL),(36,'2026-09-04 20:12:21',105000.00,'Efectivo','completada',28,NULL),(37,'2026-09-04 20:13:55',120000.00,'Efectivo','completada',29,NULL),(38,'2026-09-04 20:16:40',100000.00,'Transferencia','completada',30,NULL),(39,'2026-09-04 20:19:38',95000.00,'Efectivo','completada',31,NULL),(40,'2026-09-04 20:21:51',120000.00,'Transferencia','completada',32,NULL),(41,'2026-09-04 20:27:29',90000.00,'Tarjeta Débito','completada',33,NULL),(42,'2026-09-04 20:28:14',160000.00,'Tarjeta Débito','completada',34,NULL),(43,'2026-09-04 20:31:44',30000.00,'Transferencia','completada',35,NULL),(44,'2026-09-04 20:34:36',40000.00,'Efectivo','completada',36,NULL),(45,'2026-09-04 20:37:58',150000.00,'Transferencia','completada',37,NULL),(46,'2026-09-04 20:41:29',120000.00,'Transferencia','completada',25,NULL),(47,'2026-09-04 20:45:46',90000.00,'Transferencia','completada',26,NULL),(48,'2026-09-04 20:48:02',100000.00,'Efectivo','completada',27,NULL),(49,'2026-09-04 20:49:43',60000.00,'Efectivo','completada',28,NULL),(50,'2026-09-04 20:51:29',20000.00,'Transferencia','completada',29,NULL),(51,'2026-09-04 20:54:08',200000.00,'Tarjeta Crédito','cancelada',31,NULL),(52,'2026-09-12 20:34:11',10000.00,'Efectivo','cancelada',25,NULL),(53,'2026-09-15 19:47:15',40000.00,'Transferencia','cancelada',22,NULL),(56,'2026-09-21 22:49:33',20000.00,'Efectivo','completada',21,NULL),(59,'2026-09-22 15:30:35',10000.00,'Efectivo','completada',21,NULL),(65,'2026-09-22 16:13:58',0.00,'Efectivo','completada',21,NULL),(66,'2026-09-22 16:15:51',10000.00,'Efectivo','completada',21,NULL);
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

-- Dump completed on 2026-09-23 19:50:23
