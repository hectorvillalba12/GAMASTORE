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
  `accion` enum('crear','editar','baja','reactivar','apertura','cierre','ingreso','egreso') NOT NULL,
  `datos_anteriores` text,
  `datos_nuevos` text,
  `usuario_id_usuario` int DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_auditoria`),
  KEY `usuario_id_usuario` (`usuario_id_usuario`),
  CONSTRAINT `auditoria_ibfk_1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria`
--

LOCK TABLES `auditoria` WRITE;
/*!40000 ALTER TABLE `auditoria` DISABLE KEYS */;
INSERT INTO `auditoria` VALUES (1,'producto',38,'editar','{\"id_producto\":38,\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":5000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"38\",\"nombre\":\"topper\",\"descripcion\":\"\",\"tipo\":\"correr\",\"precio\":\"30000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"16\"}',6,'2026-08-31 17:20:38'),(2,'producto',39,'crear',NULL,'{\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"16\"}',6,'2026-08-31 21:56:26'),(3,'producto',37,'baja','{\"id_producto\":37,\"nombre\":\"airmax\",\"descripcion\":\"addads\",\"tipodezapatillas\":\"deportiva\",\"precio\":3333,\"talle_id_talle\":15,\"marca_id_marca\":16,\"color_id_color\":14,\"categoria_id_categoria\":14,\"activo\":1}','{\"activo\":0}',6,'2026-08-31 21:56:49'),(4,'producto',40,'crear',NULL,'{\"nombre\":\"diadora\",\"descripcion\":\"msamsmadm\",\"tipodezapatillas\":\"urbana\",\"precio\":\"40000\",\"talle\":\"16\",\"marca\":\"13\",\"color\":\"16\",\"categoria\":\"14\"}',23,'2026-08-31 22:02:56'),(5,'producto',38,'baja','{\"id_producto\":38,\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":30000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":16,\"activo\":1}','{\"activo\":0}',23,'2026-08-31 22:03:44'),(6,'producto',41,'crear',NULL,'{\"nombre\":\"airmax\",\"descripcion\":\"zapatillas para correr\",\"tipodezapatillas\":\"running\",\"precio\":\"70000\",\"talle\":\"18\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"14\"}',6,'2026-09-01 19:34:38'),(7,'producto',42,'crear',NULL,'{\"nombre\":\"adidasmagic\",\"descripcion\":\"zapatillas nuevas\",\"tipodezapatillas\":\"urbana\",\"precio\":\"60000\",\"talle\":\"18\",\"marca\":\"15\",\"color\":\"14\",\"categoria\":\"15\"}',6,'2026-09-01 19:36:01'),(8,'producto',39,'editar','{\"id_producto\":39,\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"39\",\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipo\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"14\",\"categoria\":\"16\"}',6,'2026-09-01 20:40:30'),(9,'producto',43,'crear',NULL,'{\"nombre\":\"nike\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"60000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"13\",\"categoria\":\"14\"}',6,'2026-09-01 20:52:50'),(10,'producto',44,'crear',NULL,'{\"nombre\":\"ojotas nike\",\"descripcion\":\"mmm\",\"tipodezapatillas\":\"playeras\",\"precio\":\"10000\",\"talle\":\"15\",\"marca\":\"12\",\"color\":\"15\",\"categoria\":\"17\"}',6,'2026-09-01 21:14:03'),(11,'producto',40,'baja','{\"id_producto\":40,\"nombre\":\"diadora\",\"descripcion\":\"msamsmadm\",\"tipodezapatillas\":\"urbana\",\"precio\":40000,\"talle_id_talle\":16,\"marca_id_marca\":13,\"color_id_color\":16,\"categoria_id_categoria\":14,\"activo\":1}','{\"activo\":0}',6,'2026-09-04 15:34:22'),(12,'producto',45,'crear',NULL,'{\"nombre\":\"vans\",\"descripcion\":\"\",\"tipodezapatillas\":\"training\",\"precio\":\"50000\",\"talle\":\"21\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"18\"}',6,'2026-09-04 15:36:45'),(13,'producto',46,'crear',NULL,'{\"nombre\":\"vans\",\"descripcion\":\"\",\"tipodezapatillas\":\"correr\",\"precio\":\"50000\",\"talle\":\"20\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"14\"}',6,'2026-09-04 15:49:09'),(14,'producto',47,'crear',NULL,'{\"nombre\":\"pumas\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"40000\",\"talle\":\"21\",\"marca\":\"17\",\"color\":\"20\",\"categoria\":\"19\"}',6,'2026-09-04 19:27:49'),(15,'producto',48,'crear',NULL,'{\"nombre\":\"topper\",\"descripcion\":\"\",\"tipodezapatillas\":\"urbana\",\"precio\":\"45000\",\"talle\":\"17\",\"marca\":\"20\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 19:37:13'),(16,'producto',49,'crear',NULL,'{\"nombre\":\"nike messi\",\"descripcion\":\"\",\"tipodezapatillas\":\"fubol\",\"precio\":\"30000\",\"talle\":\"20\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"16\"}',6,'2026-09-04 20:30:56'),(17,'producto',50,'crear',NULL,'{\"nombre\":\"air max nike\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"25\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:33:49'),(18,'producto',51,'crear',NULL,'{\"nombre\":\"top700\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"30000\",\"talle\":\"20\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:37:04'),(19,'producto',52,'crear',NULL,'{\"nombre\":\"heelies\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"16\",\"marca\":\"17\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:40:08'),(20,'producto',53,'crear',NULL,'{\"nombre\":\"rebook\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"30000\",\"talle\":\"19\",\"marca\":\"18\",\"color\":\"15\",\"categoria\":\"19\"}',6,'2026-09-04 20:44:37'),(21,'producto',54,'crear',NULL,'{\"nombre\":\"knu\",\"descripcion\":\"\",\"tipodezapatillas\":\"urbana\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"18\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:47:09'),(22,'producto',55,'crear',NULL,'{\"nombre\":\"maracaibo\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"20\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-09-04 20:48:48'),(23,'producto',56,'crear',NULL,'{\"nombre\":\"air\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"10000\",\"talle\":\"19\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"19\"}',6,'2026-09-04 20:50:36'),(24,'producto',57,'crear',NULL,'{\"nombre\":\"nike pirane\",\"descripcion\":\"\",\"tipodezapatillas\":\"casual\",\"precio\":\"20000\",\"talle\":\"26\",\"marca\":\"16\",\"color\":\"17\",\"categoria\":\"17\"}',6,'2026-09-04 20:52:55'),(25,'producto',39,'editar','{\"id_producto\":39,\"nombre\":\"adidas\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipodezapatillas\":\"deportiva\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":14,\"categoria_id_categoria\":16,\"activo\":1}','{\"id\":\"39\",\"nombre\":\"topper\",\"descripcion\":\"lhkgjhfgjjhjg\",\"tipo\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"20\",\"color\":\"14\",\"categoria\":\"16\"}',25,'2026-09-15 13:42:19'),(26,'producto',56,'baja','{\"id_producto\":56,\"nombre\":\"air\",\"descripcion\":\"\",\"tipodezapatillas\":\"deportiva\",\"precio\":10000,\"talle_id_talle\":19,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":19,\"activo\":1}','{\"activo\":0}',6,'2026-09-15 19:23:16'),(27,'producto',42,'baja','{\"id_producto\":42,\"nombre\":\"adidasmagic\",\"descripcion\":\"zapatillas nuevas\",\"tipodezapatillas\":\"urbana\",\"precio\":60000,\"talle_id_talle\":18,\"marca_id_marca\":15,\"color_id_color\":14,\"categoria_id_categoria\":15,\"activo\":1}','{\"activo\":0}',6,'2026-09-15 20:15:21'),(28,'producto',58,'crear',NULL,'{\"nombre\":\"vans nueva\",\"descripcion\":\"nuevo producto\",\"tipodezapatillas\":\"skate\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"22\",\"color\":\"20\",\"categoria\":\"23\"}',6,'2026-09-29 20:22:39'),(29,'inventario',28,'baja','{\"id_inventario\":28,\"stock_actual\":\"7.00\",\"ubicacion\":\"Deposito B  - Estante 2\",\"stock_maximo\":10,\"stock_minimo\":7,\"producto_id_producto\":56,\"activo\":1,\"nombre_producto\":\"air\",\"precio\":10000,\"tipodezapatillas\":\"deportiva\"}','{\"activo\":0}',6,'2026-10-01 17:05:16'),(30,'inventario',28,'reactivar','{\"id_inventario\":28,\"stock_actual\":\"7.00\",\"ubicacion\":\"Deposito B  - Estante 2\",\"stock_maximo\":10,\"stock_minimo\":7,\"producto_id_producto\":56,\"activo\":0,\"nombre_producto\":\"air\",\"precio\":10000,\"tipodezapatillas\":\"deportiva\"}','{\"activo\":1}',6,'2026-10-01 17:05:25'),(31,'inventario',30,'baja','{\"id_inventario\":30,\"stock_actual\":\"9.00\",\"ubicacion\":\"Deposito A - Estante 2\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":58,\"activo\":1,\"nombre_producto\":\"vans nueva\",\"precio\":20000,\"tipodezapatillas\":\"skate\"}','{\"activo\":0}',6,'2026-10-01 17:08:35'),(32,'inventario',30,'reactivar','{\"id_inventario\":30,\"stock_actual\":\"10.00\",\"ubicacion\":\"Deposito A - Estante 2\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":58,\"activo\":0,\"nombre_producto\":\"vans nueva\",\"precio\":20000,\"tipodezapatillas\":\"skate\"}','{\"activo\":1}',6,'2026-10-01 17:08:59'),(33,'inventario',25,'baja','{\"id_inventario\":25,\"stock_actual\":\"7.00\",\"ubicacion\":\"Deposito B  - Estante 2\",\"stock_maximo\":8,\"stock_minimo\":6,\"producto_id_producto\":53,\"activo\":1,\"nombre_producto\":\"rebook\",\"precio\":30000,\"tipodezapatillas\":\"deportiva\"}','{\"activo\":0}',6,'2026-10-02 17:32:48'),(34,'producto',59,'crear',NULL,'{\"nombre\":\"rebook\",\"descripcion\":\"para caminar\",\"tipodezapatillas\":\"casual\",\"precio\":\"30000\",\"talle\":\"21\",\"marca\":\"21\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-10-02 17:33:39'),(35,'inventario',31,'baja','{\"id_inventario\":31,\"stock_actual\":\"6.00\",\"ubicacion\":\"Deposito C - Estante 1\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":59,\"activo\":1,\"nombre_producto\":\"rebook\",\"precio\":30000,\"tipodezapatillas\":\"casual\"}','{\"activo\":0}',6,'2026-10-02 17:44:27'),(36,'inventario',23,'baja','{\"id_inventario\":23,\"stock_actual\":\"5.00\",\"ubicacion\":\"Deposito B  - Estante 2\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":51,\"activo\":1,\"nombre_producto\":\"top700\",\"precio\":30000,\"tipodezapatillas\":\"casual\"}','{\"activo\":0}',6,'2026-10-02 17:44:38'),(37,'producto',60,'crear',NULL,'{\"nombre\":\"air jornan 1\",\"descripcion\":\"zapa urbana\",\"tipodezapatillas\":\"urbana\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"24\"}',6,'2026-10-13 14:57:49'),(38,'producto',61,'crear',NULL,'{\"nombre\":\"topper t350\",\"descripcion\":\"para correr\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"30000\",\"talle\":\"26\",\"marca\":\"20\",\"color\":\"19\",\"categoria\":\"19\"}',6,'2026-10-13 20:08:24'),(39,'inventario',33,'baja','{\"id_inventario\":33,\"stock_actual\":\"0.00\",\"ubicacion\":\"\",\"stock_maximo\":0,\"stock_minimo\":0,\"producto_id_producto\":61,\"activo\":1,\"nombre_producto\":\"topper t350\",\"precio\":30000,\"tipodezapatillas\":\"deportiva\"}','{\"activo\":0}',6,'2026-10-13 20:09:55'),(40,'inventario',20,'baja','{\"id_inventario\":20,\"stock_actual\":\"6.00\",\"ubicacion\":\"\",\"stock_maximo\":0,\"stock_minimo\":0,\"producto_id_producto\":48,\"activo\":1,\"nombre_producto\":\"topper\",\"precio\":45000,\"tipodezapatillas\":\"urbana\"}','{\"activo\":0}',6,'2026-10-13 20:10:07'),(41,'producto',62,'crear',NULL,'{\"nombre\":\"air max 90\",\"descripcion\":\"zapatilla nueva\",\"tipodezapatillas\":\"casual\",\"precio\":\"60000\",\"talle\":\"18\",\"marca\":\"16\",\"color\":\"16\",\"categoria\":\"17\"}',6,'2026-10-13 20:17:42'),(42,'producto',63,'crear',NULL,'{\"nombre\":\"air max 85\",\"descripcion\":\"zapa nuevas\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"18\",\"marca\":\"16\",\"color\":\"15\",\"categoria\":\"19\"}',6,'2026-10-13 20:20:58'),(43,'producto',60,'editar','{\"id_producto\":60,\"nombre\":\"air jornan 1\",\"descripcion\":\"zapa urbana\",\"imagen\":null,\"tipodezapatillas\":\"urbana\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":24,\"activo\":1}','{\"id\":\"60\",\"nombre\":\"air jornan 1\",\"descripcion\":\"zapa urbana\",\"imagen\":\"prod_c88408979a430a1d.jpg\",\"tipo\":\"urbana\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"categoria\":\"24\"}',6,'2026-10-29 14:24:58'),(44,'producto',64,'crear',NULL,'{\"nombre\":\"vans era\",\"descripcion\":\"producto nuevo de vans\",\"imagen\":\"prod_ba3e04f74666fb78.jpg\",\"tipodezapatillas\":\"deportiva\",\"precio\":\"600000\",\"talle\":\"16\",\"marca\":\"18\",\"categoria\":\"16\"}',6,'2026-10-29 15:34:02'),(45,'inventario',13,'baja','{\"id_inventario\":13,\"stock_actual\":\"10.00\",\"ubicacion\":\"Deposito A - Estante 2\",\"stock_maximo\":12,\"stock_minimo\":5,\"producto_id_producto\":43,\"activo\":1,\"nombre_producto\":\"nike\",\"precio\":60000,\"tipodezapatillas\":\"casual\",\"talle\":\"45\"}','{\"activo\":0}',6,'2026-10-30 16:35:33'),(46,'producto',65,'crear',NULL,'{\"nombre\":\"nike vomero\",\"descripcion\":\"zapatilla vomero\",\"imagen\":\"prod_4efdc22b5d7194b2.jpg\",\"tipodezapatillas\":\"running\",\"precio\":\"80000\",\"talle\":\"18\",\"marca\":\"12\",\"categoria\":\"14\"}',6,'2026-10-30 16:38:15'),(47,'producto',66,'crear',NULL,'{\"nombre\":\"adidas superstar\",\"descripcion\":\"zapatilla adidas\",\"imagen\":\"prod_abe83361c68ece01.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":\"700000\",\"talle\":\"16\",\"marca\":\"15\",\"categoria\":\"17\"}',6,'2026-10-30 16:57:27'),(48,'producto',66,'editar','{\"id_producto\":66,\"nombre\":\"adidas superstar\",\"descripcion\":\"zapatilla adidas\",\"imagen\":\"prod_abe83361c68ece01.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":700000,\"talle_id_talle\":16,\"marca_id_marca\":15,\"color_id_color\":null,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"66\",\"nombre\":\"adidas superstar\",\"descripcion\":\"zapatilla adidas\",\"imagen\":\"prod_abe83361c68ece01.jpg\",\"tipo\":\"casual\",\"precio\":\"70000\",\"talle\":\"16\",\"marca\":\"15\",\"categoria\":\"17\"}',6,'2026-10-30 16:57:49'),(49,'producto',50,'editar','{\"id_producto\":50,\"nombre\":\"air max nike\",\"descripcion\":\"\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":20000,\"talle_id_talle\":25,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"50\",\"nombre\":\"air max nike\",\"descripcion\":\"\",\"imagen\":\"prod_36f1b98f63bb9cf8.jpg\",\"tipo\":\"casual\",\"precio\":\"20000\",\"talle\":\"25\",\"marca\":\"16\",\"categoria\":\"17\"}',6,'2026-10-30 17:00:42'),(50,'producto',59,'editar','{\"id_producto\":59,\"nombre\":\"rebook\",\"descripcion\":\"para caminar\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":30000,\"talle_id_talle\":21,\"marca_id_marca\":21,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"59\",\"nombre\":\"rebook\",\"descripcion\":\"para caminar\",\"imagen\":\"prod_e70c6ec20f1d3e6a.jpg\",\"tipo\":\"casual\",\"precio\":\"30000\",\"talle\":\"21\",\"marca\":\"21\",\"categoria\":\"17\"}',6,'2026-10-30 18:43:17'),(51,'producto',59,'editar','{\"id_producto\":59,\"nombre\":\"rebook\",\"descripcion\":\"para caminar\",\"imagen\":\"prod_e70c6ec20f1d3e6a.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":30000,\"talle_id_talle\":21,\"marca_id_marca\":21,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"59\",\"nombre\":\"rebook\",\"descripcion\":\"para caminar\",\"imagen\":\"prod_e70c6ec20f1d3e6a.jpg\",\"tipo\":\"casual\",\"precio\":\"30000\",\"talle\":\"16\",\"marca\":\"21\",\"categoria\":\"17\"}',6,'2026-10-30 18:44:50'),(52,'inventario',31,'reactivar','{\"id_inventario\":31,\"stock_actual\":\"6.00\",\"ubicacion\":\"Deposito C - Estante 1\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":59,\"activo\":0,\"nombre_producto\":\"rebook\",\"precio\":30000,\"tipodezapatillas\":\"casual\",\"talle\":\"45\"}','{\"activo\":1}',6,'2026-10-30 18:45:08'),(53,'producto',52,'editar','{\"id_producto\":52,\"nombre\":\"heelies\",\"descripcion\":\"\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":20000,\"talle_id_talle\":16,\"marca_id_marca\":17,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"52\",\"nombre\":\"heelies\",\"descripcion\":\"\",\"imagen\":\"prod_1d10eb8f51d100a6.webp\",\"tipo\":\"casual\",\"precio\":\"20000\",\"talle\":\"16\",\"marca\":\"21\",\"categoria\":\"17\"}',6,'2026-10-30 18:47:15'),(54,'producto',44,'editar','{\"id_producto\":44,\"nombre\":\"ojotas nike\",\"descripcion\":\"mmm\",\"imagen\":null,\"tipodezapatillas\":\"playeras\",\"precio\":10000,\"talle_id_talle\":15,\"marca_id_marca\":12,\"color_id_color\":15,\"categoria_id_categoria\":17,\"activo\":1}','{\"id\":\"44\",\"nombre\":\"ojotas nike\",\"descripcion\":\"mmm\",\"imagen\":\"prod_2b4083cf8a87be7e.jpg\",\"tipo\":\"playeras\",\"precio\":\"10000\",\"talle\":\"15\",\"marca\":\"12\",\"categoria\":\"17\"}',6,'2026-10-30 18:52:56'),(55,'proveedor',3,'crear',NULL,'{\"nombre\":\"Hector villalba\",\"telefono\":\"3704219257\",\"direccion\":\"formosa argentina\",\"email\":\"villalbahector257@gmail.com\"}',6,'2026-10-31 16:44:43'),(56,'proveedor',4,'crear',NULL,'{\"nombre\":\"Milena vera\",\"telefono\":\"3774320392\",\"direccion\":\"formosa argentina\",\"email\":\"milenaverarenaut18@gmail.com\"}',6,'2026-10-31 16:56:49'),(57,'caja',1,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":70000,\"monto_esperado\":0,\"monto_declarado\":140000,\"diferencia\":140000,\"observacion\":\"ni idea\"}',6,'2026-11-01 02:19:54'),(58,'caja',2,'apertura',NULL,'{\"monto_inicial\":0,\"observacion\":\"\"}',6,'2026-11-01 02:33:30'),(59,'caja',2,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":170000,\"monto_esperado\":170000,\"monto_declarado\":170000,\"diferencia\":0,\"observacion\":\"\"}',6,'2026-11-01 02:36:41'),(60,'caja',3,'apertura',NULL,'{\"monto_inicial\":0,\"observacion\":\"\"}',6,'2026-11-01 20:19:22'),(61,'caja',3,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":140000,\"monto_esperado\":50000,\"monto_declarado\":39999.99,\"diferencia\":-10000.01,\"observacion\":\"Falta 10000\"}',6,'2026-11-01 20:21:09'),(62,'caja',4,'apertura',NULL,'{\"monto_inicial\":0,\"observacion\":\"\"}',6,'2026-11-02 16:49:06'),(63,'caja',4,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":140000,\"monto_esperado\":140000,\"monto_declarado\":140000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":20000,\"declarado\":20000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":50000,\"declarado\":50000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":1,\"esperado\":70000,\"declarado\":70000,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-02 17:08:09'),(64,'caja',5,'apertura',NULL,'{\"monto_inicial\":0,\"observacion\":\"\"}',6,'2026-11-02 17:08:27'),(65,'caja',5,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":140000,\"monto_esperado\":140000,\"monto_declarado\":100000,\"diferencia\":-40000,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":70000,\"declarado\":50000,\"diferencia\":-20000},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":20000,\"declarado\":10000,\"diferencia\":-10000},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":10000,\"diferencia\":10000},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":1,\"esperado\":50000,\"declarado\":30000,\"diferencia\":-20000}],\"observacion\":\"falta plata\"}',6,'2026-11-02 17:11:27'),(66,'caja',6,'apertura',NULL,'{\"monto_inicial\":20000,\"observacion\":\"\"}',6,'2026-11-02 17:31:55'),(67,'caja',6,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"20000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":60000,\"monto_esperado\":80000,\"monto_declarado\":80000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":80000,\"declarado\":80000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-02 17:33:56'),(68,'caja',7,'apertura',NULL,'{\"monto_inicial\":0,\"observacion\":\"\"}',6,'2026-11-03 20:21:32'),(69,'caja',7,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"0.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":120000,\"monto_esperado\":120000,\"monto_declarado\":120000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":70000,\"declarado\":70000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":1,\"esperado\":50000,\"declarado\":50000,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-03 20:24:35'),(70,'proveedor',5,'crear',NULL,'{\"nombre\":\"milton chavez\",\"telefono\":\"384940303\",\"direccion\":\"formosa argentina\",\"email\":\"chavezmilton082@gmail.com\"}',6,'2026-11-03 20:27:14'),(71,'caja',8,'apertura',NULL,'{\"monto_inicial\":100000,\"observacion\":\"\"}',6,'2026-11-03 20:52:36'),(72,'caja',8,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"100000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":120000,\"monto_esperado\":220000,\"monto_declarado\":220000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":220000,\"declarado\":220000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-03 20:56:39'),(73,'caja',9,'apertura',NULL,'{\"monto_inicial\":220000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-12 22:19:12'),(74,'caja_movimiento',2,'ingreso',NULL,'{\"caja_id\":9,\"monto\":10000,\"motivo\":\"cambio de billetes\"}',6,'2026-11-12 22:26:33'),(75,'caja_movimiento',3,'ingreso',NULL,'{\"caja_id\":9,\"monto\":10000,\"motivo\":\"cambio de billetes\"}',6,'2026-11-12 22:27:13'),(76,'caja_movimiento',4,'egreso',NULL,'{\"caja_id\":9,\"monto\":5000,\"motivo\":\"trajeron un bidon de agua\"}',6,'2026-11-12 22:27:32'),(77,'caja',9,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"220000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":160000,\"monto_esperado\":405000,\"monto_declarado\":405000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":295000,\"declarado\":295000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":50000,\"declarado\":50000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":1,\"esperado\":60000,\"declarado\":60000,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-12 22:31:20'),(78,'caja',10,'apertura',NULL,'{\"monto_inicial\":295000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-12 22:40:30'),(79,'caja_movimiento',5,'ingreso',NULL,'{\"caja_id\":10,\"monto\":10000,\"motivo\":\"dinero faltante de compra de zapatillas nike\"}',6,'2026-11-12 22:41:36'),(80,'caja_movimiento',6,'egreso',NULL,'{\"caja_id\":10,\"monto\":20000,\"motivo\":\"pago de 4 bidones de agua\"}',6,'2026-11-12 22:42:07'),(81,'caja',10,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"295000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":80000,\"monto_esperado\":365000,\"monto_declarado\":365000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":0,\"esperado\":285000,\"declarado\":285000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":20000,\"declarado\":20000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":1,\"esperado\":60000,\"declarado\":60000,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-12 22:44:29'),(82,'caja',11,'apertura',NULL,'{\"monto_inicial\":285000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-13 19:37:06'),(83,'caja_movimiento',7,'ingreso',NULL,'{\"caja_id\":11,\"monto\":10000,\"motivo\":\"cambio de billetes\"}',6,'2026-11-13 19:38:14'),(84,'caja_movimiento',8,'egreso',NULL,'{\"caja_id\":11,\"monto\":20000,\"motivo\":\"se compro 4 bidones de agua\"}',6,'2026-11-13 19:38:40'),(85,'caja',11,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"285000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":190000,\"monto_esperado\":465000,\"monto_declarado\":465000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":325000,\"declarado\":325000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":70000,\"declarado\":70000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":2,\"esperado\":70000,\"declarado\":70000,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-13 19:43:13'),(86,'caja',12,'apertura',NULL,'{\"monto_inicial\":325000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-13 19:44:14'),(87,'caja',12,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"325000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":70000,\"monto_esperado\":395000,\"monto_declarado\":395000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":395000,\"declarado\":395000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-13 19:45:44'),(88,'caja',13,'apertura',NULL,'{\"monto_inicial\":395000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-13 20:08:03'),(89,'proveedor',6,'crear',NULL,'{\"nombre\":\"ariel montengro\",\"telefono\":\"3748393332\",\"direccion\":\"formosa argentina\",\"email\":\"montenegroariel@gmail.com\"}',6,'2026-11-13 20:15:17'),(90,'caja',13,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"395000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":0,\"monto_esperado\":395000,\"monto_declarado\":395000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":0,\"esperado\":395000,\"declarado\":395000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-14 12:58:11'),(91,'producto',66,'editar','{\"id_producto\":66,\"nombre\":\"adidas superstar\",\"descripcion\":\"zapatilla adidas\",\"imagen\":\"prod_abe83361c68ece01.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":70000,\"talle_id_talle\":16,\"marca_id_marca\":15,\"color_id_color\":null,\"categoria_id_categoria\":17,\"activo\":1,\"codigo\":null}','{\"id\":\"66\",\"codigo\":\"000000123\",\"nombre\":\"adidas superstar\",\"descripcion\":\"zapatilla adidas\",\"imagen\":\"prod_abe83361c68ece01.jpg\",\"tipo\":\"casual\",\"precio\":\"70000\",\"talle\":\"16\",\"marca\":\"15\",\"categoria\":\"17\"}',6,'2026-11-14 14:11:59'),(92,'caja',14,'apertura',NULL,'{\"monto_inicial\":395000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-14 14:12:40'),(93,'producto',60,'editar','{\"id_producto\":60,\"nombre\":\"air jornan 1\",\"descripcion\":\"zapa urbana\",\"imagen\":\"prod_c88408979a430a1d.jpg\",\"tipodezapatillas\":\"urbana\",\"precio\":50000,\"talle_id_talle\":16,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":24,\"activo\":1,\"codigo\":null}','{\"id\":\"60\",\"codigo\":\"000000124\",\"nombre\":\"air jornan 1\",\"descripcion\":\"zapa urbana\",\"imagen\":\"prod_c88408979a430a1d.jpg\",\"tipo\":\"urbana\",\"precio\":\"50000\",\"talle\":\"16\",\"marca\":\"16\",\"categoria\":\"24\"}',6,'2026-11-14 14:42:50'),(94,'caja',14,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"395000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":170000,\"monto_esperado\":565000,\"monto_declarado\":565000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":0,\"esperado\":395000,\"declarado\":395000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":1,\"esperado\":170000,\"declarado\":170000,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-14 14:44:58'),(95,'producto',67,'crear',NULL,'{\"codigo\":\"002392303\",\"nombre\":\"nike pegasus\",\"descripcion\":\"\",\"imagen\":\"prod_4f77b180c369c9a0.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":\"80000\",\"talle\":\"27\",\"marca\":\"12\",\"categoria\":\"17\"}',6,'2026-11-14 14:48:57'),(96,'producto',65,'editar','{\"id_producto\":65,\"nombre\":\"nike vomero\",\"descripcion\":\"zapatilla vomero\",\"imagen\":\"prod_4efdc22b5d7194b2.jpg\",\"tipodezapatillas\":\"running\",\"precio\":80000,\"talle_id_talle\":18,\"marca_id_marca\":12,\"color_id_color\":null,\"categoria_id_categoria\":14,\"activo\":1,\"codigo\":null}','{\"id\":\"65\",\"codigo\":\"003943222\",\"nombre\":\"nike vomero\",\"descripcion\":\"zapatilla vomero\",\"imagen\":\"prod_4efdc22b5d7194b2.jpg\",\"tipo\":\"running\",\"precio\":\"80000\",\"talle\":\"18\",\"marca\":\"12\",\"categoria\":\"14\"}',6,'2026-11-14 14:50:34'),(97,'producto',62,'editar','{\"id_producto\":62,\"nombre\":\"air max 90\",\"descripcion\":\"zapatilla nueva\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":60000,\"talle_id_talle\":18,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1,\"codigo\":null}','{\"id\":\"62\",\"codigo\":\"023933112\",\"nombre\":\"air max 90\",\"descripcion\":\"zapatilla nueva\",\"imagen\":\"prod_dbade060984b0af8.jpg\",\"tipo\":\"casual\",\"precio\":\"60000\",\"talle\":\"18\",\"marca\":\"16\",\"categoria\":\"17\"}',6,'2026-11-14 14:55:26'),(98,'producto',58,'editar','{\"id_producto\":58,\"nombre\":\"vans nueva\",\"descripcion\":\"nuevo producto\",\"imagen\":null,\"tipodezapatillas\":\"skate\",\"precio\":20000,\"talle_id_talle\":17,\"marca_id_marca\":22,\"color_id_color\":20,\"categoria_id_categoria\":23,\"activo\":1,\"codigo\":null}','{\"id\":\"58\",\"codigo\":\"212334323\",\"nombre\":\"vans nueva\",\"descripcion\":\"nuevo producto\",\"imagen\":null,\"tipo\":\"skate\",\"precio\":\"20000\",\"talle\":\"17\",\"marca\":\"22\",\"categoria\":\"23\"}',6,'2026-11-14 14:55:56'),(99,'producto',63,'editar','{\"id_producto\":63,\"nombre\":\"air max 85\",\"descripcion\":\"zapa nuevas\",\"imagen\":null,\"tipodezapatillas\":\"deportiva\",\"precio\":50000,\"talle_id_talle\":18,\"marca_id_marca\":16,\"color_id_color\":15,\"categoria_id_categoria\":19,\"activo\":1,\"codigo\":null}','{\"id\":\"63\",\"codigo\":\"223300000\",\"nombre\":\"air max 85\",\"descripcion\":\"zapa nuevas\",\"imagen\":null,\"tipo\":\"deportiva\",\"precio\":\"50000\",\"talle\":\"18\",\"marca\":\"16\",\"categoria\":\"19\"}',6,'2026-11-14 14:56:20'),(100,'producto',50,'editar','{\"id_producto\":50,\"nombre\":\"air max nike\",\"descripcion\":\"\",\"imagen\":\"prod_36f1b98f63bb9cf8.jpg\",\"tipodezapatillas\":\"casual\",\"precio\":20000,\"talle_id_talle\":25,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1,\"codigo\":null}','{\"id\":\"50\",\"codigo\":\"NIKE-SKU-000\",\"nombre\":\"air max nike\",\"descripcion\":\"\",\"imagen\":\"prod_36f1b98f63bb9cf8.jpg\",\"tipo\":\"casual\",\"precio\":\"20000\",\"talle\":\"25\",\"marca\":\"16\",\"categoria\":\"17\"}',6,'2026-11-14 14:56:48'),(101,'producto',49,'baja','{\"id_producto\":49,\"nombre\":\"nike messi\",\"descripcion\":\"\",\"imagen\":null,\"tipodezapatillas\":\"fubol\",\"precio\":30000,\"talle_id_talle\":20,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":16,\"activo\":1,\"codigo\":null}','{\"activo\":0}',6,'2026-11-16 10:51:14'),(102,'producto',55,'baja','{\"id_producto\":55,\"nombre\":\"maracaibo\",\"descripcion\":\"\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":20000,\"talle_id_talle\":17,\"marca_id_marca\":20,\"color_id_color\":16,\"categoria_id_categoria\":17,\"activo\":1,\"codigo\":null}','{\"activo\":0}',6,'2026-11-16 10:51:22'),(103,'producto',57,'baja','{\"id_producto\":57,\"nombre\":\"nike pirane\",\"descripcion\":\"\",\"imagen\":null,\"tipodezapatillas\":\"casual\",\"precio\":20000,\"talle_id_talle\":26,\"marca_id_marca\":16,\"color_id_color\":17,\"categoria_id_categoria\":17,\"activo\":1,\"codigo\":null}','{\"activo\":0}',6,'2026-11-16 10:51:35'),(104,'inventario',29,'baja','{\"id_inventario\":29,\"stock_actual\":\"7.00\",\"ubicacion\":\"Deposito A - Estante 2\",\"stock_maximo\":11,\"stock_minimo\":6,\"producto_id_producto\":57,\"activo\":1,\"nombre_producto\":\"nike pirane\",\"precio\":20000,\"tipodezapatillas\":\"casual\",\"talle\":\"35\"}','{\"activo\":0}',6,'2026-11-16 10:52:14'),(105,'caja',15,'apertura',NULL,'{\"monto_inicial\":395000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-16 15:49:14'),(106,'caja',15,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"395000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":70000,\"monto_esperado\":465000,\"monto_declarado\":465000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":405000,\"declarado\":405000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":60000,\"declarado\":60000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-16 16:04:49'),(107,'producto',41,'editar','{\"id_producto\":41,\"nombre\":\"airmax\",\"descripcion\":\"zapatillas para correr\",\"imagen\":null,\"tipodezapatillas\":\"running\",\"precio\":70000,\"talle_id_talle\":18,\"marca_id_marca\":16,\"color_id_color\":16,\"categoria_id_categoria\":14,\"activo\":1,\"codigo\":null}','{\"id\":\"41\",\"codigo\":\"000000125\",\"nombre\":\"airmax\",\"descripcion\":\"zapatillas para correr\",\"imagen\":null,\"tipo\":\"running\",\"precio\":\"70000\",\"talle\":\"18\",\"marca\":\"16\",\"categoria\":\"14\"}',6,'2026-11-16 16:06:52'),(108,'caja',16,'apertura',NULL,'{\"monto_inicial\":405000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-16 16:09:08'),(109,'caja',16,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"405000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":170000,\"monto_esperado\":575000,\"monto_declarado\":575000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":475000,\"declarado\":475000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":100000,\"declarado\":100000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-16 16:11:22'),(110,'caja',17,'apertura',NULL,'{\"monto_inicial\":475000,\"arrastrado\":true,\"observacion\":\"\"}',6,'2026-11-17 20:09:21'),(111,'caja',17,'cierre','{\"estado\":\"abierta\",\"monto_inicial\":\"475000.00\"}','{\"estado\":\"cerrada\",\"total_ventas\":120000,\"monto_esperado\":595000,\"monto_declarado\":595000,\"diferencia\":0,\"por_metodo\":[{\"metodo\":\"Efectivo\",\"cantidad\":1,\"esperado\":555000,\"declarado\":555000,\"diferencia\":0},{\"metodo\":\"Transferencia\",\"cantidad\":1,\"esperado\":40000,\"declarado\":40000,\"diferencia\":0},{\"metodo\":\"Tarjeta Crédito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0},{\"metodo\":\"Tarjeta Débito\",\"cantidad\":0,\"esperado\":0,\"declarado\":0,\"diferencia\":0}],\"observacion\":\"\"}',6,'2026-11-17 20:25:25');
/*!40000 ALTER TABLE `auditoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja`
--

DROP TABLE IF EXISTS `caja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caja` (
  `id_caja` int NOT NULL AUTO_INCREMENT,
  `estado` varchar(10) NOT NULL DEFAULT 'abierta',
  `fecha_apertura` datetime NOT NULL,
  `monto_inicial` decimal(10,2) NOT NULL DEFAULT '0.00',
  `observacion_apertura` varchar(255) DEFAULT NULL,
  `usuario_apertura_id` int NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `total_ventas` decimal(10,2) DEFAULT NULL,
  `cantidad_ventas` int DEFAULT NULL,
  `monto_esperado` decimal(10,2) DEFAULT NULL,
  `monto_declarado` decimal(10,2) DEFAULT NULL,
  `diferencia` decimal(10,2) DEFAULT NULL,
  `observacion_cierre` varchar(255) DEFAULT NULL,
  `usuario_cierre_id` int DEFAULT NULL,
  `abierta_unica` tinyint GENERATED ALWAYS AS (if((`estado` = _utf8mb3'abierta'),1,NULL)) STORED,
  PRIMARY KEY (`id_caja`),
  UNIQUE KEY `uq_caja_abierta_unica` (`abierta_unica`),
  KEY `fk_caja_usuario_apertura_idx` (`usuario_apertura_id`),
  KEY `fk_caja_usuario_cierre_idx` (`usuario_cierre_id`),
  CONSTRAINT `fk_caja_usuario_apertura` FOREIGN KEY (`usuario_apertura_id`) REFERENCES `usuario` (`id_usuario`),
  CONSTRAINT `fk_caja_usuario_cierre` FOREIGN KEY (`usuario_cierre_id`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja`
