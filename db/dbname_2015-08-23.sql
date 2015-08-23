# ************************************************************
# Sequel Pro SQL dump
# Version 3408
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: 127.0.0.1 (MySQL 5.6.22-1+deb.sury.org~trusty+1)
# Database: dbname
# Generation Time: 2015-08-23 06:00:52 +0000
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
  `advertiser_id` int(11) unsigned NOT NULL,
  `car_category` int(3) unsigned NOT NULL,
  `car_make` int(3) NOT NULL,
  `car_model` varchar(50) NOT NULL,
  `year_start` int(3) unsigned NOT NULL,
  `year_end` int(3) unsigned NOT NULL,
  `part_name` varchar(50) NOT NULL,
  `description` mediumtext NOT NULL,
  `price` float(10,2) unsigned NOT NULL,
  `currency` varchar(3) NOT NULL,
  `status` enum('pending','ok','expired') NOT NULL DEFAULT 'pending',
  `dateadd` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `images` text NOT NULL,
  `views` int(11) unsigned NOT NULL,
  `contact_displayed` int(11) unsigned NOT NULL,
  `expiration_date` datetime NOT NULL,
  `stare` enum('nou','second') NOT NULL DEFAULT 'nou',
  `code_oem` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `model` (`car_model`),
  FULLTEXT KEY `part_name` (`part_name`),
  FULLTEXT KEY `description` (`description`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;

INSERT INTO `ads` (`id`, `advertiser_id`, `car_category`, `car_make`, `car_model`, `year_start`, `year_end`, `part_name`, `description`, `price`, `currency`, `status`, `dateadd`, `updated_at`, `images`, `views`, `contact_displayed`, `expiration_date`, `stare`, `code_oem`)
VALUES
	(3,1,2,51,'',0,0,'Furtun de injectie','furtun de injectie ce sa protriveste la toate modele de audi a6 din 2009',200.00,'RON','expired','2015-02-23 19:58:04','0000-00-00 00:00:00','a:0:{}',0,0,'0000-00-00 00:00:00','nou',''),
	(7,1,2,51,'',0,0,'discuri spate','asdadsd',0.00,'RON','expired','2015-02-24 23:28:01','0000-00-00 00:00:00','a:0:{}',0,0,'0000-00-00 00:00:00','nou',''),
	(8,1,2,51,'LX3200, IV2300',2012,2014,'pompa de circulare','pompa de circulare',0.00,'RON','ok','2015-07-07 13:58:50','2015-07-07 13:58:50','a:2:{i:0;s:35:\"337f596490e382685ad2c9d40e1d1485a73\";i:1;s:35:\"667b2616b47220db07db2052229c77f1345\";}',12,0,'2015-09-06 13:58:50','nou',''),
	(9,1,2,51,'LX3200',2011,2015,'toba de esapament','Audi A4 sedan 1.9TDI, sau: piesa e compatibila cu toate modele Audi A4 2001-2005',0.00,'RON','ok','2015-07-07 13:58:22','2015-07-07 13:58:22','a:2:{i:0;s:35:\"70798e0fc101a579811ee84de7b77022ec0\";i:1;s:35:\"837d4690a640035f0d0c97e562ea7b212bb\";}',0,0,'2015-09-06 13:58:22','nou',''),
	(10,1,2,51,'LX3200',2013,2015,'pompa de presiune pentru container de cablaj','pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4',199.00,'RON','ok','2015-07-07 13:57:36','2015-07-07 13:57:36','a:1:{i:0;s:35:\"337f596490e382685ad2c9d40e1d1485a73\";}',1,0,'2015-09-06 13:57:36','second',''),
	(12,1,2,51,'LX3200',1962,2015,'granitura de chiuloasa','garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa',299.00,'RON','ok','2015-07-07 13:58:42','2015-07-07 13:58:42','a:3:{i:0;s:35:\"2438d33e67f3d8a919ff0af63547e6eab8a\";i:1;s:35:\"651f596490e382685ad2c9d40e1d1485a73\";i:2;s:35:\"842b2616b47220db07db2052229c77f1345\";}',0,0,'2015-09-06 13:58:42','nou',''),
	(20,1,2,58,'MB2000',2011,2013,'carburant cu turbina de putere','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.',2000.00,'EUR','ok','2015-07-07 13:58:57','2015-07-07 13:58:57','a:0:{}',1,0,'2015-09-06 13:58:57','nou',''),
	(21,1,2,58,'MB1500',2009,2011,'garnitura prag de evacuare','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.',1999.00,'RON','ok','2015-07-07 13:57:59','2015-07-07 13:57:59','a:1:{i:0;s:35:\"656d6d968d1f304afba6bd860fc5fad695e\";}',0,0,'2015-09-06 13:57:59','nou',''),
	(22,1,2,42,'DF100',2009,2015,'oglinda stanga','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus',20.00,'EUR','ok','2015-07-07 13:57:51','2015-07-07 13:57:51','a:1:{i:0;s:35:\"619967dfe3725dea8285bb251c6f6f7faec\";}',0,0,'2015-09-06 13:57:51','nou',''),
	(23,1,2,51,'IV2300, LX345',2010,2015,'carlig bara tractare','pompa carlig',400.00,'EUR','ok','2015-07-07 13:58:29','2015-07-07 13:58:29','a:1:{i:0;s:35:\"583e881595c9bdd7c9f7f0928b7787ed95f\";}',5,0,'2015-09-06 13:58:29','nou',''),
	(26,1,2,58,'MB2323',1961,2015,'Oglinda retrovizoare cu comenzi digitale','sdgn sgnoifdgniosdngionf gionsgio gniogn sign goidngio sdngiong udbg uigbgi noifgnsidogbiugbfgu noign sdgsdignsdoig ndsoign sdoigndsiogndfoi gnosign iogndso igndfsoig noign doign dfsoignsdgoi ngoindsogin sdgoindsg oinsdoigndsoigndfsiog ndsoign dsoignf doigndfio gndsfoign sdogin sdiognfo ginsdofig',199.00,'USD','ok','2015-07-07 13:57:28','2015-07-07 13:57:28','a:1:{i:0;s:35:\"29744955fd99b09e0844332f2626f5341f3\";}',8,1,'2015-09-06 13:57:28','second','12325'),
	(28,1,2,61,'ASTRA',2010,2015,'capac delcou','capac delcou',0.00,'RON','expired','2015-04-06 19:25:05','2015-04-09 19:45:57','a:1:{i:0;s:35:\"819d6d968d1f304afba6bd860fc5fad695e\";}',0,0,'2015-05-06 22:05:20','second',''),
	(29,1,2,51,'LX3200',1982,2009,'pompa de submersie cu carlig de evacuare','pompa de submersie poate fi istalata pe toate modele din anii 2000',99.00,'USD','ok','2015-07-07 13:57:42','2015-07-07 13:57:42','a:2:{i:0;s:35:\"1728d33e67f3d8a919ff0af63547e6eab8a\";i:1;s:35:\"832e38515fca64a00a766f3b4623c778aca\";}',18,2,'2015-09-06 13:57:42','second',''),
	(30,1,2,51,'LX3200',2013,2015,'oglinzi','oglinzi de toate felurile',50.99,'RON','ok','2015-07-20 21:27:14','2015-07-20 21:27:39','a:9:{i:0;s:35:\"688967dfe3725dea8285bb251c6f6f7faec\";i:1;s:35:\"495d4690a640035f0d0c97e562ea7b212bb\";i:2;s:35:\"693eaffe067455cc66188586ba6457523e0\";i:3;s:35:\"4962760735506a5bc187a35f6c829fae70d\";i:4;s:35:\"1504c7ae5a42e4a70ce21ee214719e13d3d\";i:5;s:35:\"427f596490e382685ad2c9d40e1d1485a73\";i:6;s:35:\"5518d33e67f3d8a919ff0af63547e6eab8a\";i:7;s:35:\"821f596490e382685ad2c9d40e1d1485a73\";i:8;s:35:\"912f596490e382685ad2c9d40e1d1485a73\";}',0,0,'2015-08-19 21:27:14','nou','342534241234'),
	(31,18,2,51,'LX3200',1999,2002,'trapa cabina tir','trapa la cabina de tir cu deschidere electrica cu panou de sticla mata gri',200.00,'EUR','ok','2015-07-25 16:20:51','2015-07-25 17:32:01','a:2:{i:0;s:35:\"2554fc7f199e036ce35a86a42b25b8e5e87\";i:1;s:35:\"7315a5d4f29f71ddfab1e5f36b432ee346f\";}',63,2,'2015-08-24 16:20:51','second','576542365687465'),
	(32,18,1,21,'Sprinter',2002,2009,'Oglinda retrovizoare cu comenzi digitale','Oglinda retrovizoare cu comenzi digitale pentru modele: sprinter, vito si D230',200.00,'RON','ok','2015-07-25 17:31:21','2015-07-25 18:50:19','a:1:{i:0;s:35:\"45644955fd99b09e0844332f2626f5341f3\";}',107,2,'2015-08-24 17:31:21','second','MBOR2342352'),
	(39,24,2,41,'111',2011,2015,'colier de relgaj','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vehicula sit amet tellus vitae dictum. Maecenas aliquet fermentum turpis eu porttitor. Ut hendrerit urna eu odio cursus ultrices. Donec ullamcorper aliquet ligula, nec fermentum libero ultricies eu. Aliquam libero nunc, dictum sit amet rhoncus non, ullamcorper eu est. Integer lobortis vulputate dui a rhoncus. Morbi porttitor tempor egestas. Integer convallis vitae nibh ut dapibus. Nam nisi diam, auctor quis volutpat at, iaculis sit amet enim.',2.99,'RON','ok','2015-08-08 18:19:26','2015-08-08 18:19:26','a:1:{i:0;s:35:\"5174c7ae5a42e4a70ce21ee214719e13d3d\";}',0,0,'2015-09-07 18:19:26','nou',''),
	(37,22,2,51,'FL8900',2015,2015,'test piesa 2','khbhbhj hbj',34.00,'RON','ok','2015-08-08 14:41:07','2015-08-08 14:41:07','a:0:{}',4,2,'2015-09-07 14:41:07','nou',''),
	(40,25,2,51,'IV250',2013,2015,'trapa si parbriz','In efficitur, nulla quis auctor molestie, felis dui tincidunt ante, eget feugiat enim tortor nec diam. Donec commodo tempus dolor, at convallis est fermentum vel. Praesent blandit velit nulla, rutrum ultricies nisi facilisis id. Quisque iaculis maximus mi, id suscipit velit pharetra at. In hac habitasse platea dictumst. Nulla ac ante urna. Donec porta arcu ex, non ornare metus interdum et. Maecenas pretium quam ante, ac finibus lorem tincidunt non. Curabitur volutpat enim dolor, quis pellentesque lorem varius ut. Pellentesque posuere eget leo eu ornare. Phasellus eget leo nisi. Pellentesque posuere magna vel facilisis blandit. Vestibulum lacus felis, aliquet nec nisl pharetra, dictum congue ipsum.',199.99,'RON','ok','2015-08-09 10:03:24','2015-08-09 10:03:24','a:2:{i:0;s:35:\"4715a5d4f29f71ddfab1e5f36b432ee346f\";i:1;s:35:\"5894fc7f199e036ce35a86a42b25b8e5e87\";}',9,1,'2015-09-08 10:03:24','nou',''),
	(36,20,2,42,'111',2015,2015,'test piesa','sdfsdfsfsdfdf',19.99,'RON','ok','2015-08-08 14:35:50','2015-08-08 14:35:50','a:25:{i:0;s:2:\"12\";i:1;s:1:\"6\";i:2;s:2:\"21\";i:3;s:1:\"9\";i:4;s:2:\"22\";i:5;s:8:\"tmp11115\";i:6;s:8:\"tmp12045\";i:7;s:8:\"tmp18119\";i:8;s:8:\"tmp37247\";i:9;s:8:\"tmp34568\";i:10;s:8:\"tmp34420\";i:11;s:8:\"tmp53472\";i:12;s:8:\"tmp18511\";i:13;s:8:\"tmp33790\";i:14;s:8:\"tmp29336\";i:15;s:8:\"tmp63743\";i:16;s:8:\"tmp69655\";i:17;s:8:\"tmp87539\";i:18;s:8:\"tmp66890\";i:19;s:2:\"26\";i:20;s:2:\"10\";i:21;s:2:\"29\";i:22;s:1:\"8\";i:23;s:2:\"30\";i:24;s:2:\"23\";}',1,0,'2015-09-07 14:35:50','nou','');

/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table advertiser
# ------------------------------------------------------------

DROP TABLE IF EXISTS `advertiser`;

CREATE TABLE `advertiser` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL,
  `url` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL DEFAULT '',
  `city` varchar(50) NOT NULL DEFAULT '',
  `state` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL DEFAULT '',
  `tel1` varchar(20) NOT NULL DEFAULT '',
  `tel2` varchar(20) NOT NULL DEFAULT '',
  `tel3` varchar(20) NOT NULL DEFAULT '',
  `logo` varchar(100) NOT NULL DEFAULT '',
  `account_type` enum('particular','parc-auto','unregistered') NOT NULL DEFAULT 'parc-auto',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `advertiser` WRITE;
/*!40000 ALTER TABLE `advertiser` DISABLE KEYS */;

INSERT INTO `advertiser` (`id`, `name`, `email`, `url`, `address`, `city`, `state`, `description`, `tel1`, `tel2`, `tel3`, `logo`, `account_type`)
VALUES
	(1,'DinamicVal Dezmembrari Camioane','contact@validez.ro','www.tirbox.ro','str Slatinei nr 1','Oradea','6','descrirea parcului meu auto pentru a putea fi vazut de catre toti cei care vor sa cumpere tot felul de parit de masini','0747047607','0732456543','','1059177d8e0573bcc91588da9675758c0b6','parc-auto'),
	(2,'ileavalentin7@gmail.com','ileavalentin7@gmail.com','','','','','','','','','','parc-auto'),
	(3,'ileavalentin8@gmail.com','ileavalentin8@gmail.com','','','','','','','','','','parc-auto'),
	(4,'ileavalentin9@gmail.com','ileavalentin9@gmail.com','','','','','','','','','','parc-auto'),
	(5,'vali1@gmail.com','vali1@gmail.com','','','','','','','','','','parc-auto'),
	(6,'vali1@tirbox.ro','vali1@tirbox.ro','','','','','','','','','','parc-auto'),
	(7,'vali2@tb.ro','vali2@tb.ro','','','','','','','','','','parc-auto'),
	(8,'vali3@tb.ro','vali3@tb.ro','','','','','','','','','','parc-auto'),
	(9,'v3@tb.ro','v3@tb.ro','','','','','','','','','','parc-auto'),
	(15,'v4@tb.ro','v4@tb.ro','','','','','','','','','','parc-auto'),
	(17,'v5@tb.ro','v5@tb.ro','','','','','','','','','','parc-auto'),
	(18,'Valentin Ilea','ileavalentin930@yahoo.co.uk','','','Oradea','6','','0747047607','','','5805466affa2c43acd7c45602ff44d3d878','particular'),
	(20,'gheorghe stefan','','','','Cluj-Napoca','14','','0567895342','','','','particular'),
	(22,'gheorghe stefan','','','','Cluj-Napoca','14','','0567895342','','','','particular'),
	(24,'gheorghe stefan','','','','Cluj-Napoca','13','','07897678234','','','','unregistered'),
	(25,'Valisle Lpupescu2','v.lupescu@tirbox.ro','','str Slatinei nr 1','Oradea','6','','653423536456','','','','unregistered');

/*!40000 ALTER TABLE `advertiser` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table advertiser_users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `advertiser_users`;

CREATE TABLE `advertiser_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `advertiser_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `advertiser_users` WRITE;
/*!40000 ALTER TABLE `advertiser_users` DISABLE KEYS */;

INSERT INTO `advertiser_users` (`id`, `user_id`, `advertiser_id`)
VALUES
	(1,1,1),
	(2,2,1),
	(3,22,2),
	(4,23,3),
	(5,24,4),
	(6,25,5),
	(7,26,6),
	(8,27,7),
	(9,28,8),
	(10,29,9),
	(16,38,15),
	(18,40,17),
	(19,41,18);

/*!40000 ALTER TABLE `advertiser_users` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cars_model
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cars_model`;

CREATE TABLE `cars_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(3) NOT NULL,
  `car_make` varchar(50) NOT NULL DEFAULT '',
  `popular` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cars_model` WRITE;
/*!40000 ALTER TABLE `cars_model` DISABLE KEYS */;

INSERT INTO `cars_model` (`id`, `category_id`, `car_make`, `popular`)
VALUES
	(1,1,'Algemas',0),
	(2,1,'Brakas',0),
	(3,1,'Citroen',1),
	(4,1,'Dacia',1),
	(5,1,'Daf',0),
	(6,1,'Fiat',0),
	(7,1,'Ford',1),
	(8,1,'Fuso',0),
	(9,1,'GAC Gonow',0),
	(10,1,'Hako',0),
	(11,1,'Hanomag',0),
	(12,1,'Hyundai',0),
	(13,1,'Ifor Williams',0),
	(14,1,'Intrall',0),
	(15,1,'Isuzu',1),
	(16,1,'Iveco',1),
	(17,1,'Kia',0),
	(18,1,'Ladog',0),
	(19,1,'MAN',0),
	(20,1,'Mazda',1),
	(21,1,'Mercedes-Benz',1),
	(22,1,'Mitsubishi',0),
	(23,1,'Multicar',0),
	(24,1,'Nissan',0),
	(25,1,'Opel',1),
	(26,1,'Peugeot',0),
	(27,1,'Piaggio',0),
	(28,1,'Renault',0),
	(29,1,'Robur',0),
	(30,1,'Roman',0),
	(31,1,'Schmidt',0),
	(32,1,'Seat',0),
	(33,1,'Skoda',0),
	(34,1,'Steyr',0),
	(35,1,'Suzuki',0),
	(36,1,'Toyota',0),
	(37,1,'Unimog',0),
	(38,1,'Volkswagen',0),
	(39,1,'Volvo',0),
	(40,1,'Altele',0),
	(41,2,'Daewoo',0),
	(42,2,'DAF',0),
	(43,2,'Demag',0),
	(44,2,'Faul',0),
	(45,2,'Ford',0),
	(46,2,'Freightliner',0),
	(47,2,'Ginaf',0),
	(48,2,'Grove',0),
	(49,2,'Hako',0),
	(50,2,'HN Schorling',0),
	(51,2,'Iveco',1),
	(52,2,'Kamaz',0),
	(53,2,'Kenworth',0),
	(54,2,'Liebherr',0),
	(55,2,'Mack',0),
	(56,2,'Magirus-Deutz',0),
	(57,2,'Man',1),
	(58,2,'Mercedes-Benz',1),
	(59,2,'Mitsubishi',0),
	(60,2,'Nissan',0),
	(61,2,'Opel',1),
	(62,2,'Palfinger',0),
	(63,2,'Peterbilt',0),
	(64,2,'Peugeot',0),
	(65,2,'Renault',1),
	(66,2,'Roman',1),
	(67,2,'Scania',0),
	(68,2,'Schmidt',0),
	(69,2,'Skoda',0),
	(70,2,'Steyr',0),
	(71,2,'Tatra',0),
	(72,2,'Toyota',0),
	(73,2,'Unimog',0),
	(74,2,'Volkswagen',1),
	(75,2,'Volvo',1),
	(76,2,'Yanmar',0),
	(77,2,'Altele',0),
	(78,3,'Ackermann',0),
	(79,3,'Agados',0),
	(80,3,'Ahlmann',0),
	(81,3,'Airstream',0),
	(82,3,'Algema',0),
	(83,3,'Annaburger',0),
	(84,3,'Anssems',0),
	(85,3,'Atec',1),
	(86,3,'Auwarter',0),
	(87,3,'Barthau',0),
	(88,3,'Benalu',0),
	(89,3,'Berger',0),
	(90,3,'Blomenrohr',0),
	(91,3,'Blomert',0),
	(92,3,'Blyss',0),
	(93,3,'BNG',1),
	(94,3,'Bockmann',0),
	(95,3,'Borco-Hohns',0),
	(96,3,'Brandl',0),
	(97,3,'Brenderup',0),
	(98,3,'Brian James',0),
	(99,3,'Broshuis',0),
	(100,3,'Bunge',0),
	(101,3,'Carnehl',0),
	(102,3,'Chereau',0),
	(103,3,'Cheval Liberte',0),
	(104,3,'Daltec',0),
	(105,3,'Dinkel',0),
	(106,3,'Doll',0),
	(107,3,'Eduard',0),
	(108,3,'ES GE',0),
	(109,3,'Excalibur',0),
	(110,3,'Faymonville',0),
	(111,3,'Feber',1),
	(112,3,'Feldbinder',0),
	(113,3,'Fitzel',0),
	(114,3,'Fliegl',1),
	(115,3,'Floor',0),
	(116,3,'Frankenstein',0),
	(117,3,'Fruhauf',0),
	(118,3,'General Trailer',0),
	(119,3,'Gerden',0),
	(120,3,'Goldhofer',0),
	(121,3,'Groeneweghen',0),
	(122,3,'Hapert',0),
	(123,3,'Heinemann',0),
	(124,3,'Hendricks',0),
	(125,3,'Henra',0),
	(126,3,'HKM',1),
	(127,3,'Hoffmann',0),
	(128,3,'Homar',0),
	(129,3,'HRD',0),
	(130,3,'Huffermann',0),
	(131,3,'Hulco',0),
	(132,3,'Humbaur',0),
	(133,3,'Hutner',0),
	(134,3,'Ifor Williams',0),
	(135,3,'Jotha',0),
	(136,3,'Kaiser',0),
	(137,3,'Kassbohrer',0),
	(138,3,'Kel-Berg',0),
	(139,3,'Kempf',0),
	(140,3,'Klagie',0),
	(141,3,'Knapen',0),
	(142,3,'Koch',0),
	(143,3,'Kogel',1),
	(144,3,'Kotschenreuther',0),
	(145,3,'Kraker',0),
	(146,3,'Kramer',0),
	(147,3,'Kroeger',0),
	(148,3,'Krone',0),
	(149,3,'Krukenmeier',0),
	(150,3,'Lafaro',0),
	(151,3,'Lag',0),
	(152,3,'Lamberet',0),
	(153,3,'Langendorf',0),
	(154,3,'Lecinena',0),
	(155,3,'LeciTrailer',0),
	(156,3,'Ley',0),
	(157,3,'LinTrailers',1),
	(158,3,'Luck',0),
	(159,3,'Magyar',0),
	(160,3,'MEGA',0),
	(161,3,'Meierling',0),
	(162,3,'Meiller',0),
	(163,3,'Menci',0),
	(164,3,'Merker',0),
	(165,3,'Meusburger',0),
	(166,3,'Montenegro',0),
	(167,3,'Moslein',0),
	(168,3,'Muller-Mitteltal',0),
	(169,3,'Neptun',1),
	(170,3,'NFP-Eurotrailer',0),
	(171,3,'Niewiadow',0),
	(172,3,'Nooteboom',0),
	(173,3,'Obermaier',0),
	(174,3,'Orten',0),
	(175,3,'Orthaus',0),
	(176,3,'Pacton',0),
	(177,3,'Palfinger',0),
	(178,3,'Pezzaioli',0),
	(179,3,'Pongratz',0),
	(180,3,'Renders',0),
	(181,3,'Reuter',0),
	(182,3,'ROHR',0),
	(183,3,'ROKA',0),
	(184,3,'Samro',0),
	(185,3,'Saris',0),
	(186,3,'SAXAS',0),
	(187,3,'Scheuerle',0),
	(188,3,'Schmidt',0),
	(189,3,'Schmidt Cargobull',0),
	(190,3,'Schrader',0),
	(191,3,'Schwarzmuller',0),
	(192,3,'Seico',0),
	(193,3,'SEKA',0),
	(194,3,'Sluis',0),
	(195,3,'Sommer',0),
	(196,3,'Spitzer',0),
	(197,3,'Stas',0),
	(198,3,'Stedele',0),
	(199,3,'Stema',0),
	(200,3,'Talson',0),
	(201,3,'Tempus',0),
	(202,3,'Thiel',0),
	(203,3,'THULE',0),
	(204,3,'Tijhof',0),
	(205,3,'TPV',0),
	(206,3,'Trailor',0),
	(207,3,'Treibner',0),
	(208,3,'Unsinn',0),
	(209,3,'Van Eck',0),
	(210,3,'Vanhool',0),
	(211,3,'Variotrail',0),
	(212,3,'Vezeko',0),
	(213,3,'Volkswagen',0),
	(214,3,'Voss',0),
	(215,3,'Wackenhut',0),
	(216,3,'Wagner',0),
	(217,3,'Wecon',0),
	(218,3,'Westfalia',0),
	(219,3,'Wielton',0),
	(220,3,'WM Meyer',0),
	(221,3,'Woodford',0),
	(222,3,'Womann',0),
	(223,3,'XXTrail',0),
	(224,3,'ZASLAW TRAILIS',0),
	(225,3,'Z-Trailer',0),
	(226,3,'Altele',0),
	(227,5,'DAF',1),
	(228,5,'Drogmoller',0),
	(229,5,'EOS',0),
	(230,5,'Evobus',0),
	(231,5,'Fiat',1),
	(232,5,'Ford',1),
	(233,5,'Ikarus',1),
	(234,5,'Irisbus',1),
	(235,5,'Irizar',0),
	(236,5,'Isuzu',1),
	(237,5,'Iveco',1),
	(238,5,'King Long',0),
	(239,5,'Magirus Deutz',0),
	(240,5,'Man',0),
	(241,5,'Mercedes-Benz',0),
	(242,5,'Neoplan',0),
	(243,5,'Peugeot',0),
	(244,5,'Renault',0),
	(245,5,'Robur',0),
	(246,5,'Roman',0),
	(247,5,'Scania',0),
	(248,5,'Setra',0),
	(249,5,'Solaris',0),
	(250,5,'Temsa',0),
	(251,5,'Vanhool',0),
	(252,5,'VDL',0),
	(253,5,'Volkswagen',0),
	(254,5,'Volvo',1),
	(255,5,'Altele',0),
	(256,6,'ABG',0),
	(257,6,'Ahlmann',0),
	(258,6,'Allrad',0),
	(259,6,'Ammann',0),
	(260,6,'Atlas',0),
	(261,6,'Atlet',0),
	(262,6,'Ausa',0),
	(263,6,'Barford',0),
	(264,6,'Belle',0),
	(265,6,'Benaty',0),
	(266,6,'Blizzer',0),
	(267,6,'Bobcat',0),
	(268,6,'Bomag',0),
	(269,6,'BT',0),
	(270,6,'BV',0),
	(271,6,'Case',0),
	(272,6,'Cat',1),
	(273,6,'Caterpillar',0),
	(274,6,'Cesab',0),
	(275,6,'Clark',0),
	(276,6,'Crown',0),
	(277,6,'Daewoo',1),
	(278,6,'Dantruck',0),
	(279,6,'Demag',0),
	(280,6,'Denyo',0),
	(281,6,'Destas',0),
	(282,6,'Ditch Witch',0),
	(283,6,'Doosan',0),
	(284,6,'Dresser',0),
	(285,6,'Dynapac',0),
	(286,6,'Fadroma',0),
	(287,6,'Faun',0),
	(288,6,'Fendt',0),
	(289,6,'Fermec',0),
	(290,6,'Fiat',0),
	(291,6,'Filnay',0),
	(292,6,'Ford',1),
	(293,6,'Fuchs',0),
	(294,6,'Furukawa',0),
	(295,6,'Gehl',0),
	(296,6,'Genie',0),
	(297,6,'Genus',0),
	(298,6,'Grove',0),
	(299,6,'Hako',0),
	(300,6,'Hamm',0),
	(301,6,'Hangcha',0),
	(302,6,'Hanix',0),
	(303,6,'Hanomag',0),
	(304,6,'Hartl',0),
	(305,6,'Haulotte',0),
	(306,6,'HC',0),
	(307,6,'Heden',0),
	(308,6,'Hiab',0),
	(309,6,'Hinowa',0),
	(310,6,'Hitachi',1),
	(311,6,'HSW',0),
	(312,6,'Hydrema',0),
	(313,6,'Hyster',0),
	(314,6,'Hyundai',1),
	(315,6,'Jamma',0),
	(316,6,'JCB',0),
	(317,6,'John Deere',0),
	(318,6,'Jungheinrich',0),
	(319,6,'Kaercher',0),
	(320,6,'Kalmar',0),
	(321,6,'Kobelco',0),
	(322,6,'Komatsu',0),
	(323,6,'Konig',0),
	(324,6,'Kraftwelle',0),
	(325,6,'Kramerv',0),
	(326,6,'Kubota',0),
	(327,6,'Libra',0),
	(328,6,'Liebherr',1),
	(329,6,'Linde',0),
	(330,6,'Luna',0),
	(331,6,'Manitou',0),
	(332,6,'Mecalac',0),
	(333,6,'Merlo',0),
	(334,6,'Mitsubishi',1),
	(335,6,'Moxy',0),
	(336,6,'MTZ',0),
	(337,6,'Neuson',0),
	(338,6,'New Holland',0),
	(339,6,'Nissan',1),
	(340,6,'O&K',0),
	(341,6,'Ostrówek',0),
	(342,6,'Palfinger',0),
	(343,6,'Paus',0),
	(344,6,'Powerscreen',0),
	(345,6,'Pramac',0),
	(346,6,'Putzmeister',0),
	(347,6,'Rammax',0),
	(348,6,'Renault',0),
	(349,6,'Rocla',0),
	(350,6,'Samsung',0),
	(351,6,'Schaeff',0),
	(352,6,'Schäffer',0),
	(353,6,'Socage',0),
	(354,6,'Steinbock',0),
	(355,6,'Still',0),
	(356,6,'Strassmayr',0),
	(357,6,'SUNWARD',0),
	(358,6,'Svettruck',0),
	(359,6,'Takeuchi',0),
	(360,6,'TCM',0),
	(361,6,'Terberg',0),
	(362,6,'Terex',0),
	(363,6,'Tesab',0),
	(364,6,'Toyota',0),
	(365,6,'Unimog',0),
	(366,6,'Venieri',0),
	(367,6,'Vermeer',0),
	(368,6,'Vogele',0),
	(369,6,'Volvo',0),
	(370,6,'Wacker',0),
	(371,6,'WEBER',0),
	(372,6,'Wirtgen',0),
	(373,6,'Yale',0),
	(374,6,'Yamaguchi',0),
	(375,6,'Yanmar',0),
	(376,6,'Zeppelin',0),
	(377,6,'Zettelmeye',0),
	(378,6,'Altele',0),
	(379,4,'Accord',0),
	(380,4,'Agco / Massey Ferguson',0),
	(381,4,'Amazone',0),
	(382,4,'Avant Tecno',0),
	(383,4,'Becker',0),
	(384,4,'Bergmann',0),
	(385,4,'Branson',0),
	(386,4,'Busatis',0),
	(387,4,'BvL – Van Lengerich',0),
	(388,4,'Carraro',0),
	(389,4,'Case',0),
	(390,4,'Claas',0),
	(391,4,'Deutz Fahr',0),
	(392,4,'Doll',0),
	(393,4,'Ducker',0),
	(394,4,'Duvelsdorf',0),
	(395,4,'Eberhardt',0),
	(396,4,'Eicher',0),
	(397,4,'Fahr',0),
	(398,4,'Fella',0),
	(399,4,'Fendt',0),
	(400,4,'Fiat',1),
	(401,4,'Ford',1),
	(402,4,'Fortschirtt',0),
	(403,4,'Foton',0),
	(404,4,'Frost',0),
	(405,4,'Gaspardo',0),
	(406,4,'Goldoni',0),
	(407,4,'Grimme',0),
	(408,4,'Guldner',0),
	(409,4,'Gutbrod',0),
	(410,4,'Hako',0),
	(411,4,'Hanomag',0),
	(412,4,'Hanssia',0),
	(413,4,'Holder',0),
	(414,4,'Howard',0),
	(415,4,'IHC',0),
	(416,4,'Iseki',0),
	(417,4,'Jacobsen',0),
	(418,4,'JCB',0),
	(419,4,'John Deere',0),
	(420,4,'Kioti',0),
	(421,4,'Kramer',0),
	(422,4,'Krone',0),
	(423,4,'Kubota',0),
	(424,4,'Lamborghini',1),
	(425,4,'Landini',0),
	(426,4,'Landsberg',0),
	(427,4,'Lanz',0),
	(428,4,'Lely',0),
	(429,4,'Lemken',0),
	(430,4,'MAN',1),
	(431,4,'Maschio',0),
	(432,4,'Masswy Ferguson',0),
	(433,4,'McCormick',0),
	(434,4,'Mengele',0),
	(435,4,'Mercedes-Benz',0),
	(436,4,'Mitsubishi',1),
	(437,4,'Multicar',0),
	(438,4,'New Holland',0),
	(439,4,'Niemeyer',0),
	(440,4,'Porsche',1),
	(441,4,'Pottinger',0),
	(442,4,'PZ-Vicon',0),
	(443,4,'Rabe',0),
	(444,4,'Rau',0),
	(445,4,'Rauch',0),
	(446,4,'Reforwerke Wels',0),
	(447,4,'Regent',0),
	(448,4,'Renault',1),
	(449,4,'Same',0),
	(450,4,'Schaffer',0),
	(451,4,'Schluter',0),
	(452,4,'Schmidt',0),
	(453,4,'Steyr',0),
	(454,4,'Strautmann',0),
	(455,4,'Unimog',0),
	(456,4,'Universal',1),
	(457,4,'Valtra',0),
	(458,4,'Vogel&Noot',0),
	(459,4,'Weidemann',0),
	(460,4,'Zetor',0),
	(461,4,'Altele',0);

/*!40000 ALTER TABLE `cars_model` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table cars_model2
# ------------------------------------------------------------

DROP TABLE IF EXISTS `cars_model2`;

CREATE TABLE `cars_model2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(3) NOT NULL,
  `car_make` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `cars_model2` WRITE;
/*!40000 ALTER TABLE `cars_model2` DISABLE KEYS */;

INSERT INTO `cars_model2` (`id`, `category_id`, `car_make`)
VALUES
	(9,2,'DAF'),
	(10,2,'Demag'),
	(11,2,'Iveco'),
	(12,2,'Man'),
	(13,2,'Mercedes-Benz'),
	(14,2,'Renault'),
	(15,2,'Opel');

/*!40000 ALTER TABLE `cars_model2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table categories
# ------------------------------------------------------------

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) NOT NULL DEFAULT '',
  `ord` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;

INSERT INTO `categories` (`id`, `category`, `ord`)
VALUES
	(1,'Utilitare max 3.5 tone',2),
	(2,'Camioane',1),
	(3,'Remorci',3),
	(4,'Utilaje Agricole',5),
	(5,'Autobuze',6),
	(6,'Utilaje Constructii',4);

/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table newsletter_logs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `newsletter_logs`;

CREATE TABLE `newsletter_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `advertiser_id` int(11) NOT NULL,
  `email_type` varchar(20) NOT NULL DEFAULT '',
  `dateadd` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `newsletter_logs` WRITE;
/*!40000 ALTER TABLE `newsletter_logs` DISABLE KEYS */;

INSERT INTO `newsletter_logs` (`id`, `advertiser_id`, `email_type`, `dateadd`)
VALUES
	(6,1,'inactivate_ad','2015-05-08 11:57:45'),
	(14,1,'inactivate_ad','2015-05-12 09:56:44');

/*!40000 ALTER TABLE `newsletter_logs` ENABLE KEYS */;
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
  `hash_login` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`user_id`, `username`, `email`, `display_name`, `password`, `state`, `hash_login`)
