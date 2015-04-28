# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.21-1~dotdeb.1)
# Database: tunat
# Generation Time: 2015-04-05 10:59:52 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ads
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ads`;

CREATE TABLE `ads` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `park_id` int(11) unsigned NOT NULL,
  `car_category` int(3) unsigned NOT NULL,
  `car_make` int(3) NOT NULL,
  `car_model` varchar(50) NOT NULL,
  `year_start` int(3) unsigned NOT NULL,
  `year_end` int(3) unsigned NOT NULL,
  `part_name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `price` float(10,2) unsigned NOT NULL,
  `currency` varchar(3) NOT NULL,
  `status` enum('pending','ok','expired') NOT NULL DEFAULT 'pending',
  `dateadd` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `images` text NOT NULL,
  `views` int(11) unsigned NOT NULL,
  `contact_displayed` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;

INSERT INTO `ads` (`id`, `park_id`, `car_category`, `car_make`, `car_model`, `year_start`, `year_end`, `part_name`, `description`, `price`, `currency`, `status`, `dateadd`, `updated_at`, `images`, `views`, `contact_displayed`)
VALUES
	(3,1,2,11,'',0,0,'Furtun de injectie','furtun de injectie ce sa protriveste la toate modele de audi a6 din 2009',200.00,'RON','expired','2015-02-23 19:58:04','0000-00-00 00:00:00','a:0:{}',0,0),
	(7,1,2,11,'',0,0,'discuri spate','asdadsd',0.00,'RON','expired','2015-02-24 23:28:01','0000-00-00 00:00:00','a:0:{}',0,0),
	(8,1,2,11,'',0,0,'pompa de circulare','Audi A4 sedan 1.9TDI, sau: piesa e compatibila cu toate modele Audi A4 2001-2005',0.00,'RON','expired','2015-02-24 23:31:19','0000-00-00 00:00:00','a:5:{i:0;s:35:\"333a16e7813edc2548a02b4c00fe5d9530c\";i:1;s:35:\"613215b2fc7bd16d799d849dd9f043a7737\";i:2;s:35:\"66847949459ce0fbd4341cafb856a6ee01f\";i:3;s:35:\"7705466affa2c43acd7c45602ff44d3d878\";i:4;s:35:\"916c82d45abd621e56a019744f12484ab7a\";}',0,0),
	(9,1,2,11,'LX3200',2011,2015,'toba de esapament','Audi A4 sedan 1.9TDI, sau: piesa e compatibila cu toate modele Audi A4 2001-2005',0.00,'RON','ok','2015-03-31 19:48:36','2015-03-31 19:48:36','a:2:{i:0;s:35:\"70798e0fc101a579811ee84de7b77022ec0\";i:1;s:35:\"837d4690a640035f0d0c97e562ea7b212bb\";}',0,0),
	(10,1,2,11,'',0,0,'pompa de presiune pentru container de cablaj','pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4',199.00,'RON','ok','2015-03-01 20:35:43','0000-00-00 00:00:00','a:4:{i:0;s:35:\"345a16e7813edc2548a02b4c00fe5d9530c\";i:1;s:35:\"37247949459ce0fbd4341cafb856a6ee01f\";i:2;s:35:\"638c82d45abd621e56a019744f12484ab7a\";i:3;s:35:\"81179997e1c8bcd71b1284e0da52d5b4aec\";}',0,0),
	(12,1,2,11,'',0,0,'granitura de chiuloasa','garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa',299.00,'RON','ok','2015-03-08 12:15:00','0000-00-00 00:00:00','a:3:{i:0;s:35:\"2438d33e67f3d8a919ff0af63547e6eab8a\";i:1;s:35:\"651f596490e382685ad2c9d40e1d1485a73\";i:2;s:35:\"842b2616b47220db07db2052229c77f1345\";}',0,0),
	(20,1,2,13,'MB2000',2011,2013,'carburant cu turbina de putere','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.',2000.00,'EUR','ok','2015-03-24 23:08:22','0000-00-00 00:00:00','a:0:{}',0,0),
	(21,1,2,13,'MB1500',2009,2011,'garnitura prag de evacuare','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.',1999.00,'RON','ok','2015-03-24 23:11:30','2015-03-31 19:50:50','a:1:{i:0;s:35:\"656d6d968d1f304afba6bd860fc5fad695e\";}',0,0),
	(22,1,2,9,'DF100',2009,2015,'oglinda stanga','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus',20.00,'EUR','ok','2015-03-24 23:13:52','2015-03-31 19:55:20','a:1:{i:0;s:35:\"619967dfe3725dea8285bb251c6f6f7faec\";}',2,1),
	(23,1,2,11,'IV2300, LX',2010,2015,'carlig bara tractare','kxadjfb sjfnslfa godfn fsaopf smpdf',400.00,'EUR','ok','2015-03-26 20:29:37','2015-03-30 23:05:29','a:1:{i:0;s:35:\"583e881595c9bdd7c9f7f0928b7787ed95f\";}',0,0),
	(26,1,2,13,'MB2323',1961,2015,'Oglinda retrovizoare cu comenz','sdgn sgnoifdgniosdngionf gionsgio gniogn sign goidngio sdngiong udbg uigbgi noifgnsidogbiugbfgu noign sdgsdignsdoig ndsoign sdoigndsiogndfoi gnosign iogndso igndfsoig noign doign dfsoignsdgoi ngoindsogin sdgoindsg oinsdoigndsoigndfsiog ndsoign dsoignf doigndfio gndsfoign sdogin sdiognfo ginsdofig',199.00,'USD','ok','2015-03-30 22:45:14','2015-03-30 22:57:27','a:1:{i:0;s:35:\"29744955fd99b09e0844332f2626f5341f3\";}',9,4);