--

LOCK TABLES `caja` WRITE;
/*!40000 ALTER TABLE `caja` DISABLE KEYS */;
INSERT INTO `caja` (`id_caja`, `estado`, `fecha_apertura`, `monto_inicial`, `observacion_apertura`, `usuario_apertura_id`, `fecha_cierre`, `total_ventas`, `cantidad_ventas`, `monto_esperado`, `monto_declarado`, `diferencia`, `observacion_cierre`, `usuario_cierre_id`) VALUES (1,'cerrada','2026-11-01 02:08:19',0.00,NULL,6,'2026-11-01 02:19:54',70000.00,5,0.00,140000.00,140000.00,'ni idea',6),(2,'cerrada','2026-11-01 02:33:30',0.00,NULL,6,'2026-11-01 02:36:41',170000.00,1,170000.00,170000.00,0.00,NULL,6),(3,'cerrada','2026-11-01 20:19:22',0.00,NULL,6,'2026-11-01 20:21:09',140000.00,3,50000.00,39999.99,-10000.01,'Falta 10000',6),(4,'cerrada','2026-11-02 16:49:06',0.00,NULL,6,'2026-11-02 17:08:09',140000.00,3,140000.00,140000.00,0.00,NULL,6),(5,'cerrada','2026-11-02 17:08:27',0.00,NULL,6,'2026-11-02 17:11:27',140000.00,3,140000.00,100000.00,-40000.00,'falta plata',6),(6,'cerrada','2026-11-02 17:31:55',20000.00,NULL,6,'2026-11-02 17:33:56',60000.00,1,80000.00,80000.00,0.00,NULL,6),(7,'cerrada','2026-11-03 20:21:32',0.00,NULL,6,'2026-11-03 20:24:35',120000.00,2,120000.00,120000.00,0.00,NULL,6),(8,'cerrada','2026-11-03 20:52:36',100000.00,NULL,6,'2026-11-03 20:56:39',120000.00,1,220000.00,220000.00,0.00,NULL,6),(9,'cerrada','2026-11-12 22:19:12',220000.00,NULL,6,'2026-11-12 22:31:20',160000.00,3,405000.00,405000.00,0.00,NULL,6),(10,'cerrada','2026-11-12 22:40:30',295000.00,NULL,6,'2026-11-12 22:44:29',80000.00,2,365000.00,365000.00,0.00,NULL,6),(11,'cerrada','2026-11-13 19:37:05',285000.00,NULL,6,'2026-11-13 19:43:13',190000.00,4,465000.00,465000.00,0.00,NULL,6),(12,'cerrada','2026-11-13 19:44:14',325000.00,NULL,6,'2026-11-13 19:45:44',70000.00,1,395000.00,395000.00,0.00,NULL,6),(13,'cerrada','2026-11-13 20:08:03',395000.00,NULL,6,'2026-11-14 12:58:11',0.00,0,395000.00,395000.00,0.00,NULL,6),(14,'cerrada','2026-11-14 14:12:40',395000.00,NULL,6,'2026-11-14 14:44:58',170000.00,1,565000.00,565000.00,0.00,NULL,6),(15,'cerrada','2026-11-16 15:49:14',395000.00,NULL,6,'2026-11-16 16:04:49',70000.00,1,465000.00,465000.00,0.00,NULL,6),(16,'cerrada','2026-11-16 16:09:08',405000.00,NULL,6,'2026-11-16 16:11:22',170000.00,1,575000.00,575000.00,0.00,NULL,6),(17,'cerrada','2026-11-17 20:09:21',475000.00,NULL,6,'2026-11-17 20:25:25',120000.00,1,595000.00,595000.00,0.00,NULL,6);
/*!40000 ALTER TABLE `caja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_arqueo`
--

DROP TABLE IF EXISTS `caja_arqueo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caja_arqueo` (
  `id_caja_arqueo` int NOT NULL AUTO_INCREMENT,
  `caja_id` int NOT NULL,
  `metodo_de_pago` varchar(45) NOT NULL,
  `cantidad_ventas` int NOT NULL DEFAULT '0',
  `monto_esperado` decimal(10,2) NOT NULL,
  `monto_declarado` decimal(10,2) NOT NULL,
  `diferencia` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_caja_arqueo`),
  UNIQUE KEY `uq_arqueo_caja_metodo` (`caja_id`,`metodo_de_pago`),
  CONSTRAINT `fk_arqueo_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`id_caja`)
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_arqueo`
--

LOCK TABLES `caja_arqueo` WRITE;
/*!40000 ALTER TABLE `caja_arqueo` DISABLE KEYS */;
INSERT INTO `caja_arqueo` VALUES (1,4,'Efectivo',1,20000.00,20000.00,0.00),(2,4,'Transferencia',1,50000.00,50000.00,0.00),(3,4,'Tarjeta Crédito',0,0.00,0.00,0.00),(4,4,'Tarjeta Débito',1,70000.00,70000.00,0.00),(5,5,'Efectivo',1,70000.00,50000.00,-20000.00),(6,5,'Transferencia',1,20000.00,10000.00,-10000.00),(7,5,'Tarjeta Crédito',0,0.00,10000.00,10000.00),(8,5,'Tarjeta Débito',1,50000.00,30000.00,-20000.00),(9,6,'Efectivo',1,80000.00,80000.00,0.00),(10,6,'Transferencia',0,0.00,0.00,0.00),(11,6,'Tarjeta Crédito',0,0.00,0.00,0.00),(12,6,'Tarjeta Débito',0,0.00,0.00,0.00),(13,7,'Efectivo',1,70000.00,70000.00,0.00),(14,7,'Transferencia',0,0.00,0.00,0.00),(15,7,'Tarjeta Crédito',1,50000.00,50000.00,0.00),(16,7,'Tarjeta Débito',0,0.00,0.00,0.00),(17,8,'Efectivo',1,220000.00,220000.00,0.00),(18,8,'Transferencia',0,0.00,0.00,0.00),(19,8,'Tarjeta Crédito',0,0.00,0.00,0.00),(20,8,'Tarjeta Débito',0,0.00,0.00,0.00),(21,9,'Efectivo',1,295000.00,295000.00,0.00),(22,9,'Transferencia',1,50000.00,50000.00,0.00),(23,9,'Tarjeta Crédito',0,0.00,0.00,0.00),(24,9,'Tarjeta Débito',1,60000.00,60000.00,0.00),(25,10,'Efectivo',0,285000.00,285000.00,0.00),(26,10,'Transferencia',1,20000.00,20000.00,0.00),(27,10,'Tarjeta Crédito',1,60000.00,60000.00,0.00),(28,10,'Tarjeta Débito',0,0.00,0.00,0.00),(29,11,'Efectivo',1,325000.00,325000.00,0.00),(30,11,'Transferencia',1,70000.00,70000.00,0.00),(31,11,'Tarjeta Crédito',2,70000.00,70000.00,0.00),(32,11,'Tarjeta Débito',0,0.00,0.00,0.00),(33,12,'Efectivo',1,395000.00,395000.00,0.00),(34,12,'Transferencia',0,0.00,0.00,0.00),(35,12,'Tarjeta Crédito',0,0.00,0.00,0.00),(36,12,'Tarjeta Débito',0,0.00,0.00,0.00),(37,13,'Efectivo',0,395000.00,395000.00,0.00),(38,13,'Transferencia',0,0.00,0.00,0.00),(39,13,'Tarjeta Crédito',0,0.00,0.00,0.00),(40,13,'Tarjeta Débito',0,0.00,0.00,0.00),(41,14,'Efectivo',0,395000.00,395000.00,0.00),(42,14,'Transferencia',0,0.00,0.00,0.00),(43,14,'Tarjeta Crédito',0,0.00,0.00,0.00),(44,14,'Tarjeta Débito',1,170000.00,170000.00,0.00),(45,15,'Efectivo',1,405000.00,405000.00,0.00),(46,15,'Transferencia',1,60000.00,60000.00,0.00),(47,15,'Tarjeta Crédito',0,0.00,0.00,0.00),(48,15,'Tarjeta Débito',0,0.00,0.00,0.00),(49,16,'Efectivo',1,475000.00,475000.00,0.00),(50,16,'Transferencia',1,100000.00,100000.00,0.00),(51,16,'Tarjeta Crédito',0,0.00,0.00,0.00),(52,16,'Tarjeta Débito',0,0.00,0.00,0.00),(53,17,'Efectivo',1,555000.00,555000.00,0.00),(54,17,'Transferencia',1,40000.00,40000.00,0.00),(55,17,'Tarjeta Crédito',0,0.00,0.00,0.00),(56,17,'Tarjeta Débito',0,0.00,0.00,0.00);
/*!40000 ALTER TABLE `caja_arqueo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `caja_movimiento`
--

DROP TABLE IF EXISTS `caja_movimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `caja_movimiento` (
  `id_caja_movimiento` int NOT NULL AUTO_INCREMENT,
  `caja_id` int NOT NULL,
  `tipo` enum('ingreso','egreso') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `motivo` varchar(150) NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_caja_movimiento`),
  KEY `idx_mov_caja` (`caja_id`),
  KEY `fk_mov_usuario` (`usuario_id`),
  CONSTRAINT `fk_mov_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`id_caja`),
  CONSTRAINT `fk_mov_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `caja_movimiento`
--

LOCK TABLES `caja_movimiento` WRITE;
/*!40000 ALTER TABLE `caja_movimiento` DISABLE KEYS */;
INSERT INTO `caja_movimiento` VALUES (1,9,'ingreso',10000.00,'cambio de billetes',6,'2026-11-12 22:21:06'),(2,9,'ingreso',10000.00,'cambio de billetes',6,'2026-11-12 22:26:33'),(3,9,'ingreso',10000.00,'cambio de billetes',6,'2026-11-12 22:27:13'),(4,9,'egreso',5000.00,'trajeron un bidon de agua',6,'2026-11-12 22:27:32'),(5,10,'ingreso',10000.00,'dinero faltante de compra de zapatillas nike',6,'2026-11-12 22:41:36'),(6,10,'egreso',20000.00,'pago de 4 bidones de agua',6,'2026-11-12 22:42:07'),(7,11,'ingreso',10000.00,'cambio de billetes',6,'2026-11-13 19:38:14'),(8,11,'egreso',20000.00,'se compro 4 bidones de agua',6,'2026-11-13 19:38:39');
/*!40000 ALTER TABLE `caja_movimiento` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (14,'running'),(15,'basket'),(16,'futbol'),(17,'casual'),(18,'training'),(19,'competicion'),(20,'tenis'),(21,''),(22,'skate'),(23,'patineta'),(24,'urbana');
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
INSERT INTO `cliente` VALUES (19,'2026-05-22 21:41:09',NULL,NULL,'activo',21,NULL,0),(20,'2026-05-22 21:44:57',NULL,NULL,'activo',22,NULL,0),(21,'2026-06-06 20:12:24',NULL,NULL,'activo',23,NULL,0),(22,'2026-06-15 01:57:17',NULL,NULL,'activo',24,NULL,0),(23,'2026-09-02 00:45:38',NULL,NULL,'activo',25,NULL,0),(24,'2026-09-04 18:51:17',NULL,NULL,'activo',26,NULL,0),(25,'2026-09-04 23:01:16',NULL,NULL,'activo',27,NULL,0),(26,'2026-09-04 23:05:58',NULL,NULL,'activo',28,NULL,0),(27,'2026-09-04 23:08:42',NULL,NULL,'activo',29,NULL,0),(28,'2026-09-04 23:11:05',NULL,NULL,'activo',30,NULL,0),(29,'2026-09-04 23:13:22',NULL,NULL,'activo',31,NULL,0),(30,'2026-09-04 23:16:00',NULL,NULL,'activo',32,NULL,0),(31,'2026-09-04 23:18:36',NULL,NULL,'activo',33,NULL,0),(32,'2026-09-04 23:20:53',NULL,NULL,'activo',34,NULL,0),(33,'2026-09-04 23:26:08',NULL,NULL,'activo',35,NULL,0),(34,'2026-09-04 23:26:53',NULL,NULL,'activo',36,NULL,0),(35,'2026-09-04 23:29:59',NULL,NULL,'activo',37,NULL,0),(36,'2026-09-04 23:32:56',NULL,NULL,'activo',38,NULL,0),(37,'2026-09-04 23:35:36',NULL,NULL,'activo',39,NULL,0),(38,'2026-09-29 23:02:44',NULL,NULL,'activo',40,NULL,0);
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
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
INSERT INTO `detalle_venta` VALUES (3,0,'Producto #37 x1',1,3333,0.00,3333,4033,16,37,NULL),(4,0,'topper (Stock: 6.00) x1',1,5000,0.00,5000,6050,17,38,NULL),(5,0,'topper (Stock: 5.00) x2',2,5000,0.00,10000,12100,18,38,NULL),(6,0,'nike (Stock: 10.00) x3',3,60000,0.00,180000,217800,19,43,NULL),(7,0,'nike (Stock: 9.00) x5',5,60000,0.00,300000,363000,20,43,NULL),(8,0,'ojotas nike (Stock: 9.00) x5',5,10000,0.00,50000,60500,21,44,NULL),(9,0,'nike (Stock: 17.00) x2',2,60000,0.00,120000,145200,22,43,NULL),(10,0,'ojotas nike (Stock: 14.00) x2',2,10000,0.00,20000,24200,22,44,NULL),(11,0,'ojotas nike (Stock: 12.00) x1',1,10000,0.00,10000,12100,23,44,NULL),(12,0,'nike (Stock: 15.00) x1',1,60000,0.00,60000,72600,24,43,NULL),(13,0,'nike (Stock: 9.00) x1',1,60000,0.00,60000,60000,25,43,NULL),(14,0,'vans (Stock: 9.00) x1',1,50000,0.00,50000,50000,26,45,NULL),(15,0,'ojotas nike (Stock: 11.00) x1',1,10000,0.00,10000,10000,26,44,NULL),(16,0,'nike (Stock: 13.00) x1',1,60000,0.00,60000,60000,26,43,NULL),(17,0,'vans (Stock: 7.00) x1',1,50000,0.00,50000,50000,27,46,NULL),(18,0,'vans (Stock: 6.00) x6',6,50000,0.00,300000,300000,28,46,NULL),(19,0,'nike (Stock: 9.00) x8',8,60000,0.00,480000,480000,28,43,NULL),(20,0,'ojotas nike (Stock: 10.00) x1',1,10000,0.00,10000,10000,29,44,NULL),(21,0,'nike (Stock: 19.00) x2',2,60000,0.00,120000,120000,30,43,NULL),(22,0,'vans (Stock: 8.00) x3',3,50000,0.00,150000,150000,30,45,NULL),(23,0,'ojotas nike (Stock: 9.00) x3',3,10000,0.00,30000,30000,30,44,NULL),(24,0,'vans (Stock: 19.00) x1',1,50000,0.00,50000,50000,31,46,NULL),(25,0,'pumas (Stock: 7.00) x1',1,40000,0.00,40000,40000,32,47,NULL),(26,0,'nike (Stock: 17.00) x2',2,60000,0.00,120000,120000,32,43,NULL),(27,0,'nike (Stock: 15.00) x1',1,60000,0.00,60000,60000,33,43,NULL),(28,0,'pumas (Stock: 6.00) x1',1,40000,0.00,40000,40000,33,47,NULL),(29,0,'vans (Stock: 9.00) x1',1,50000,0.00,50000,50000,34,45,NULL),(30,0,'nike (Stock: 14.00) x2',2,60000,0.00,120000,120000,34,43,NULL),(31,0,'vans (Stock: 18.00) x2',2,50000,0.00,100000,100000,35,46,NULL),(32,0,'pumas (Stock: 5.00) x1',1,40000,0.00,40000,40000,35,47,NULL),(33,0,'topper (Stock: 8.00) x1',1,45000,0.00,45000,45000,36,48,NULL),(34,0,'nike (Stock: 12.00) x1',1,60000,0.00,60000,60000,36,43,NULL),(35,0,'nike (Stock: 11.00) x2',2,60000,0.00,120000,120000,37,43,NULL),(36,0,'nike (Stock: 9.00) x1',1,60000,0.00,60000,60000,38,43,NULL),(37,0,'pumas (Stock: 12.00) x1',1,40000,0.00,40000,40000,38,47,NULL),(38,0,'vans (Stock: 16.00) x1',1,50000,0.00,50000,50000,39,46,NULL),(39,0,'topper (Stock: 7.00) x1',1,45000,0.00,45000,45000,39,48,NULL),(40,0,'nike (Stock: 9.00) x2',2,60000,0.00,120000,120000,40,43,NULL),(41,0,'vans (Stock: 15.00) x1',1,50000,0.00,50000,50000,41,46,NULL),(42,0,'pumas (Stock: 11.00) x1',1,40000,0.00,40000,40000,41,47,NULL),(43,0,'vans (Stock: 14.00) x2',2,50000,0.00,100000,100000,42,46,NULL),(44,0,'nike (Stock: 12.00) x1',1,60000,0.00,60000,60000,42,43,NULL),(45,0,'nike messi (Stock: 10.00) x1',1,30000,0.00,30000,30000,43,49,NULL),(46,0,'air max nike (Stock: 10.00) x2',2,20000,0.00,40000,40000,44,50,NULL),(47,0,'top700 (Stock: 10.00) x5',5,30000,0.00,150000,150000,45,51,NULL),(48,0,'heelies (Stock: 10.00) x6',6,20000,0.00,120000,120000,46,52,NULL),(49,0,'rebook (Stock: 10.00) x3',3,30000,0.00,90000,90000,47,53,NULL),(50,0,'knu (Stock: 10.00) x5',5,20000,0.00,100000,100000,48,54,NULL),(51,0,'maracaibo (Stock: 10.00) x3',3,20000,0.00,60000,60000,49,55,NULL),(52,0,'air (Stock: 10.00) x2',2,10000,0.00,20000,20000,50,56,NULL),(53,0,'nike pirane (Stock: 10.00) x10',10,20000,0.00,200000,200000,51,57,NULL),(54,0,'air (Stock: 8.00) x1',1,10000,0.00,10000,10000,52,56,NULL),(55,0,'air max nike (Stock: 8.00) x2',2,20000,0.00,40000,40000,53,50,NULL),(56,0,'nike pirane (Stock: 10.00) x1',1,20000,0.00,20000,20000,56,57,NULL),(57,0,'nike messi (Stock: 9.00) x1 — Promo: primavera-verano',1,30000,20000.00,30000,10000,59,49,8),(58,0,'nike pirane x1 — Promo: primavera-verano',1,20000,20000.00,20000,0,65,57,8),(59,0,'nike messi x1 — Promo: primavera-verano',1,30000,20000.00,30000,10000,66,49,8),(60,0,'vans nueva x1',1,20000,0.00,20000,20000,67,58,NULL),(61,0,'knu x2',2,20000,0.00,40000,40000,68,54,NULL),(62,0,'nike x2 — Promo: prueba de hoy',2,60000,10000.00,120000,110000,69,43,10),(63,0,'ojotas nike x6',6,10000,0.00,60000,60000,69,44,NULL),(64,0,'air max nike x10',10,20000,0.00,200000,200000,70,50,NULL),(65,0,'maracaibo x5',5,20000,0.00,100000,100000,71,55,NULL),(66,0,'rebook x4',4,30000,0.00,120000,120000,72,59,NULL),(67,0,'nike x1 — Promo: prueba de hoy',1,60000,10000.00,60000,50000,73,43,10),(68,0,'air max nike x3',3,20000,0.00,60000,60000,73,50,NULL),(69,0,'air max nike x1',1,20000,0.00,20000,20000,74,50,NULL),(70,0,'air jornan 1 x3',3,50000,0.00,150000,150000,75,60,NULL),(71,0,'air jornan 1 x1',1,50000,0.00,50000,50000,76,60,NULL),(72,0,'air max 85 x1',1,50000,0.00,50000,50000,77,63,NULL),(73,0,'air max 90 x2',2,60000,0.00,120000,120000,78,62,NULL),(74,0,'nike vomero x1',1,80000,0.00,80000,80000,79,65,NULL),(75,0,'nike pirane x1 — Promo: liquidacion de verano',1,20000,6000.00,20000,14000,80,57,9),(76,0,'adidas superstar x1',1,70000,0.00,70000,70000,85,66,NULL),(77,0,'air max 85 x1',1,50000,0.00,50000,50000,86,63,NULL),(78,0,'adidas superstar x1',1,70000,0.00,70000,70000,86,66,NULL),(79,0,'air max nike x1',1,20000,0.00,20000,20000,86,50,NULL),(80,0,'nike messi x1',1,30000,0.00,30000,30000,86,49,NULL),(81,0,'air max 85 x1',1,50000,0.00,50000,50000,87,63,NULL),(82,0,'air max nike x1',1,20000,0.00,20000,20000,88,50,NULL),(83,0,'adidas superstar x1',1,70000,0.00,70000,70000,89,66,NULL),(84,0,'adidas superstar x1',1,70000,0.00,70000,70000,90,66,NULL),(85,0,'air max 85 x1',1,50000,0.00,50000,50000,91,63,NULL),(86,0,'air max nike x1',1,20000,0.00,20000,20000,92,50,NULL),(87,0,'adidas superstar x1',1,70000,0.00,70000,70000,93,66,NULL),(88,0,'air max 85 x1',1,50000,0.00,50000,50000,94,63,NULL),(89,0,'air max nike x1',1,20000,0.00,20000,20000,95,50,NULL),(90,0,'air max 90 x1',1,60000,0.00,60000,60000,96,62,NULL),(91,0,'adidas superstar x1',1,70000,0.00,70000,70000,97,66,NULL),(92,0,'adidas superstar x1',1,70000,0.00,70000,70000,98,66,NULL),(93,0,'air max 85 x1',1,50000,0.00,50000,50000,99,63,NULL),(94,0,'adidas superstar x1',1,70000,0.00,70000,70000,100,66,NULL),(95,0,'air jornan 1 x1',1,50000,0.00,50000,50000,100,60,NULL),(96,0,'air jornan 1 x1',1,50000,0.00,50000,50000,101,60,NULL),(97,0,'air max 90 x1',1,60000,0.00,60000,60000,102,62,NULL),(98,0,'air max 85 x1',1,50000,0.00,50000,50000,103,63,NULL),(99,0,'air max nike x1',1,20000,0.00,20000,20000,104,50,NULL),(100,0,'air max 90 x1',1,60000,0.00,60000,60000,105,62,NULL),(101,0,'air max nike x1',1,20000,0.00,20000,20000,106,50,NULL),(102,0,'adidas superstar x1',1,70000,0.00,70000,70000,107,66,NULL),(103,0,'air max 85 x1',1,50000,0.00,50000,50000,108,63,NULL),(104,0,'air jornan 1 x1',1,50000,0.00,50000,50000,109,60,NULL),(105,0,'air max nike x1',1,20000,0.00,20000,20000,110,50,NULL),(106,0,'adidas superstar x1',1,70000,0.00,70000,70000,111,66,NULL),(107,0,'air jornan 1 x1',1,50000,0.00,50000,50000,112,60,NULL),(108,0,'air jornan 1 x2',2,50000,0.00,100000,100000,113,60,NULL),(109,0,'adidas superstar x1',1,70000,0.00,70000,70000,113,66,NULL),(110,0,'adidas superstar x1',1,70000,0.00,70000,70000,114,66,NULL),(111,0,'adidas superstar x1',1,70000,0.00,70000,70000,115,66,NULL),(112,0,'air jornan 1 x2',2,50000,0.00,100000,100000,115,60,NULL),(113,0,'air jornan 1 x1',1,50000,0.00,50000,50000,116,60,NULL),(114,0,'adidas superstar x1',1,70000,0.00,70000,70000,116,66,NULL);
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
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_inventario`),
  KEY `fk_inventario_producto1_idx` (`producto_id_producto`),
  CONSTRAINT `fk_inventario_producto1` FOREIGN KEY (`producto_id_producto`) REFERENCES `producto` (`id_producto`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario`
--

LOCK TABLES `inventario` WRITE;
/*!40000 ALTER TABLE `inventario` DISABLE KEYS */;
INSERT INTO `inventario` VALUES (6,6.00,'Deposito C - Estante 1',10,4,37,1),(7,11.00,'Deposito B  - Estante 2',12,4,38,1),(12,10.00,'Deposito A - Estante 2',20,8,43,1),(13,10.00,'Deposito A - Estante 2',12,5,43,0),(15,10.00,'Deposito B  - Estante 2',20,8,44,1),(17,8.00,'Deposito A - Estante 2',10,6,45,1),(18,10.00,'Deposito B  - Estante 2',10,6,46,1),(19,10.00,'Deposito A - Estante 2',8,4,47,1),(20,6.00,'',0,0,48,0),(21,7.00,'Deposito A - Estante 2',11,6,49,1),(22,4.00,'Deposito B  - Estante 2',11,6,50,1),(23,5.00,'Deposito B  - Estante 2',11,6,51,0),(24,2.00,'Deposito A - Estante 2',10,6,52,1),(25,7.00,'Deposito B  - Estante 2',8,6,53,0),(26,3.00,'Deposito C - Estante 1',10,7,54,1),(27,7.00,'Deposito A - Estante 2',10,7,55,1),(28,10.00,'Deposito B  - Estante 2',11,7,56,1),(29,7.00,'Deposito A - Estante 2',11,6,57,0),(30,10.00,'Deposito A - Estante 2',13,6,58,1),(31,8.00,'Deposito C - Estante 1',11,6,59,1),(32,0.00,'Deposito C - Estante 1',10,5,60,1),(33,0.00,'',0,0,61,0),(34,10.00,'Deposito B  - Estante 2',11,6,61,1),(35,5.00,'Deposito A - Estante 2',11,6,62,1),(36,3.00,'Deposito A - Estante 2',11,7,63,1),(37,10.00,'Deposito C - Estante 1',11,5,64,1),(38,9.00,'Deposito B  - Estante 2',11,6,65,1),(39,1.00,'Deposito A - Estante 2',11,6,66,1),(40,10.00,'Deposito B  - Estante 2',11,6,67,1);
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marca`
--

LOCK TABLES `marca` WRITE;
/*!40000 ALTER TABLE `marca` DISABLE KEYS */;
INSERT INTO `marca` VALUES (12,'nike','nike'),(13,'diadora','diadora'),(15,'adidas','libre'),(16,'nike','sss'),(17,'pumas',NULL),(18,'vans',NULL),(19,'crocs',NULL),(20,'topper',NULL),(21,'rebook',NULL),(22,'vans2',NULL);
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
  `motivo` varchar(500) DEFAULT NULL,
  `usuario_id_usuario` int DEFAULT NULL,
  `proveedor_id_proveedor` int DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_movimiento`),
  KEY `fk_movimiento_inventario_idx` (`inventario_id_inventario`),
  KEY `fk_movimiento_usuario_idx` (`usuario_id_usuario`),
  KEY `fk_movimiento_proveedor_idx` (`proveedor_id_proveedor`),
  CONSTRAINT `fk_movimiento_inventario` FOREIGN KEY (`inventario_id_inventario`) REFERENCES `inventario` (`id_inventario`),
  CONSTRAINT `fk_movimiento_proveedor` FOREIGN KEY (`proveedor_id_proveedor`) REFERENCES `proveedor` (`id_proveedor`),
  CONSTRAINT `fk_movimiento_usuario` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=194 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimiento_inventario`
--

LOCK TABLES `movimiento_inventario` WRITE;
/*!40000 ALTER TABLE `movimiento_inventario` DISABLE KEYS */;
INSERT INTO `movimiento_inventario` VALUES (1,6,'ajuste',4,0,4,'Ajuste manual desde edición de inventario',6,NULL,'2026-08-20 20:57:49'),(2,6,'ajuste',3,3,6,'Ajuste manual desde edición de inventario',6,NULL,'2026-08-25 19:48:45'),(3,7,'ajuste',6,0,6,'Ajuste manual desde edición de inventario',6,NULL,'2026-08-25 19:52:43'),(4,7,'salida',1,6,5,'Venta #17',6,NULL,'2026-08-25 19:53:13'),(5,7,'salida',2,5,3,'Venta #18',6,NULL,'2026-08-25 21:07:21'),(6,13,'entrada',9,0,9,'Carga inicial de inventario',6,NULL,'2026-09-01 20:53:15'),(7,12,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-01 20:59:04'),(8,12,'salida',3,10,7,'Venta #19',6,NULL,'2026-09-01 21:00:34'),(9,15,'entrada',5,0,5,'Carga inicial de inventario',6,NULL,'2026-09-01 21:15:01'),(10,15,'ajuste',7,5,12,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-01 21:15:33'),(11,15,'ajuste',3,12,9,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-01 21:39:00'),(12,7,'entrada',8,3,11,'proveedor',6,NULL,'2026-09-01 21:39:42'),(13,12,'salida',5,7,2,'Venta #20',6,NULL,'2026-09-01 21:47:20'),(14,15,'salida',5,9,4,'Venta #21',6,NULL,'2026-09-01 21:49:42'),(15,15,'entrada',10,4,14,'proveedor',6,NULL,'2026-09-01 21:52:58'),(16,12,'entrada',15,2,17,'proveedor',6,NULL,'2026-09-01 21:53:31'),(17,12,'salida',2,17,15,'Venta #22',6,NULL,'2026-09-03 21:13:18'),(18,15,'salida',2,14,12,'Venta #22',6,NULL,'2026-09-03 21:13:18'),(19,15,'salida',1,12,11,'Venta #23',6,NULL,'2026-09-04 15:18:19'),(20,12,'salida',1,15,14,'Venta #24',6,NULL,'2026-09-04 15:19:50'),(21,12,'salida',1,14,13,'Venta #25',6,NULL,'2026-09-04 15:23:44'),(22,17,'entrada',10,0,10,'Carga inicial de inventario',6,NULL,'2026-09-04 15:37:29'),(23,17,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 15:38:13'),(24,17,'salida',1,9,8,'Venta #26',6,NULL,'2026-09-04 15:39:08'),(25,15,'salida',1,11,10,'Venta #26',6,NULL,'2026-09-04 15:39:08'),(26,12,'salida',1,13,12,'Venta #26',6,NULL,'2026-09-04 15:39:08'),(27,18,'ajuste',7,0,7,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 15:49:37'),(28,18,'salida',1,7,6,'Venta #27',6,NULL,'2026-09-04 15:50:04'),(29,18,'salida',6,6,0,'Venta #28',6,NULL,'2026-09-04 16:06:37'),(30,12,'salida',8,12,4,'Venta #28',6,NULL,'2026-09-04 16:06:37'),(31,12,'entrada',20,4,24,'proveedor',6,NULL,'2026-09-04 19:28:06'),(32,12,'ajuste',5,24,19,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:28:18'),(33,19,'ajuste',10,0,10,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:28:41'),(34,19,'ajuste',10,10,0,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:28:57'),(35,19,'entrada',10,0,10,'provedor',6,NULL,'2026-09-04 19:29:27'),(36,18,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 19:30:02'),(37,19,'ajuste',3,10,7,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:30:20'),(38,18,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:31:02'),(39,15,'salida',1,10,9,'Venta #29',6,NULL,'2026-09-04 19:38:28'),(40,12,'salida',2,19,17,'Venta #30',6,NULL,'2026-09-04 19:39:26'),(41,17,'salida',3,8,5,'Venta #30',6,NULL,'2026-09-04 19:39:26'),(42,15,'salida',3,9,6,'Venta #30',6,NULL,'2026-09-04 19:39:26'),(43,15,'entrada',10,6,16,'proveedor',6,NULL,'2026-09-04 19:41:02'),(44,20,'entrada',8,0,8,'proveedor',6,NULL,'2026-09-04 19:41:19'),(45,17,'entrada',5,5,10,'proveedor',6,NULL,'2026-09-04 19:41:35'),(46,17,'ajuste',1,10,9,'Ajuste manual desde edición de inventario',6,NULL,'2026-09-04 19:41:44'),(47,18,'entrada',10,9,19,'proveedor',6,NULL,'2026-09-04 19:43:40'),(48,18,'salida',1,19,18,'Venta #31',6,NULL,'2026-09-04 19:44:07'),(49,19,'salida',1,7,6,'Venta #32',6,NULL,'2026-09-04 20:02:14'),(50,12,'salida',2,17,15,'Venta #32',6,NULL,'2026-09-04 20:02:14'),(51,12,'salida',1,15,14,'Venta #33',6,NULL,'2026-09-04 20:06:45'),(52,19,'salida',1,6,5,'Venta #33',6,NULL,'2026-09-04 20:06:45'),(53,17,'salida',1,9,8,'Venta #34',6,NULL,'2026-09-04 20:09:20'),(54,12,'salida',2,14,12,'Venta #34',6,NULL,'2026-09-04 20:09:20'),(55,18,'salida',2,18,16,'Venta #35',6,NULL,'2026-09-04 20:11:45'),(56,19,'salida',1,5,4,'Venta #35',6,NULL,'2026-09-04 20:11:45'),(57,20,'salida',1,8,7,'Venta #36',6,NULL,'2026-09-04 20:12:21'),(58,12,'salida',1,12,11,'Venta #36',6,NULL,'2026-09-04 20:12:21'),(59,12,'salida',2,11,9,'Venta #37',6,NULL,'2026-09-04 20:13:55'),(60,19,'entrada',8,4,12,'provedor',6,NULL,'2026-09-04 20:14:17'),(61,12,'salida',1,9,8,'Venta #38',6,NULL,'2026-09-04 20:16:40'),(62,19,'salida',1,12,11,'Venta #38',6,NULL,'2026-09-04 20:16:40'),(63,18,'salida',1,16,15,'Venta #39',6,NULL,'2026-09-04 20:19:38'),(64,20,'salida',1,7,6,'Venta #39',6,NULL,'2026-09-04 20:19:38'),(65,12,'salida',2,8,6,'Venta #40',6,NULL,'2026-09-04 20:21:51'),(66,12,'entrada',6,6,12,'proveedor',6,NULL,'2026-09-04 20:23:29'),(67,18,'salida',1,15,14,'Venta #41',6,NULL,'2026-09-04 20:27:29'),(68,19,'salida',1,11,10,'Venta #41',6,NULL,'2026-09-04 20:27:29'),(69,18,'salida',2,14,12,'Venta #42',6,NULL,'2026-09-04 20:28:14'),(70,12,'salida',1,12,11,'Venta #42',6,NULL,'2026-09-04 20:28:14'),(71,21,'entrada',10,0,10,'provedor',6,NULL,'2026-09-04 20:31:11'),(72,21,'salida',1,10,9,'Venta #43',6,NULL,'2026-09-04 20:31:44'),(73,22,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:34:03'),(74,22,'salida',2,10,8,'Venta #44',6,NULL,'2026-09-04 20:34:36'),(75,23,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:37:18'),(76,23,'salida',5,10,5,'Venta #45',6,NULL,'2026-09-04 20:37:58'),(77,24,'entrada',10,0,10,'provedor',6,NULL,'2026-09-04 20:40:25'),(78,24,'salida',6,10,4,'Venta #46',6,NULL,'2026-09-04 20:41:29'),(79,25,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:44:55'),(80,25,'salida',3,10,7,'Venta #47',6,NULL,'2026-09-04 20:45:46'),(81,26,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:47:22'),(82,26,'salida',5,10,5,'Venta #48',6,NULL,'2026-09-04 20:48:02'),(83,27,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:49:04'),(84,27,'salida',3,10,7,'Venta #49',6,NULL,'2026-09-04 20:49:43'),(85,28,'entrada',10,0,10,'proveedor',6,NULL,'2026-09-04 20:50:51'),(86,28,'salida',2,10,8,'Venta #50',6,NULL,'2026-09-04 20:51:29'),(87,29,'entrada',10,0,10,'provedor',6,NULL,'2026-09-04 20:53:11'),(88,29,'salida',10,10,0,'Venta #51',6,NULL,'2026-09-04 20:54:08'),(89,29,'entrada',10,0,10,'Cancelación venta #51',6,NULL,'2026-09-12 15:37:57'),(90,28,'salida',1,8,7,'Venta #52',6,NULL,'2026-09-12 20:34:11'),(91,28,'entrada',1,7,8,'Cancelación venta #52',6,NULL,'2026-09-12 20:34:17'),(92,22,'salida',2,8,6,'Venta #53',6,NULL,'2026-09-15 19:47:15'),(93,22,'entrada',12,6,18,'provedor',6,NULL,'2026-09-15 20:17:50'),(94,22,'entrada',2,18,20,'Cancelación venta #53',6,NULL,'2026-09-18 21:43:45'),(95,29,'salida',1,10,9,'Venta #56',6,NULL,'2026-09-21 22:49:33'),(96,21,'salida',1,9,8,'Venta #59',6,NULL,'2026-09-22 15:30:35'),(97,29,'salida',1,9,8,'Venta #65',6,NULL,'2026-09-22 16:13:58'),(98,21,'salida',1,8,7,'Venta #66',6,NULL,'2026-09-22 16:15:51'),(99,30,'entrada',10,0,10,'provedor',6,NULL,'2026-09-29 20:22:56'),(100,28,'salida',1,8,7,'robo',6,NULL,'2026-09-29 20:50:50'),(101,21,'entrada',1,7,8,'Cancelación venta #66',6,NULL,'2026-09-29 20:52:38'),(102,30,'salida',1,10,9,'Venta #67',6,NULL,'2026-10-01 17:08:19'),(103,30,'entrada',1,9,10,'Cancelación venta #67',6,NULL,'2026-10-01 17:08:44'),(104,26,'salida',2,5,3,'Venta #68',6,NULL,'2026-10-01 17:38:10'),(105,12,'salida',2,11,9,'Venta #69',6,NULL,'2026-10-01 17:39:04'),(106,15,'salida',6,16,10,'Venta #69',6,NULL,'2026-10-01 17:39:04'),(107,22,'salida',10,20,10,'Venta #70',6,NULL,'2026-10-02 17:18:27'),(108,27,'salida',5,7,2,'Venta #71',6,NULL,'2026-10-02 17:32:23'),(109,31,'entrada',10,0,10,'proveedor',6,NULL,'2026-10-02 17:34:12'),(110,31,'salida',4,10,6,'Venta #72',6,NULL,'2026-10-02 17:35:16'),(111,12,'salida',1,9,8,'Venta #73',6,NULL,'2026-10-02 19:40:34'),(112,22,'salida',3,10,7,'Venta #73',6,NULL,'2026-10-02 19:40:34'),(113,13,'entrada',1,9,10,'Cancelación venta #73',6,NULL,'2026-10-02 20:48:46'),(114,22,'entrada',3,7,10,'Cancelación venta #73',6,NULL,'2026-10-02 20:48:46'),(115,27,'entrada',5,2,7,'Cancelación venta #71',6,NULL,'2026-10-08 15:25:49'),(116,22,'salida',1,10,9,'Venta #74',6,NULL,'2026-10-09 22:09:46'),(117,32,'entrada',10,0,10,'provedor',6,NULL,'2026-10-13 14:58:17'),(118,32,'salida',3,10,7,'Venta #75',6,NULL,'2026-10-13 14:59:28'),(119,22,'entrada',1,9,10,'Cancelación venta #74',6,NULL,'2026-10-13 19:30:04'),(120,28,'entrada',3,7,10,'provedor',6,NULL,'2026-10-13 19:34:38'),(121,34,'entrada',10,0,10,'Carga inicial de inventario',6,NULL,'2026-10-13 20:09:06'),(122,35,'entrada',10,0,10,'provedor',6,NULL,'2026-10-13 20:18:09'),(123,30,'ajuste',2,10,12,'Ajuste manual desde edición de inventario',6,NULL,'2026-10-13 20:19:31'),(124,36,'ajuste',10,0,10,'Ajuste manual desde edición de inventario',6,NULL,'2026-10-13 20:21:31'),(125,32,'salida',1,7,6,'Venta #76',6,NULL,'2026-10-29 00:57:22'),(126,36,'salida',1,10,9,'Venta #77',6,NULL,'2026-10-29 00:58:37'),(127,35,'salida',2,10,8,'Venta #78',6,NULL,'2026-10-29 13:15:32'),(128,37,'entrada',10,0,10,'proveedor',6,NULL,'2026-10-29 15:34:31'),(129,36,'entrada',1,9,10,'Cancelación venta #77',6,NULL,'2026-10-29 15:38:07'),(130,38,'entrada',10,0,10,'provedor',6,NULL,'2026-10-30 16:39:17'),(131,38,'salida',1,10,9,'Venta #79',6,NULL,'2026-10-30 16:40:25'),(132,29,'salida',1,8,7,'Venta #80',6,NULL,'2026-10-30 16:54:16'),(133,39,'entrada',10,0,10,'proveedor',6,NULL,'2026-10-30 16:58:16'),(134,31,'entrada',4,6,10,'compra al proveedor',6,3,'2026-10-31 16:45:12'),(135,31,'salida',2,10,8,'hola vino otro taller',6,3,'2026-10-31 16:45:54'),(136,30,'salida',2,12,10,'hola les mando unas estas dos zapatillas, no eran el talle que pedi ni el diseño, los otros pedidos llegaron correctamente, muchas gracias',6,3,'2026-10-31 16:48:28'),(137,18,'salida',2,12,10,'Hola devuelvo estas dos zapatillas vinieron de otro talle',6,4,'2026-10-31 16:57:22'),(138,39,'salida',1,10,9,'Venta #85',6,NULL,'2026-11-01 02:16:30'),(139,36,'salida',1,10,9,'Venta #86',6,NULL,'2026-11-01 02:34:06'),(140,39,'salida',1,9,8,'Venta #86',6,NULL,'2026-11-01 02:34:06'),(141,22,'salida',1,10,9,'Venta #86',6,NULL,'2026-11-01 02:34:06'),(142,21,'salida',1,8,7,'Venta #86',6,NULL,'2026-11-01 02:34:06'),(143,36,'salida',1,9,8,'Venta #87',6,NULL,'2026-11-01 20:19:39'),(144,22,'salida',1,9,8,'Venta #88',6,NULL,'2026-11-01 20:19:53'),(145,39,'salida',1,8,7,'Venta #89',6,NULL,'2026-11-01 20:20:06'),(146,39,'salida',1,7,6,'Venta #90',6,NULL,'2026-11-02 16:49:25'),(147,36,'salida',1,8,7,'Venta #91',6,NULL,'2026-11-02 16:49:40'),(148,22,'salida',1,8,7,'Venta #92',6,NULL,'2026-11-02 16:49:50'),(149,39,'salida',1,6,5,'Venta #93',6,NULL,'2026-11-02 17:08:40'),(150,36,'salida',1,7,6,'Venta #94',6,NULL,'2026-11-02 17:08:52'),(151,22,'salida',1,7,6,'Venta #95',6,NULL,'2026-11-02 17:09:14'),(152,35,'salida',1,8,7,'Venta #96',6,NULL,'2026-11-02 17:32:31'),(153,39,'salida',1,5,4,'Venta #97',6,NULL,'2026-11-02 17:32:50'),(154,39,'entrada',1,4,5,'Cancelación venta #97',6,NULL,'2026-11-02 17:32:54'),(155,39,'salida',1,5,4,'Venta #98',6,NULL,'2026-11-03 20:22:12'),(156,36,'salida',1,6,5,'Venta #99',6,NULL,'2026-11-03 20:22:33'),(157,12,'entrada',2,8,10,'compra al proveedor',6,5,'2026-11-03 20:27:46'),(158,30,'entrada',2,10,12,'compra al proveedor',6,5,'2026-11-03 20:28:48'),(159,30,'salida',2,12,10,'producto dañado',6,5,'2026-11-03 20:29:17'),(160,39,'salida',1,4,3,'Venta #100',6,NULL,'2026-11-03 20:53:01'),(161,32,'salida',1,6,5,'Venta #100',6,NULL,'2026-11-03 20:53:01'),(162,32,'salida',1,5,4,'Venta #101',6,NULL,'2026-11-12 22:19:50'),(163,35,'salida',1,7,6,'Venta #102',6,NULL,'2026-11-12 22:20:22'),(164,36,'salida',1,5,4,'Venta #103',6,NULL,'2026-11-12 22:20:36'),(165,22,'salida',1,6,5,'Venta #104',6,NULL,'2026-11-12 22:42:39'),(166,35,'salida',1,6,5,'Venta #105',6,NULL,'2026-11-12 22:42:57'),(167,22,'salida',1,5,4,'Venta #106',6,NULL,'2026-11-12 22:43:12'),(168,22,'entrada',1,4,5,'Cancelación venta #106',6,NULL,'2026-11-12 22:43:27'),(169,39,'entrada',4,3,7,'compra al proveedor',6,3,'2026-11-12 22:46:17'),(170,32,'entrada',3,4,7,'provedor',6,3,'2026-11-13 13:30:31'),(171,32,'salida',1,7,6,'vino de otro taller',6,4,'2026-11-13 13:31:27'),(172,24,'entrada',2,4,6,'proveedor',6,3,'2026-11-13 13:41:28'),(173,24,'salida',1,6,5,'robo',6,NULL,'2026-11-13 13:42:51'),(174,24,'salida',1,5,4,'falla de fabrica',6,3,'2026-11-13 13:43:14'),(175,24,'salida',1,4,3,'producto dañado',6,3,'2026-11-13 13:44:34'),(176,24,'salida',1,3,2,'devolucion',6,3,'2026-11-13 13:49:13'),(177,39,'salida',1,7,6,'Venta #107',6,NULL,'2026-11-13 19:39:16'),(178,36,'salida',1,4,3,'Venta #108',6,NULL,'2026-11-13 19:40:02'),(179,32,'salida',1,6,5,'Venta #109',6,NULL,'2026-11-13 19:40:23'),(180,22,'salida',1,5,4,'Venta #110',6,NULL,'2026-11-13 19:40:44'),(181,39,'salida',1,6,5,'Venta #111',6,NULL,'2026-11-13 19:44:31'),(182,32,'salida',1,5,4,'Venta #112',6,NULL,'2026-11-13 19:44:52'),(183,32,'entrada',1,4,5,'Cancelación venta #112',6,NULL,'2026-11-13 19:44:58'),(184,39,'entrada',2,5,7,'compra al proveedor',6,6,'2026-11-13 20:15:50'),(185,39,'salida',2,7,5,'producto dañado',6,6,'2026-11-13 20:17:06'),(186,32,'salida',2,5,3,'Venta #113',6,NULL,'2026-11-14 14:44:16'),(187,39,'salida',1,5,4,'Venta #113',6,NULL,'2026-11-14 14:44:16'),(188,40,'entrada',10,0,10,'compra al proveedor',6,6,'2026-11-14 14:49:23'),(189,39,'salida',1,4,3,'Venta #114',6,NULL,'2026-11-16 16:03:26'),(190,39,'salida',1,3,2,'Venta #115',6,NULL,'2026-11-16 16:10:44'),(191,32,'salida',2,3,1,'Venta #115',6,NULL,'2026-11-16 16:10:44'),(192,32,'salida',1,1,0,'Venta #116',6,NULL,'2026-11-17 20:10:48'),(193,39,'salida',1,2,1,'Venta #116',6,NULL,'2026-11-17 20:10:48');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Administrador','Acceso total al sistema','inactivo'),(2,'Administrador','Acceso total al sistema','inactivo'),(3,'Eduardo','empleado del mes','inactivo'),(4,'Empleado','empleado nuevo','inactivo'),(5,'ariel','profesor','inactivo'),(6,'hector','empleado nuevo','inactivo'),(7,'mbappe','empleado nuevo','activo'),(8,'juan','empleado nuevo','activo'),(9,'milena','empleada','activo'),(10,'fulano2','nuevo empleado','activo'),(11,'pepe','empleado del mes','activo'),(12,'falcioni','nuevo empleado','activo'),(13,'patricio','nuevo empleado','activo'),(14,'vasco','nuevo administrador','activo'),(15,'killmer','empleado nuevo','activo');
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
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_has_modulo`
--