VALUES
	(1,NULL,'ileavalentin@gmail.com','Vali Ilea','$2y$14$IPcJvB17aK5Gw5MEeH43uOY4PzmkdUri2nTSygCkSkky4FVMjs36e',1,'a2aff77a06cba09f97c9259364efd2e4'),
	(17,NULL,'ileavalentin2@gmail.com',NULL,'$2y$14$gPJigLBEXDI2/oEn2vqFAOqeAHNDTKMAwO30/NOKq.3qhzhYzknCW',1,NULL),
	(18,NULL,'ileavalentin3@gmail.com',NULL,'$2y$14$Dx.W2sIL/yBewETiAZ5CYe4CV5jxzJuhc4Vn4cR1cchU9Uue7oJEy',1,NULL),
	(19,NULL,'ileavalentin4@gmail.com',NULL,'$2y$14$irpnXJpVUzLR7vPjqy/D1.uu4VWGYueT3yrESeZn0NUiTrYj5rBbi',1,NULL),
	(20,NULL,'ileavalentin5@gmail.com',NULL,'$2y$14$vgmPWt4NjZ5sUkAlOo1GXu.d0/TS3q6stSN8YyNMXhsYHN5RBHw0K',1,NULL),
	(21,NULL,'ileavalentin6@gmail.com',NULL,'$2y$14$6MsnUcu3i0rxAxFH52t2auTU0PS575XRKw5x47qtCdZ0oMChEcE46',1,NULL),
	(22,NULL,'ileavalentin7@gmail.com',NULL,'$2y$14$sDGbWN4ZhhBvVsqc.DSd1OadlkgRc2Uj6lk1R6qbhNrs3P6GqFlAC',1,NULL),
	(23,NULL,'ileavalentin8@gmail.com',NULL,'$2y$14$6.widPbSF1RIvM8QiPU8KeVbmlKyX1JA/frX8nGbBMRJrhS7grBBe',1,NULL),
	(24,NULL,'ileavalentin9@gmail.com',NULL,'$2y$14$sTPuoYcJ6tzzjF5DR.sDyOks7Wym5vmBEnmFl1eNNFRb28I6ROv56',1,NULL),
	(25,NULL,'vali1@gmail.com',NULL,'$2y$14$LqlZnhMEPbm0terax.vxPeemvHeI4dAettZwae5nivWc6ZprtxFcK',1,NULL),
	(26,NULL,'vali1@tirbox.ro',NULL,'$2y$14$SBCtToVhZO9NBP3JnQdWYuPVZ62apiwpXp/wYfM5cOMprQSFWclTW',1,NULL),
	(27,NULL,'vali2@tb.ro',NULL,'$2y$14$CXBe804C61bSh/oMFOcBceFRafe/z8pEnutLRPpL3KaaApr48HX.m',1,NULL),
	(28,NULL,'vali3@tb.ro',NULL,'$2y$14$eHFm6lL/vZus2/uWaoGfKefxbr2aQ19ib7PHEtRjzdngXHT7ynsx2',1,NULL),
	(29,NULL,'v3@tb.ro',NULL,'$2y$14$3PoLUAVhV.aibzbAdkhNqevCqfF7osMXfshMIruGqFhGeWPeNtqke',1,NULL),
	(38,NULL,'v4@tb.ro',NULL,'$2y$14$Rrd1WzvXwkch9tq0ifJVk./jBzRmzz8OmQViuehKrHS2bp2ZRSm3i',1,NULL),
	(40,NULL,'v5@tb.ro',NULL,'$2y$14$oRa63WtaPwrghlnkZp5SxOzdI0dRTC8Nkn9xp9yRjecWAcNjHt6Su',1,NULL),
	(41,NULL,'ileavalentin930@yahoo.co.uk','Valentin Ilea','facebookToLocalUser',1,NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_forgot_pass
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_forgot_pass`;

CREATE TABLE `user_forgot_pass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `hash` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_forgot_pass` WRITE;
/*!40000 ALTER TABLE `user_forgot_pass` DISABLE KEYS */;

INSERT INTO `user_forgot_pass` (`id`, `email`, `hash`)
VALUES
	(4,'ileavalentin@gmail.com','7822a61bbce008a1ffe9a7018fb0d5cb78053839');

/*!40000 ALTER TABLE `user_forgot_pass` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_provider
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_provider`;

CREATE TABLE `user_provider` (
  `user_id` int(11) NOT NULL,
  `provider_id` varchar(50) NOT NULL,
  `provider` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`provider_id`),
  UNIQUE KEY `provider_id` (`provider_id`,`provider`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_provider` WRITE;
/*!40000 ALTER TABLE `user_provider` DISABLE KEYS */;

INSERT INTO `user_provider` (`user_id`, `provider_id`, `provider`)
VALUES
	(41,'10205761714497578','facebook');

/*!40000 ALTER TABLE `user_provider` ENABLE KEYS */;
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
	(3,'admin',0,'contentmanager'),
	(10,'guest',1,NULL),
	(11,'contentmanager',0,'parcauto');

/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_role_linker
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_role_linker`;

CREATE TABLE `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_role_linker` WRITE;
/*!40000 ALTER TABLE `user_role_linker` DISABLE KEYS */;

INSERT INTO `user_role_linker` (`user_id`, `role_id`)
VALUES
	(1,'contentmanager'),
	(17,'parcauto'),
	(18,'parcauto'),
	(19,'parcauto'),
	(20,'parcauto'),
	(21,'parcauto'),
	(22,'parcauto'),
	(23,'parcauto'),
	(24,'parcauto'),
	(25,'parcauto'),
	(26,'parcauto'),
	(27,'parcauto'),
	(28,'parcauto'),
	(29,'parcauto'),
	(38,'parcauto'),
	(40,'parcauto'),
	(41,'parcauto');

/*!40000 ALTER TABLE `user_role_linker` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