/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table autopark_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `autopark_users`;

CREATE TABLE `autopark_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `park_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `autopark_users` WRITE;
/*!40000 ALTER TABLE `autopark_users` DISABLE KEYS */;

INSERT INTO `autopark_users` (`id`, `user_id`, `park_id`)
VALUES
	(1,1,1),
	(2,2,1);

/*!40000 ALTER TABLE `autopark_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table autoparks
# ------------------------------------------------------------

DROP TABLE IF EXISTS `autoparks`;

CREATE TABLE `autoparks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `tel1` varchar(20) NOT NULL DEFAULT '',
  `tel2` varchar(20) NOT NULL DEFAULT '',
  `tel3` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `autoparks` WRITE;
/*!40000 ALTER TABLE `autoparks` DISABLE KEYS */;

INSERT INTO `autoparks` (`id`, `name`, `email`, `url`, `location`, `description`, `tel1`, `tel2`, `tel3`)
VALUES
	(1,'Dezmemebrari Vali SRL','contact@validez.ro','www.validez.ro','str Slatinei nr 1, Oradea','cea mai tare firma de dezmembrari','747047607','747047607','');

/*!40000 ALTER TABLE `autoparks` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cars_model
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cars_model`;

CREATE TABLE `cars_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(3) NOT NULL,
  `car_make` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cars_model` WRITE;
/*!40000 ALTER TABLE `cars_model` DISABLE KEYS */;

INSERT INTO `cars_model` (`id`, `category_id`, `car_make`)
VALUES
	(9,2,'DAF'),
	(10,2,'Demag'),
	(11,2,'Iveco'),
	(12,2,'Man'),
	(13,2,'Mercedes-Benz'),
	(14,2,'Renault'),
	(15,2,'Opel'),
	(16,1,'test'),
	(17,3,'test'),
	(18,4,'test'),
	(19,5,'test'),
	(20,7,'test');

/*!40000 ALTER TABLE `cars_model` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `category`)
VALUES
	(1,'Dube'),
	(2,'Camioane'),
	(3,'Remorci'),
	(4,'Utilaje Agricole'),
	(5,'Autobuse'),
	(6,'Utilaje Constructii');

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table parts_main_categ
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parts_main_categ`;

CREATE TABLE `parts_main_categ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `parts_main_categ` WRITE;
/*!40000 ALTER TABLE `parts_main_categ` DISABLE KEYS */;

INSERT INTO `parts_main_categ` (`id`, `category`)
VALUES
	(1,'Air conditioning / Heating'),
	(2,'Body'),
	(3,'Brakes'),
	(4,'Dismantling object'),
	(5,'Doors'),
	(6,'Electric / Transmitter / Databox / Sensor'),
	(7,'Engine'),
	(8,'Exhaust/cleaning'),
	(9,'Exterior details'),
	(10,'Front details'),
	(11,'Fuel'),
	(12,'Gear box / Drive axle / Middle axle'),
	(13,'Hydraulic/Traction'),
	(14,'Instruments / Electric switches'),
	(15,'Interior'),
	(16,'Lock / Alarm'),
	(17,'Other'),
	(18,'Rearview Mirror'),
	(19,'Repair sheet metal / Consumables'),
	(20,'Spoilers / Wipers'),
	(21,'Steering wheel / Axle / Lever / Pedal'),
	(22,'Vehicle exterior / Suspension'),
	(23,'Vehicle front / Springs / Steering'),
	(24,'Wheels / Tires / Accessories'),
	(25,'Windows');

/*!40000 ALTER TABLE `parts_main_categ` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table parts_sub_categ
# ------------------------------------------------------------

DROP TABLE IF EXISTS `parts_sub_categ`;

CREATE TABLE `parts_sub_categ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categ_id` int(3) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `parts_sub_categ` WRITE;
/*!40000 ALTER TABLE `parts_sub_categ` DISABLE KEYS */;

INSERT INTO `parts_sub_categ` (`id`, `categ_id`, `category`)
VALUES
	(1,7,'Ax Came'),
	(2,7,'Cylinder Head Fuel'),
	(3,7,'Cam axle housing'),
	(4,7,'Camshaft adjusting valve'),
	(5,7,'Cylinder Head Diesel'),
	(6,7,'Cylinder Head Fuel'),
	(7,7,'Engine Diesel'),
	(8,7,'Alternator bracket / Accessories'),
	(9,8,'Exhaust manifold'),
	(10,8,'Lambda probe'),
	(11,8,'EGR Valve');

/*!40000 ALTER TABLE `parts_sub_categ` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `display_name` varchar(50) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `state` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`)
VALUES
	(1,NULL,'ileavalentin@gmail.com',NULL,'$2y$14$z6uzCD8UTTE.Rww.4k1fE.cc6s5c33jYehzvY9hLc.GtBdORr5qW.',NULL),
	(2,NULL,'ileavalentin2@gmail.com',NULL,'$2y$14$QvFNbVR/GlNz0Q5a7u0wkePSrjL0ibFOpXxrlQIGsVk/2E9y4w4AC',NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_role
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_role`;

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` varchar(255) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;

INSERT INTO `user_role` (`id`, `roleId`, `is_default`, `parent_id`)
VALUES
	(1,'user',0,NULL),
	(2,'parcauto',0,NULL),
	(3,'admin',0,NULL),
	(10,'guest',1,NULL);

/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_role_linker
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_role_linker`;

CREATE TABLE `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_role_linker` WRITE;
/*!40000 ALTER TABLE `user_role_linker` DISABLE KEYS */;

INSERT INTO `user_role_linker` (`user_id`, `role_id`)
VALUES
	(1,'parcauto'),
	(2,'parcauto');

/*!40000 ALTER TABLE `user_role_linker` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