LOCK TABLES `perfil_has_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_has_modulo` DISABLE KEYS */;
INSERT INTO `perfil_has_modulo` VALUES (1,1,'dashboard'),(2,1,'productos'),(3,1,'clientes'),(4,1,'inventario'),(5,1,'ventas'),(6,1,'dashboard'),(7,1,'productos'),(8,1,'clientes'),(9,1,'inventario'),(10,1,'ventas'),(11,3,'dashboard'),(12,3,'clientes'),(13,4,'productos'),(14,4,'clientes'),(15,5,'dashboard'),(16,5,'productos'),(17,5,'clientes'),(20,1,'usuarios'),(21,1,'perfiles'),(26,6,'dashboard'),(27,6,'productos'),(28,6,'clientes'),(29,6,'inventario'),(30,6,'ventas'),(33,8,'dashboard'),(34,8,'clientes'),(36,7,'dashboard'),(37,7,'clientes'),(45,9,'dashboard'),(46,9,'productos'),(47,9,'clientes'),(48,9,'inventario'),(49,9,'ventas'),(50,10,'clientes'),(51,1,'promociones'),(52,1,'reportes'),(53,11,'dashboard'),(54,11,'productos'),(55,11,'clientes'),(56,11,'inventario'),(57,11,'ventas'),(58,1,'auditoria'),(60,12,'dashboard'),(61,12,'productos'),(62,12,'clientes'),(63,12,'inventario'),(64,12,'ventas'),(65,12,'promociones'),(66,12,'reportes'),(67,13,'clientes'),(68,13,'inventario'),(69,14,'dashboard'),(70,14,'productos'),(71,14,'clientes'),(72,14,'inventario'),(73,14,'ventas'),(74,1,'configuraciones'),(75,15,'dashboard'),(76,15,'productos'),(77,15,'clientes'),(78,15,'inventario'),(79,15,'ventas'),(80,15,'promociones'),(81,1,'proveedores'),(82,1,'caja');
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `persona`
--

LOCK TABLES `persona` WRITE;
/*!40000 ALTER TABLE `persona` DISABLE KEYS */;
INSERT INTO `persona` VALUES (21,'daniela','villalba',41176704,'danielavillalba379@gmail.com','3704713654',NULL,NULL),(22,'dani','villa',33323225,'danivillalba277@gmail.com','23355663',NULL,NULL),(23,'ariel','montenegro',402332454,'montenegroariel@gmail.com','3704442233',NULL,NULL),(24,'milena','vera',20211223,'milenaverarenaut18@gmail.com','233443344',NULL,NULL),(25,'gabriela','vera',30455932,'gabrielabetianavera@gmail.com','3704553222',NULL,NULL),(26,'Hector','Villalba',46154857,'villalbahector257@gmail.com','3704219257',NULL,NULL),(27,'alan','ramirez',46394081,'sebalr4m@gmail.com','3704875949',NULL,NULL),(28,'alexander','alarcon',42037330,'alexanderalarcon949@gmail.com','3718463858',NULL,NULL),(29,'facundo','esquivel',44982636,'facundoesquivel03@gmail.com','3704245651',NULL,NULL),(30,'santiago','mora',41415458,'scdm0407@gmail.com','3704784457',NULL,NULL),(31,'gonzalo','gauna',45901768,'gg10exequiel@gmail.com','3705046143',NULL,NULL),(32,'lujan','benitez',45903304,'lub899176@gmail.com','3704819925',NULL,NULL),(33,'marcos','kayser',46321944,'marquitosk05@gmail.com','3704526038',NULL,NULL),(34,'patricio','sosa',45899843,'sosapatricio2025@gmail.com','370506554',NULL,NULL),(35,'erick','britez',45901294,'erikbrite@gmail.com','3704564622',NULL,NULL),(36,'tobias','soto',4597103,'sototobias855@gmail.com','3704048734',NULL,NULL),(37,'miguel','romero',43329003,'miguelangelromero2o1553@gmail.com','3704544014',NULL,NULL),(38,'lucia','davis',46388971,'lucydavis144@gmail.com','3704960038',NULL,NULL),(39,'tobias','almiron',46065755,'tobiias398@gmail.com','3704001122',NULL,NULL),(40,'juan','minujin',38388484,'juan@minujin.com','23344556667',NULL,NULL);
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
  `imagen` varchar(255) DEFAULT NULL,
  `tipodezapatillas` varchar(45) DEFAULT NULL,
  `precio` int DEFAULT NULL,
  `talle_id_talle` int NOT NULL,
  `marca_id_marca` int NOT NULL,
  `color_id_color` int DEFAULT NULL,
  `categoria_id_categoria` int NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `codigo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  UNIQUE KEY `uq_producto_codigo` (`codigo`),
  KEY `fk_productos_talle1_idx` (`talle_id_talle`),
  KEY `fk_productos_marca1_idx` (`marca_id_marca`),
  KEY `fk_productos_color1_idx` (`color_id_color`),
  KEY `fk_producto_categoria1_idx` (`categoria_id_categoria`),
  CONSTRAINT `fk_producto_categoria1` FOREIGN KEY (`categoria_id_categoria`) REFERENCES `categoria` (`id_categoria`),
  CONSTRAINT `fk_productos_color1` FOREIGN KEY (`color_id_color`) REFERENCES `color` (`id_color`),
  CONSTRAINT `fk_productos_marca1` FOREIGN KEY (`marca_id_marca`) REFERENCES `marca` (`id_marca`),
  CONSTRAINT `fk_productos_talle1` FOREIGN KEY (`talle_id_talle`) REFERENCES `talle` (`id_talle`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (37,'airmax','addads',NULL,'deportiva',3333,15,16,14,14,0,NULL),(38,'topper','',NULL,'correr',30000,16,16,15,16,0,NULL),(39,'topper','lhkgjhfgjjhjg',NULL,'deportiva',50000,16,20,14,16,1,NULL),(40,'diadora','msamsmadm',NULL,'urbana',40000,16,13,16,14,0,NULL),(41,'airmax','zapatillas para correr',NULL,'running',70000,18,16,16,14,1,'000000125'),(42,'adidasmagic','zapatillas nuevas',NULL,'urbana',60000,18,15,14,15,0,NULL),(43,'nike','',NULL,'casual',60000,16,16,13,14,1,NULL),(44,'ojotas nike','mmm','prod_2b4083cf8a87be7e.jpg','playeras',10000,15,12,15,17,1,NULL),(45,'vans','',NULL,'training',50000,21,18,15,18,1,NULL),(46,'vans','',NULL,'correr',50000,20,18,15,14,1,NULL),(47,'pumas','',NULL,'deportiva',40000,21,17,20,19,1,NULL),(48,'topper','',NULL,'urbana',45000,17,20,16,17,1,NULL),(49,'nike messi','',NULL,'fubol',30000,20,16,16,16,0,NULL),(50,'air max nike','','prod_36f1b98f63bb9cf8.jpg','casual',20000,25,16,16,17,1,'NIKE-SKU-000'),(51,'top700','',NULL,'casual',30000,20,16,16,17,1,NULL),(52,'heelies','','prod_1d10eb8f51d100a6.webp','casual',20000,16,21,16,17,1,NULL),(53,'rebook','',NULL,'deportiva',30000,19,18,15,19,1,NULL),(54,'knu','',NULL,'urbana',20000,17,18,16,17,1,NULL),(55,'maracaibo','',NULL,'casual',20000,17,20,16,17,0,NULL),(56,'air','',NULL,'deportiva',10000,19,16,15,19,0,NULL),(57,'nike pirane','',NULL,'casual',20000,26,16,17,17,0,NULL),(58,'vans nueva','nuevo producto',NULL,'skate',20000,17,22,20,23,1,'212334323'),(59,'rebook','para caminar','prod_e70c6ec20f1d3e6a.jpg','casual',30000,16,21,16,17,1,NULL),(60,'air jornan 1','zapa urbana','prod_c88408979a430a1d.jpg','urbana',50000,16,16,16,24,1,'000000124'),(61,'topper t350','para correr',NULL,'deportiva',30000,26,20,19,19,1,NULL),(62,'air max 90','zapatilla nueva','prod_dbade060984b0af8.jpg','casual',60000,18,16,16,17,1,'023933112'),(63,'air max 85','zapa nuevas',NULL,'deportiva',50000,18,16,15,19,1,'223300000'),(64,'vans era','producto nuevo de vans','prod_ba3e04f74666fb78.jpg','deportiva',600000,16,18,NULL,16,1,NULL),(65,'nike vomero','zapatilla vomero','prod_4efdc22b5d7194b2.jpg','running',80000,18,12,NULL,14,1,'003943222'),(66,'adidas superstar','zapatilla adidas','prod_abe83361c68ece01.jpg','casual',70000,16,15,NULL,17,1,'000000123'),(67,'nike pegasus','','prod_4f77b180c369c9a0.jpg','casual',80000,27,12,NULL,17,1,'002392303');
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
INSERT INTO `productos_has_promocion` VALUES (38,6),(47,7),(53,7),(56,7),(39,8),(49,8),(57,8),(44,9),(57,9),(43,10);
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promocion`
--

LOCK TABLES `promocion` WRITE;
/*!40000 ALTER TABLE `promocion` DISABLE KEYS */;
INSERT INTO `promocion` VALUES (6,'verano','promo de ferano','porcentaje',30.00,NULL,'2026-03-31 03:00:00','2026-04-06 03:00:00',0),(7,'primavera','tempara de flores','porcentaje',35.00,NULL,'2026-09-13 03:00:00','2026-09-23 03:00:00',0),(8,'primavera-verano','flores','monto_fijo',NULL,20000.00,'2026-09-14 03:00:00','2026-10-01 03:00:00',0),(9,'liquidacion de verano','bajas ventas','porcentaje',30.00,NULL,'2026-09-29 03:00:00','2028-10-20 03:00:00',1),(10,'prueba de hoy','probando promocion','monto_fijo',NULL,10000.00,'2026-09-29 03:00:00','2026-10-29 03:00:00',1);
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
  `nombre` varchar(100) NOT NULL,
  `telefono` varchar(30) DEFAULT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_proveedor`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proveedor`
--

LOCK TABLES `proveedor` WRITE;
/*!40000 ALTER TABLE `proveedor` DISABLE KEYS */;
INSERT INTO `proveedor` VALUES (3,'Hector villalba','3704219257','formosa argentina','villalbahector257@gmail.com',1),(4,'Milena vera','3774320392','formosa argentina','milenaverarenaut18@gmail.com',1),(5,'milton chavez','384940303','formosa argentina','chavezmilton082@gmail.com',1),(6,'ariel montengro','3748393332','formosa argentina','montenegroariel@gmail.com',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `talle`
--

LOCK TABLES `talle` WRITE;
/*!40000 ALTER TABLE `talle` DISABLE KEYS */;
INSERT INTO `talle` VALUES (15,'43',''),(16,'45',''),(17,'43',''),(18,'44',''),(19,'42',NULL),(20,'41',NULL),(21,'40',NULL),(22,'39',NULL),(23,'38',NULL),(24,'37',NULL),(25,'36',NULL),(26,'35',NULL),(27,'46',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (6,'villalbahector257@gmail.com','$2y$10$TmMINXKhWPEKharI/j5rOOzOUsTgGzf0VBq/Vi0mt4YHX.ciz3rEm','admin',NULL,NULL,1,'activo'),(7,'roman@gmail.com','$2y$10$dGymR12vK/ZSlAx.ozzjC.NfEiXpi/qvqLRVUArZcbT3iwGqE/4wO','empleado',NULL,NULL,6,'inactivo'),(8,'riquelme@gmail.com','$2y$10$Bob.rMDR6rEDnFkhYYKmAunlud6Iigbp4xL5A00QYwhE1HLDAa5Qi','admin',NULL,NULL,1,'inactivo'),(9,'hv6282533@gmail.com','$2y$10$mq/tMI4ZzqPjcov5b71JbuWRtsZKLGWYbJ0rm19Pc/AhZPldTyvx6','admin',NULL,NULL,1,'inactivo'),(10,'dev@gmail.com','$2y$10$Z9cC4ihfVuDNRj3MXw.SAe8/efr/wJxRr9QUB9icaZA8iv6AgbONK','admin',NULL,NULL,1,'inactivo'),(11,'daniel@gmail.com','$2y$10$Jp20cnS/ppDcdUjSFtY8vuBOmysyECayTsZJ33iUMfCDkh1EJQhgi','empleado',NULL,NULL,1,'inactivo'),(12,'agustin@gmail.com','$2y$10$yMGHB8U5uxyXtsdgzvI5UeLKFBqusY7WAoiIia49BmiUnTyAxvaTO','admin',NULL,NULL,1,'inactivo'),(13,'ariel@gmail.com','$2y$10$eDQboIYOq290vOp4oPjuCuPZa6iLqksr9Nw9Om6PO8laMTJi19vUi','admin',NULL,NULL,1,'inactivo'),(14,'mbappe@gmail.com','$2y$10$nRrjQpDYsYJXmF4VFBSQzeE17hVfpkk/lV3rLiclFd/ebxAEMK406','empleado',NULL,NULL,7,'inactivo'),(15,'messi@gmail.com','$2y$10$8JH17FgV42Zfd4SxjR/q1OC0.JvQwJQqdsIFWiYRRpakIjxrSqu9y','empleado',NULL,NULL,1,'inactivo'),(16,'merentiel@gmail.com','$2y$10$wq.sxwpfuSUiPL06T4A3VeGdxCj/oTa2uB3f6mGA6CagKlmehiGyu','empleado',NULL,NULL,8,'activo'),(17,'milena@gmail.com','$2y$10$KLYsv1vUC.hWd0nqz1o4P.mOudxKviPQXMBIdDGl.DjxgDfDFXwD6','empleado',NULL,NULL,NULL,'activo'),(18,'fulano@gmail.com','$2y$10$nGfPTAuEth6MgCAyHFaQyebiLdGw0wra9zlDS4kBtCdTEI2yG.ldG','admin',NULL,NULL,NULL,'inactivo'),(19,'fulano2@gmail.com','$2y$10$hBh0RTyXf4NaQBXabKrFBenMk8izCHUEyR4CxmBvSSXNe3kGABTDq','admin',NULL,NULL,10,'inactivo'),(20,'pepito@gmail.com','$2y$10$x51Mp302xaaZWt8DplmMl.DdDSHneiph96VjJtmoPUD4HtHHzuaZK','empleado',NULL,NULL,NULL,'inactivo'),(21,'haland@gmail.com','$2y$10$/x6kxnk2IpNrzvcMWWqGv.fsiiHg28h.gYdOFd4DR1XNvThc7WT8m','empleado',NULL,NULL,NULL,'inactivo'),(22,'gabi@gmail.com','$2y$10$Yb4fidjDtKdPH/Nd24VK6u5HB5dLQL2pOUYrI7NqG/xC9bRBJLkaO','admin',NULL,NULL,NULL,'inactivo'),(23,'pepe@gmail.com','$2y$10$h7Ho196FkQ0rcQ5jDqC9Uu4a/uSjg0HAvloLfGDgzlAmTeWS2j/CS','empleado',NULL,NULL,11,'activo'),(24,'milton@gmail.com','$2y$10$Wv4O3u5hJZ/Ce1WhgOi2XeX3tUOR6Qi.2sVZp1kNvv0ByD.GWUsF2','admin',NULL,NULL,NULL,'activo'),(25,'falcioni@gmail.com','$2y$10$bYBUryhSuweWwmpNA3eXuOFdwmghOzz0e3s15QoBkBlEiJaqj0.kS','empleado','f7bd4a8c94c381d552303c3026ce4edb213956e7e3042de12b9d9155144d12be983904240ba4d4c91acb857eadf4744f849c','2026-10-29 21:59:28',12,'activo'),(26,'patricio@gmail.com','$2y$10$PD4PQmt3s6xg.ebnMnBY1ObGnUzkBjaI7nvP.oRBw2z7QHaPrJt1m','empleado',NULL,NULL,13,'activo'),(27,'vasco@gmail.com','$2y$10$FIzt5S/qnVIgp8dOqQLf9OCF5WQk.4e1SfTwDqXIQ6zjhio8jKqDi','admin',NULL,NULL,14,'activo'),(28,'killmerdev@gmail.com','$2y$10$WUyw6aECAwXFwT.Y/jhqUegzif1ugHW5/oIJ4H9R8VyfvqK59p4N2','empleado','4ab8d23ce1c73506b7d386590438c90abef4cac70e0dcb9c32c5f771daa7b740aff1b8bfd9b1e407d0d7a2a56a6cee6d643e','2026-10-29 22:03:11',15,'activo');
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
  `caja_id` int DEFAULT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `fk_venta_cliente1_idx` (`cliente_idcliente`),
  KEY `fk_venta_empleado1_idx` (`empleado_id_empleado`),
  KEY `fk_venta_caja_idx` (`caja_id`),
  CONSTRAINT `fk_venta_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`id_caja`),
  CONSTRAINT `fk_venta_cliente1` FOREIGN KEY (`cliente_idcliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `fk_venta_empleado1` FOREIGN KEY (`empleado_id_empleado`) REFERENCES `empleado` (`id_empleado`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
INSERT INTO `venta` VALUES (16,'2026-08-20 20:58:12',3333.00,'Tarjeta Crédito','completada',NULL,NULL,NULL),(17,'2026-08-25 19:53:13',6050.00,'Efectivo','completada',22,NULL,NULL),(18,'2026-08-25 21:07:21',12100.00,'Tarjeta Débito','completada',22,NULL,NULL),(19,'2026-09-01 21:00:34',217800.00,'Efectivo','completada',19,NULL,NULL),(20,'2026-09-01 21:47:20',363000.00,'Efectivo','completada',23,NULL,NULL),(21,'2026-09-01 21:49:42',60500.00,'Efectivo','completada',23,NULL,NULL),(22,'2026-09-03 21:13:18',169400.00,'Efectivo','completada',21,NULL,NULL),(23,'2026-09-04 15:18:19',12100.00,'Efectivo','completada',23,NULL,NULL),(24,'2026-09-04 15:19:50',72600.00,'Efectivo','completada',21,NULL,NULL),(25,'2026-09-04 15:23:44',60000.00,'Efectivo','completada',21,NULL,NULL),(26,'2026-09-04 15:39:08',120000.00,'Transferencia','completada',22,NULL,NULL),(27,'2026-09-04 15:50:03',50000.00,'Transferencia','completada',19,NULL,NULL),(28,'2026-09-04 16:06:37',780000.00,'Efectivo','completada',24,NULL,NULL),(29,'2026-09-04 19:38:28',10000.00,'Efectivo','completada',24,NULL,NULL),(30,'2026-09-04 19:39:26',300000.00,'Tarjeta Crédito','completada',19,NULL,NULL),(31,'2026-09-04 19:44:07',50000.00,'Efectivo','completada',21,NULL,NULL),(32,'2026-09-04 20:02:14',160000.00,'Efectivo','completada',25,NULL,NULL),(33,'2026-09-04 20:06:45',100000.00,'Transferencia','completada',26,NULL,NULL),(34,'2026-09-04 20:09:20',170000.00,'Transferencia','completada',27,NULL,NULL),(35,'2026-09-04 20:11:45',140000.00,'Transferencia','completada',28,NULL,NULL),(36,'2026-09-04 20:12:21',105000.00,'Efectivo','completada',28,NULL,NULL),(37,'2026-09-04 20:13:55',120000.00,'Efectivo','completada',29,NULL,NULL),(38,'2026-09-04 20:16:40',100000.00,'Transferencia','completada',30,NULL,NULL),(39,'2026-09-04 20:19:38',95000.00,'Efectivo','completada',31,NULL,NULL),(40,'2026-09-04 20:21:51',120000.00,'Transferencia','completada',32,NULL,NULL),(41,'2026-09-04 20:27:29',90000.00,'Tarjeta Débito','completada',33,NULL,NULL),(42,'2026-09-04 20:28:14',160000.00,'Tarjeta Débito','completada',34,NULL,NULL),(43,'2026-09-04 20:31:44',30000.00,'Transferencia','completada',35,NULL,NULL),(44,'2026-09-04 20:34:36',40000.00,'Efectivo','completada',36,NULL,NULL),(45,'2026-09-04 20:37:58',150000.00,'Transferencia','completada',37,NULL,NULL),(46,'2026-09-04 20:41:29',120000.00,'Transferencia','completada',25,NULL,NULL),(47,'2026-09-04 20:45:46',90000.00,'Transferencia','completada',26,NULL,NULL),(48,'2026-09-04 20:48:02',100000.00,'Efectivo','completada',27,NULL,NULL),(49,'2026-09-04 20:49:43',60000.00,'Efectivo','completada',28,NULL,NULL),(50,'2026-09-04 20:51:29',20000.00,'Transferencia','completada',29,NULL,NULL),(51,'2026-09-04 20:54:08',200000.00,'Tarjeta Crédito','cancelada',31,NULL,NULL),(52,'2026-09-12 20:34:11',10000.00,'Efectivo','cancelada',25,NULL,NULL),(53,'2026-09-15 19:47:15',40000.00,'Transferencia','cancelada',22,NULL,NULL),(56,'2026-09-21 22:49:33',20000.00,'Efectivo','completada',21,NULL,NULL),(59,'2026-09-22 15:30:35',10000.00,'Efectivo','completada',21,NULL,NULL),(65,'2026-09-22 16:13:58',0.00,'Efectivo','completada',21,NULL,NULL),(66,'2026-09-22 16:15:51',10000.00,'Efectivo','cancelada',21,NULL,NULL),(67,'2026-10-01 17:08:19',20000.00,'Efectivo','cancelada',25,NULL,NULL),(68,'2026-10-01 17:38:10',40000.00,'Tarjeta Débito','completada',26,NULL,NULL),(69,'2026-10-01 17:39:04',170000.00,'Transferencia','completada',38,NULL,NULL),(70,'2026-10-02 17:18:27',200000.00,'Transferencia','completada',21,NULL,NULL),(71,'2026-10-02 17:32:23',100000.00,'Transferencia','cancelada',30,NULL,NULL),(72,'2026-10-02 17:35:16',120000.00,'Efectivo','completada',27,NULL,NULL),(73,'2026-10-02 19:40:34',110000.00,'Efectivo','cancelada',25,NULL,NULL),(74,'2026-10-09 22:09:46',20000.00,'Efectivo','cancelada',26,NULL,NULL),(75,'2026-10-13 14:59:28',150000.00,'Tarjeta Débito','completada',25,NULL,NULL),(76,'2026-10-29 00:57:22',50000.00,'Efectivo','completada',25,NULL,NULL),(77,'2026-10-29 00:58:37',50000.00,'Tarjeta Débito','cancelada',26,NULL,NULL),(78,'2026-10-29 13:15:32',120000.00,'Efectivo','completada',21,NULL,NULL),(79,'2026-10-30 16:40:25',80000.00,'Efectivo','completada',25,NULL,NULL),(80,'2026-10-30 16:54:16',14000.00,'Efectivo','completada',21,NULL,NULL),(81,'2026-11-01 02:12:37',0.00,'Efectivo','completada',21,NULL,1),(82,'2026-11-01 02:12:45',0.00,'Efectivo','completada',21,NULL,1),(83,'2026-11-01 02:12:59',0.00,'Efectivo','completada',21,NULL,1),(84,'2026-11-01 02:13:21',0.00,'Efectivo','completada',25,NULL,1),(85,'2026-11-01 02:16:30',70000.00,'Tarjeta Débito','completada',21,NULL,1),(86,'2026-11-01 02:34:06',170000.00,'Efectivo','completada',26,NULL,2),(87,'2026-11-01 20:19:39',50000.00,'Efectivo','completada',19,NULL,3),(88,'2026-11-01 20:19:53',20000.00,'Tarjeta Crédito','completada',23,NULL,3),(89,'2026-11-01 20:20:06',70000.00,'Transferencia','completada',33,NULL,3),(90,'2026-11-02 16:49:25',70000.00,'Tarjeta Débito','completada',25,NULL,4),(91,'2026-11-02 16:49:40',50000.00,'Transferencia','completada',20,NULL,4),(92,'2026-11-02 16:49:50',20000.00,'Efectivo','completada',NULL,NULL,4),(93,'2026-11-02 17:08:40',70000.00,'Efectivo','completada',25,NULL,5),(94,'2026-11-02 17:08:52',50000.00,'Tarjeta Débito','completada',21,NULL,5),(95,'2026-11-02 17:09:14',20000.00,'Transferencia','completada',19,NULL,5),(96,'2026-11-02 17:32:31',60000.00,'Efectivo','completada',25,NULL,6),(97,'2026-11-02 17:32:50',70000.00,'Transferencia','cancelada',26,NULL,6),(98,'2026-11-03 20:22:12',70000.00,'Efectivo','completada',26,NULL,7),(99,'2026-11-03 20:22:33',50000.00,'Tarjeta Crédito','completada',20,NULL,7),(100,'2026-11-03 20:53:01',120000.00,'Efectivo','completada',25,NULL,8),(101,'2026-11-12 22:19:50',50000.00,'Efectivo','completada',25,NULL,9),(102,'2026-11-12 22:20:22',60000.00,'Tarjeta Débito','completada',21,NULL,9),(103,'2026-11-12 22:20:36',50000.00,'Transferencia','completada',19,NULL,9),(104,'2026-11-12 22:42:39',20000.00,'Transferencia','completada',25,NULL,10),(105,'2026-11-12 22:42:57',60000.00,'Tarjeta Crédito','completada',21,NULL,10),(106,'2026-11-12 22:43:12',20000.00,'Efectivo','cancelada',19,NULL,10),(107,'2026-11-13 19:39:16',70000.00,'Transferencia','completada',25,NULL,11),(108,'2026-11-13 19:40:02',50000.00,'Efectivo','completada',21,NULL,11),(109,'2026-11-13 19:40:23',50000.00,'Tarjeta Crédito','completada',19,NULL,11),(110,'2026-11-13 19:40:44',20000.00,'Tarjeta Crédito','completada',26,NULL,11),(111,'2026-11-13 19:44:31',70000.00,'Efectivo','completada',25,NULL,12),(112,'2026-11-13 19:44:52',50000.00,'Tarjeta Débito','cancelada',26,NULL,12),(113,'2026-11-14 14:44:16',170000.00,'Tarjeta Débito','completada',26,NULL,14),(114,'2026-11-16 16:03:26',70000.00,'Efectivo + Transferencia','completada',25,NULL,15),(115,'2026-11-16 16:10:44',170000.00,'Efectivo + Transferencia','completada',26,NULL,16),(116,'2026-11-17 20:10:48',120000.00,'Efectivo + Transferencia','completada',25,NULL,17);
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta_pago`
--

DROP TABLE IF EXISTS `venta_pago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta_pago` (
  `id_venta_pago` int NOT NULL AUTO_INCREMENT,
  `venta_id` int NOT NULL,
  `metodo_de_pago` varchar(45) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_venta_pago`),
  KEY `idx_venta_pago_venta` (`venta_id`),
  CONSTRAINT `fk_venta_pago_venta` FOREIGN KEY (`venta_id`) REFERENCES `venta` (`id_venta`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta_pago`
--

LOCK TABLES `venta_pago` WRITE;
/*!40000 ALTER TABLE `venta_pago` DISABLE KEYS */;
INSERT INTO `venta_pago` VALUES (1,16,'Tarjeta Crédito',3333.00),(2,17,'Efectivo',6050.00),(3,18,'Tarjeta Débito',12100.00),(4,19,'Efectivo',217800.00),(5,20,'Efectivo',363000.00),(6,21,'Efectivo',60500.00),(7,22,'Efectivo',169400.00),(8,23,'Efectivo',12100.00),(9,24,'Efectivo',72600.00),(10,25,'Efectivo',60000.00),(11,26,'Transferencia',120000.00),(12,27,'Transferencia',50000.00),(13,28,'Efectivo',780000.00),(14,29,'Efectivo',10000.00),(15,30,'Tarjeta Crédito',300000.00),(16,31,'Efectivo',50000.00),(17,32,'Efectivo',160000.00),(18,33,'Transferencia',100000.00),(19,34,'Transferencia',170000.00),(20,35,'Transferencia',140000.00),(21,36,'Efectivo',105000.00),(22,37,'Efectivo',120000.00),(23,38,'Transferencia',100000.00),(24,39,'Efectivo',95000.00),(25,40,'Transferencia',120000.00),(26,41,'Tarjeta Débito',90000.00),(27,42,'Tarjeta Débito',160000.00),(28,43,'Transferencia',30000.00),(29,44,'Efectivo',40000.00),(30,45,'Transferencia',150000.00),(31,46,'Transferencia',120000.00),(32,47,'Transferencia',90000.00),(33,48,'Efectivo',100000.00),(34,49,'Efectivo',60000.00),(35,50,'Transferencia',20000.00),(36,51,'Tarjeta Crédito',200000.00),(37,52,'Efectivo',10000.00),(38,53,'Transferencia',40000.00),(39,56,'Efectivo',20000.00),(40,59,'Efectivo',10000.00),(41,65,'Efectivo',0.00),(42,66,'Efectivo',10000.00),(43,67,'Efectivo',20000.00),(44,68,'Tarjeta Débito',40000.00),(45,69,'Transferencia',170000.00),(46,70,'Transferencia',200000.00),(47,71,'Transferencia',100000.00),(48,72,'Efectivo',120000.00),(49,73,'Efectivo',110000.00),(50,74,'Efectivo',20000.00),(51,75,'Tarjeta Débito',150000.00),(52,76,'Efectivo',50000.00),(53,77,'Tarjeta Débito',50000.00),(54,78,'Efectivo',120000.00),(55,79,'Efectivo',80000.00),(56,80,'Efectivo',14000.00),(57,81,'Efectivo',0.00),(58,82,'Efectivo',0.00),(59,83,'Efectivo',0.00),(60,84,'Efectivo',0.00),(61,85,'Tarjeta Débito',70000.00),(62,86,'Efectivo',170000.00),(63,87,'Efectivo',50000.00),(64,88,'Tarjeta Crédito',20000.00),(65,89,'Transferencia',70000.00),(66,90,'Tarjeta Débito',70000.00),(67,91,'Transferencia',50000.00),(68,92,'Efectivo',20000.00),(69,93,'Efectivo',70000.00),(70,94,'Tarjeta Débito',50000.00),(71,95,'Transferencia',20000.00),(72,96,'Efectivo',60000.00),(73,97,'Transferencia',70000.00),(74,98,'Efectivo',70000.00),(75,99,'Tarjeta Crédito',50000.00),(76,100,'Efectivo',120000.00),(77,101,'Efectivo',50000.00),(78,102,'Tarjeta Débito',60000.00),(79,103,'Transferencia',50000.00),(80,104,'Transferencia',20000.00),(81,105,'Tarjeta Crédito',60000.00),(82,106,'Efectivo',20000.00),(83,107,'Transferencia',70000.00),(84,108,'Efectivo',50000.00),(85,109,'Tarjeta Crédito',50000.00),(86,110,'Tarjeta Crédito',20000.00),(87,111,'Efectivo',70000.00),(88,112,'Tarjeta Débito',50000.00),(89,113,'Tarjeta Débito',170000.00),(128,114,'Efectivo',10000.00),(129,114,'Transferencia',60000.00),(130,115,'Efectivo',70000.00),(131,115,'Transferencia',100000.00),(132,116,'Efectivo',80000.00),(133,116,'Transferencia',40000.00);
/*!40000 ALTER TABLE `venta_pago` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-18 13:33:56
