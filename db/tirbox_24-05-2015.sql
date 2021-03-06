/*
 Navicat Premium Data Transfer

 Source Server         : localhost.vagrant.tirbox
 Source Server Type    : MySQL
 Source Server Version : 50622
 Source Host           : 127.0.0.1
 Source Database       : dbname

 Target Server Type    : MySQL
 Target Server Version : 50622
 File Encoding         : utf-8

 Date: 05/24/2015 09:36:52 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `ads`
-- ----------------------------
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
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `ads`
-- ----------------------------
BEGIN;
INSERT INTO `ads` VALUES ('3', '1', '2', '51', '', '0', '0', 'Furtun de injectie', 'furtun de injectie ce sa protriveste la toate modele de audi a6 din 2009', '200.00', 'RON', 'expired', '2015-02-23 19:58:04', '0000-00-00 00:00:00', 'a:0:{}', '0', '0', '0000-00-00 00:00:00', 'nou', ''), ('7', '1', '2', '51', '', '0', '0', 'discuri spate', 'asdadsd', '0.00', 'RON', 'expired', '2015-02-24 23:28:01', '0000-00-00 00:00:00', 'a:0:{}', '0', '0', '0000-00-00 00:00:00', 'nou', ''), ('8', '1', '2', '51', 'LX3200', '2012', '2014', 'pompa de circulare', 'pompa de circulare', '0.00', 'RON', 'ok', '2015-04-28 20:10:15', '2015-04-28 20:10:15', 'a:2:{i:0;s:35:\"337f596490e382685ad2c9d40e1d1485a73\";i:1;s:35:\"667b2616b47220db07db2052229c77f1345\";}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('9', '1', '2', '51', 'LX3200', '2011', '2015', 'toba de esapament', 'Audi A4 sedan 1.9TDI, sau: piesa e compatibila cu toate modele Audi A4 2001-2005', '0.00', 'RON', 'ok', '2015-03-31 19:48:36', '2015-03-31 19:48:36', 'a:2:{i:0;s:35:\"70798e0fc101a579811ee84de7b77022ec0\";i:1;s:35:\"837d4690a640035f0d0c97e562ea7b212bb\";}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('10', '1', '2', '51', 'LX3200', '2013', '2015', 'pompa de presiune pentru container de cablaj', 'pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4 pompa functie pentru modelele de A4', '199.00', 'RON', 'ok', '2015-04-28 20:13:31', '2015-04-10 09:35:15', 'a:1:{i:0;s:35:\"337f596490e382685ad2c9d40e1d1485a73\";}', '0', '0', '2015-05-28 20:13:31', 'second', ''), ('12', '1', '2', '51', '', '0', '0', 'granitura de chiuloasa', 'garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa garnitura de chiuloasa', '299.00', 'RON', 'ok', '2015-03-08 12:15:00', '0000-00-00 00:00:00', 'a:3:{i:0;s:35:\"2438d33e67f3d8a919ff0af63547e6eab8a\";i:1;s:35:\"651f596490e382685ad2c9d40e1d1485a73\";i:2;s:35:\"842b2616b47220db07db2052229c77f1345\";}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('20', '1', '2', '58', 'MB2000', '2011', '2013', 'carburant cu turbina de putere', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.', '2000.00', 'EUR', 'ok', '2015-03-24 23:08:22', '0000-00-00 00:00:00', 'a:0:{}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('21', '1', '2', '58', 'MB1500', '2009', '2011', 'garnitura prag de evacuare', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus posuere sodales ligula, a sagittis libero maximus quis. Aliquam quis neque varius, tincidunt magna sit amet, consequat felis. Phasellus sollicitudin libero et magna cursus euismod. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc vitae lorem enim. Vivamus a turpis nec felis fermentum porta vitae sed lorem. Donec faucibus varius porttitor.', '1999.00', 'RON', 'ok', '2015-03-24 23:11:30', '2015-03-31 19:50:50', 'a:1:{i:0;s:35:\"656d6d968d1f304afba6bd860fc5fad695e\";}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('22', '1', '2', '42', 'DF100', '2009', '2015', 'oglinda stanga', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus at dui quis felis aliquam sodales. Sed a finibus felis, a aliquet mauris. Phasellus', '20.00', 'EUR', 'ok', '2015-03-24 23:13:52', '2015-03-31 19:55:20', 'a:1:{i:0;s:35:\"619967dfe3725dea8285bb251c6f6f7faec\";}', '0', '0', '2015-05-28 20:13:31', 'nou', ''), ('23', '1', '2', '51', 'IV2300, LX', '2010', '2015', 'carlig bara tractare', 'pompa carlig', '400.00', 'EUR', 'expired', '2015-03-26 20:29:37', '2015-03-30 23:05:29', 'a:1:{i:0;s:35:\"583e881595c9bdd7c9f7f0928b7787ed95f\";}', '0', '0', '2015-05-11 18:42:14', 'nou', ''), ('26', '1', '2', '58', 'MB2323', '1961', '2015', 'Oglinda retrovizoare cu comenzi digitale', 'sdgn sgnoifdgniosdngionf gionsgio gniogn sign goidngio sdngiong udbg uigbgi noifgnsidogbiugbfgu noign sdgsdignsdoig ndsoign sdoigndsiogndfoi gnosign iogndso igndfsoig noign doign dfsoignsdgoi ngoindsogin sdgoindsg oinsdoigndsoigndfsiog ndsoign dsoignf doigndfio gndsfoign sdogin sdiognfo ginsdofig', '199.00', 'USD', 'ok', '2015-05-08 12:01:01', '2015-06-07 12:01:01', 'a:1:{i:0;s:35:\"29744955fd99b09e0844332f2626f5341f3\";}', '0', '0', '2015-06-07 12:01:01', 'second', '12325'), ('28', '1', '2', '61', 'ASTRA', '2010', '2015', 'capac delcou', 'capac delcou', '0.00', 'RON', 'expired', '2015-04-06 19:25:05', '2015-04-09 19:45:57', 'a:1:{i:0;s:35:\"819d6d968d1f304afba6bd860fc5fad695e\";}', '0', '0', '2015-05-06 22:05:20', 'second', ''), ('29', '1', '2', '51', 'LX3200', '1982', '2009', 'pompa de submersie cu carlig de evacuare', 'pompa de submersie poate fi istalata pe toate modele din anii 2000', '99.00', 'USD', 'ok', '2015-04-11 19:31:48', '2015-04-11 19:32:19', 'a:2:{i:0;s:35:\"1728d33e67f3d8a919ff0af63547e6eab8a\";i:1;s:35:\"832e38515fca64a00a766f3b4623c778aca\";}', '5', '1', '2015-05-20 22:04:57', 'second', '');
COMMIT;

-- ----------------------------
--  Table structure for `advertiser`
-- ----------------------------
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
  `account_type` enum('particular','parc-auto') NOT NULL DEFAULT 'parc-auto',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `advertiser`
-- ----------------------------
BEGIN;
INSERT INTO `advertiser` VALUES ('1', 'DinamicVal Dezmembrari Camioane', 'contact@validez.ro', 'www.tirbox.ro', 'str Slatinei nr 1', 'Oradea', '5', 'descrirea parcului meu auto pentru a putea fi vazut de catre toti cei care vor sa cumpere tot felul de parit de masini', '0747047607', '0732456543', '', '1059177d8e0573bcc91588da9675758c0b6', 'parc-auto'), ('2', 'ileavalentin7@gmail.com', 'ileavalentin7@gmail.com', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('3', 'ileavalentin8@gmail.com', 'ileavalentin8@gmail.com', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('4', 'ileavalentin9@gmail.com', 'ileavalentin9@gmail.com', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('5', 'vali1@gmail.com', 'vali1@gmail.com', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('6', 'vali1@tirbox.ro', 'vali1@tirbox.ro', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('7', 'vali2@tb.ro', 'vali2@tb.ro', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('8', 'vali3@tb.ro', 'vali3@tb.ro', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('9', 'v3@tb.ro', 'v3@tb.ro', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('15', 'v4@tb.ro', 'v4@tb.ro', '', '', '', '', '', '', '', '', '', 'parc-auto'), ('16', 'Valentin Ilea', 'ileavalentin930@yahoo.co.uk', '', '', 'Oradea', '5', '', '', '', '', '16_21f874760eef2d6f56ebacfe2a7c87fe', 'particular'), ('17', 'v5@tb.ro', 'v5@tb.ro', '', '', '', '', '', '', '', '', '', 'parc-auto');
COMMIT;

-- ----------------------------
--  Table structure for `advertiser_users`
-- ----------------------------
DROP TABLE IF EXISTS `advertiser_users`;
CREATE TABLE `advertiser_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `advertiser_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `advertiser_users`
-- ----------------------------
BEGIN;
INSERT INTO `advertiser_users` VALUES ('1', '1', '1'), ('2', '2', '1'), ('3', '22', '2'), ('4', '23', '3'), ('5', '24', '4'), ('6', '25', '5'), ('7', '26', '6'), ('8', '27', '7'), ('9', '28', '8'), ('10', '29', '9'), ('16', '38', '15'), ('17', '39', '16'), ('18', '40', '17');
COMMIT;

-- ----------------------------
--  Table structure for `cars_model`
-- ----------------------------
DROP TABLE IF EXISTS `cars_model`;
CREATE TABLE `cars_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(3) NOT NULL,
  `car_make` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=462 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `cars_model`
-- ----------------------------
BEGIN;
INSERT INTO `cars_model` VALUES ('1', '1', 'Algemas'), ('2', '1', 'Brakas'), ('3', '1', 'Citroen'), ('4', '1', 'Dacia'), ('5', '1', 'Daf'), ('6', '1', 'Fiat'), ('7', '1', 'Ford'), ('8', '1', 'Fuso'), ('9', '1', 'GAC Gonow'), ('10', '1', 'Hako'), ('11', '1', 'Hanomag'), ('12', '1', 'Hyundai'), ('13', '1', 'Ifor Williams'), ('14', '1', 'Intrall'), ('15', '1', 'Isuzu'), ('16', '1', 'Iveco'), ('17', '1', 'Kia'), ('18', '1', 'Ladog'), ('19', '1', 'MAN'), ('20', '1', 'Mazda'), ('21', '1', 'Mercedes-Benz'), ('22', '1', 'Mitsubishi'), ('23', '1', 'Multicar'), ('24', '1', 'Nissan'), ('25', '1', 'Opel'), ('26', '1', 'Peugeot'), ('27', '1', 'Piaggio'), ('28', '1', 'Renault'), ('29', '1', 'Robur'), ('30', '1', 'Roman'), ('31', '1', 'Schmidt'), ('32', '1', 'Seat'), ('33', '1', 'Skoda'), ('34', '1', 'Steyr'), ('35', '1', 'Suzuki'), ('36', '1', 'Toyota'), ('37', '1', 'Unimog'), ('38', '1', 'Volkswagen'), ('39', '1', 'Volvo'), ('40', '1', 'Altele'), ('41', '2', 'Daewoo'), ('42', '2', 'DAF'), ('43', '2', 'Demag'), ('44', '2', 'Faul'), ('45', '2', 'Ford'), ('46', '2', 'Freightliner'), ('47', '2', 'Ginaf'), ('48', '2', 'Grove'), ('49', '2', 'Hako'), ('50', '2', 'HN Schorling'), ('51', '2', 'Iveco'), ('52', '2', 'Kamaz'), ('53', '2', 'Kenworth'), ('54', '2', 'Liebherr'), ('55', '2', 'Mack'), ('56', '2', 'Magirus-Deutz'), ('57', '2', 'Man'), ('58', '2', 'Mercedes-Benz'), ('59', '2', 'Mitsubishi'), ('60', '2', 'Nissan'), ('61', '2', 'Opel'), ('62', '2', 'Palfinger'), ('63', '2', 'Peterbilt'), ('64', '2', 'Peugeot'), ('65', '2', 'Renault'), ('66', '2', 'Roman'), ('67', '2', 'Scania'), ('68', '2', 'Schmidt'), ('69', '2', 'Skoda'), ('70', '2', 'Steyr'), ('71', '2', 'Tatra'), ('72', '2', 'Toyota'), ('73', '2', 'Unimog'), ('74', '2', 'Volkswagen'), ('75', '2', 'Volvo'), ('76', '2', 'Yanmar'), ('77', '2', 'Altele'), ('78', '3', 'Ackermann'), ('79', '3', 'Agados'), ('80', '3', 'Ahlmann'), ('81', '3', 'Airstream'), ('82', '3', 'Algema'), ('83', '3', 'Annaburger'), ('84', '3', 'Anssems'), ('85', '3', 'Atec'), ('86', '3', 'Auwarter'), ('87', '3', 'Barthau'), ('88', '3', 'Benalu'), ('89', '3', 'Berger'), ('90', '3', 'Blomenrohr'), ('91', '3', 'Blomert'), ('92', '3', 'Blyss'), ('93', '3', 'BNG'), ('94', '3', 'Bockmann'), ('95', '3', 'Borco-Hohns'), ('96', '3', 'Brandl'), ('97', '3', 'Brenderup'), ('98', '3', 'Brian James'), ('99', '3', 'Broshuis'), ('100', '3', 'Bunge'), ('101', '3', 'Carnehl'), ('102', '3', 'Chereau'), ('103', '3', 'Cheval Liberte'), ('104', '3', 'Daltec'), ('105', '3', 'Dinkel'), ('106', '3', 'Doll'), ('107', '3', 'Eduard'), ('108', '3', 'ES GE'), ('109', '3', 'Excalibur'), ('110', '3', 'Faymonville'), ('111', '3', 'Feber'), ('112', '3', 'Feldbinder'), ('113', '3', 'Fitzel'), ('114', '3', 'Fliegl'), ('115', '3', 'Floor'), ('116', '3', 'Frankenstein'), ('117', '3', 'Fruhauf'), ('118', '3', 'General Trailer'), ('119', '3', 'Gerden'), ('120', '3', 'Goldhofer'), ('121', '3', 'Groeneweghen'), ('122', '3', 'Hapert'), ('123', '3', 'Heinemann'), ('124', '3', 'Hendricks'), ('125', '3', 'Henra'), ('126', '3', 'HKM'), ('127', '3', 'Hoffmann'), ('128', '3', 'Homar'), ('129', '3', 'HRD'), ('130', '3', 'Huffermann'), ('131', '3', 'Hulco'), ('132', '3', 'Humbaur'), ('133', '3', 'Hutner'), ('134', '3', 'Ifor Williams'), ('135', '3', 'Jotha'), ('136', '3', 'Kaiser'), ('137', '3', 'Kassbohrer'), ('138', '3', 'Kel-Berg'), ('139', '3', 'Kempf'), ('140', '3', 'Klagie'), ('141', '3', 'Knapen'), ('142', '3', 'Koch'), ('143', '3', 'Kogel'), ('144', '3', 'Kotschenreuther'), ('145', '3', 'Kraker'), ('146', '3', 'Kramer'), ('147', '3', 'Kroeger'), ('148', '3', 'Krone'), ('149', '3', 'Krukenmeier'), ('150', '3', 'Lafaro'), ('151', '3', 'Lag'), ('152', '3', 'Lamberet'), ('153', '3', 'Langendorf'), ('154', '3', 'Lecinena'), ('155', '3', 'LeciTrailer'), ('156', '3', 'Ley'), ('157', '3', 'LinTrailers'), ('158', '3', 'Luck'), ('159', '3', 'Magyar'), ('160', '3', 'MEGA'), ('161', '3', 'Meierling'), ('162', '3', 'Meiller'), ('163', '3', 'Menci'), ('164', '3', 'Merker'), ('165', '3', 'Meusburger'), ('166', '3', 'Montenegro'), ('167', '3', 'Moslein'), ('168', '3', 'Muller-Mitteltal'), ('169', '3', 'Neptun'), ('170', '3', 'NFP-Eurotrailer'), ('171', '3', 'Niewiadow'), ('172', '3', 'Nooteboom'), ('173', '3', 'Obermaier'), ('174', '3', 'Orten'), ('175', '3', 'Orthaus'), ('176', '3', 'Pacton'), ('177', '3', 'Palfinger'), ('178', '3', 'Pezzaioli'), ('179', '3', 'Pongratz'), ('180', '3', 'Renders'), ('181', '3', 'Reuter'), ('182', '3', 'ROHR'), ('183', '3', 'ROKA'), ('184', '3', 'Samro'), ('185', '3', 'Saris'), ('186', '3', 'SAXAS'), ('187', '3', 'Scheuerle'), ('188', '3', 'Schmidt'), ('189', '3', 'Schmidt Cargobull'), ('190', '3', 'Schrader'), ('191', '3', 'Schwarzmuller'), ('192', '3', 'Seico'), ('193', '3', 'SEKA'), ('194', '3', 'Sluis'), ('195', '3', 'Sommer'), ('196', '3', 'Spitzer'), ('197', '3', 'Stas'), ('198', '3', 'Stedele'), ('199', '3', 'Stema'), ('200', '3', 'Talson'), ('201', '3', 'Tempus'), ('202', '3', 'Thiel'), ('203', '3', 'THULE'), ('204', '3', 'Tijhof'), ('205', '3', 'TPV'), ('206', '3', 'Trailor'), ('207', '3', 'Treibner'), ('208', '3', 'Unsinn'), ('209', '3', 'Van Eck'), ('210', '3', 'Vanhool'), ('211', '3', 'Variotrail'), ('212', '3', 'Vezeko'), ('213', '3', 'Volkswagen'), ('214', '3', 'Voss'), ('215', '3', 'Wackenhut'), ('216', '3', 'Wagner'), ('217', '3', 'Wecon'), ('218', '3', 'Westfalia'), ('219', '3', 'Wielton'), ('220', '3', 'WM Meyer'), ('221', '3', 'Woodford'), ('222', '3', 'Womann'), ('223', '3', 'XXTrail'), ('224', '3', 'ZASLAW TRAILIS'), ('225', '3', 'Z-Trailer'), ('226', '3', 'Altele'), ('227', '5', 'DAF'), ('228', '5', 'Drogmoller'), ('229', '5', 'EOS'), ('230', '5', 'Evobus'), ('231', '5', 'Fiat'), ('232', '5', 'Ford'), ('233', '5', 'Ikarus'), ('234', '5', 'Irisbus'), ('235', '5', 'Irizar'), ('236', '5', 'Isuzu'), ('237', '5', 'Iveco'), ('238', '5', 'King Long'), ('239', '5', 'Magirus Deutz'), ('240', '5', 'Man'), ('241', '5', 'Mercedes-Benz'), ('242', '5', 'Neoplan'), ('243', '5', 'Peugeot'), ('244', '5', 'Renault'), ('245', '5', 'Robur'), ('246', '5', 'Roman'), ('247', '5', 'Scania'), ('248', '5', 'Setra'), ('249', '5', 'Solaris'), ('250', '5', 'Temsa'), ('251', '5', 'Vanhool'), ('252', '5', 'VDL'), ('253', '5', 'Volkswagen'), ('254', '5', 'Volvo'), ('255', '5', 'Altele'), ('256', '6', 'ABG'), ('257', '6', 'Ahlmann'), ('258', '6', 'Allrad'), ('259', '6', 'Ammann'), ('260', '6', 'Atlas'), ('261', '6', 'Atlet'), ('262', '6', 'Ausa'), ('263', '6', 'Barford'), ('264', '6', 'Belle'), ('265', '6', 'Benaty'), ('266', '6', 'Blizzer'), ('267', '6', 'Bobcat'), ('268', '6', 'Bomag'), ('269', '6', 'BT'), ('270', '6', 'BV'), ('271', '6', 'Case'), ('272', '6', 'Cat'), ('273', '6', 'Caterpillar'), ('274', '6', 'Cesab'), ('275', '6', 'Clark'), ('276', '6', 'Crown'), ('277', '6', 'Daewoo'), ('278', '6', 'Dantruck'), ('279', '6', 'Demag'), ('280', '6', 'Denyo'), ('281', '6', 'Destas'), ('282', '6', 'Ditch Witch'), ('283', '6', 'Doosan'), ('284', '6', 'Dresser'), ('285', '6', 'Dynapac'), ('286', '6', 'Fadroma'), ('287', '6', 'Faun'), ('288', '6', 'Fendt'), ('289', '6', 'Fermec'), ('290', '6', 'Fiat'), ('291', '6', 'Filnay'), ('292', '6', 'Ford'), ('293', '6', 'Fuchs'), ('294', '6', 'Furukawa'), ('295', '6', 'Gehl'), ('296', '6', 'Genie'), ('297', '6', 'Genus'), ('298', '6', 'Grove'), ('299', '6', 'Hako'), ('300', '6', 'Hamm'), ('301', '6', 'Hangcha'), ('302', '6', 'Hanix'), ('303', '6', 'Hanomag'), ('304', '6', 'Hartl'), ('305', '6', 'Haulotte'), ('306', '6', 'HC'), ('307', '6', 'Heden'), ('308', '6', 'Hiab'), ('309', '6', 'Hinowa'), ('310', '6', 'Hitachi'), ('311', '6', 'HSW'), ('312', '6', 'Hydrema'), ('313', '6', 'Hyster'), ('314', '6', 'Hyundai'), ('315', '6', 'Jamma'), ('316', '6', 'JCB'), ('317', '6', 'John Deere'), ('318', '6', 'Jungheinrich'), ('319', '6', 'Kaercher'), ('320', '6', 'Kalmar'), ('321', '6', 'Kobelco'), ('322', '6', 'Komatsu'), ('323', '6', 'Konig'), ('324', '6', 'Kraftwelle'), ('325', '6', 'Kramerv'), ('326', '6', 'Kubota'), ('327', '6', 'Libra'), ('328', '6', 'Liebherr'), ('329', '6', 'Linde'), ('330', '6', 'Luna'), ('331', '6', 'Manitou'), ('332', '6', 'Mecalac'), ('333', '6', 'Merlo'), ('334', '6', 'Mitsubishi'), ('335', '6', 'Moxy'), ('336', '6', 'MTZ'), ('337', '6', 'Neuson'), ('338', '6', 'New Holland'), ('339', '6', 'Nissan'), ('340', '6', 'O&K'), ('341', '6', 'Ostrówek'), ('342', '6', 'Palfinger'), ('343', '6', 'Paus'), ('344', '6', 'Powerscreen'), ('345', '6', 'Pramac'), ('346', '6', 'Putzmeister'), ('347', '6', 'Rammax'), ('348', '6', 'Renault'), ('349', '6', 'Rocla'), ('350', '6', 'Samsung'), ('351', '6', 'Schaeff'), ('352', '6', 'Schäffer'), ('353', '6', 'Socage'), ('354', '6', 'Steinbock'), ('355', '6', 'Still'), ('356', '6', 'Strassmayr'), ('357', '6', 'SUNWARD'), ('358', '6', 'Svettruck'), ('359', '6', 'Takeuchi'), ('360', '6', 'TCM'), ('361', '6', 'Terberg'), ('362', '6', 'Terex'), ('363', '6', 'Tesab'), ('364', '6', 'Toyota'), ('365', '6', 'Unimog'), ('366', '6', 'Venieri'), ('367', '6', 'Vermeer'), ('368', '6', 'Vogele'), ('369', '6', 'Volvo'), ('370', '6', 'Wacker'), ('371', '6', 'WEBER'), ('372', '6', 'Wirtgen'), ('373', '6', 'Yale'), ('374', '6', 'Yamaguchi'), ('375', '6', 'Yanmar'), ('376', '6', 'Zeppelin'), ('377', '6', 'Zettelmeye'), ('378', '6', 'Altele'), ('379', '4', 'Accord'), ('380', '4', 'Agco / Massey Ferguson'), ('381', '4', 'Amazone'), ('382', '4', 'Avant Tecno'), ('383', '4', 'Becker'), ('384', '4', 'Bergmann'), ('385', '4', 'Branson'), ('386', '4', 'Busatis'), ('387', '4', 'BvL – Van Lengerich'), ('388', '4', 'Carraro'), ('389', '4', 'Case'), ('390', '4', 'Claas'), ('391', '4', 'Deutz Fahr'), ('392', '4', 'Doll'), ('393', '4', 'Ducker'), ('394', '4', 'Duvelsdorf'), ('395', '4', 'Eberhardt'), ('396', '4', 'Eicher'), ('397', '4', 'Fahr'), ('398', '4', 'Fella'), ('399', '4', 'Fendt'), ('400', '4', 'Fiat'), ('401', '4', 'Ford'), ('402', '4', 'Fortschirtt'), ('403', '4', 'Foton'), ('404', '4', 'Frost'), ('405', '4', 'Gaspardo'), ('406', '4', 'Goldoni'), ('407', '4', 'Grimme'), ('408', '4', 'Guldner'), ('409', '4', 'Gutbrod'), ('410', '4', 'Hako'), ('411', '4', 'Hanomag'), ('412', '4', 'Hanssia'), ('413', '4', 'Holder'), ('414', '4', 'Howard'), ('415', '4', 'IHC'), ('416', '4', 'Iseki'), ('417', '4', 'Jacobsen'), ('418', '4', 'JCB'), ('419', '4', 'John Deere'), ('420', '4', 'Kioti'), ('421', '4', 'Kramer'), ('422', '4', 'Krone'), ('423', '4', 'Kubota'), ('424', '4', 'Lamborghini'), ('425', '4', 'Landini'), ('426', '4', 'Landsberg'), ('427', '4', 'Lanz'), ('428', '4', 'Lely'), ('429', '4', 'Lemken'), ('430', '4', 'MAN'), ('431', '4', 'Maschio'), ('432', '4', 'Masswy Ferguson'), ('433', '4', 'McCormick'), ('434', '4', 'Mengele'), ('435', '4', 'Mercedes-Benz'), ('436', '4', 'Mitsubishi'), ('437', '4', 'Multicar'), ('438', '4', 'New Holland'), ('439', '4', 'Niemeyer'), ('440', '4', 'Porsche'), ('441', '4', 'Pottinger'), ('442', '4', 'PZ-Vicon'), ('443', '4', 'Rabe'), ('444', '4', 'Rau'), ('445', '4', 'Rauch'), ('446', '4', 'Reforwerke Wels'), ('447', '4', 'Regent'), ('448', '4', 'Renault'), ('449', '4', 'Same'), ('450', '4', 'Schaffer'), ('451', '4', 'Schluter'), ('452', '4', 'Schmidt'), ('453', '4', 'Steyr'), ('454', '4', 'Strautmann'), ('455', '4', 'Unimog'), ('456', '4', 'Universal'), ('457', '4', 'Valtra'), ('458', '4', 'Vogel&Noot'), ('459', '4', 'Weidemann'), ('460', '4', 'Zetor'), ('461', '4', 'Altele');
COMMIT;

-- ----------------------------
--  Table structure for `cars_model2`
-- ----------------------------
DROP TABLE IF EXISTS `cars_model2`;
CREATE TABLE `cars_model2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(3) NOT NULL,
  `car_make` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `cars_model2`
-- ----------------------------
BEGIN;
INSERT INTO `cars_model2` VALUES ('9', '2', 'DAF'), ('10', '2', 'Demag'), ('11', '2', 'Iveco'), ('12', '2', 'Man'), ('13', '2', 'Mercedes-Benz'), ('14', '2', 'Renault'), ('15', '2', 'Opel');
COMMIT;

-- ----------------------------
--  Table structure for `categories`
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `categories`
-- ----------------------------
BEGIN;
INSERT INTO `categories` VALUES ('1', 'Dube'), ('2', 'Camioane'), ('3', 'Remorci'), ('4', 'Utilaje Agricole'), ('5', 'Autobuse'), ('6', 'Utilaje Constructii');
COMMIT;

-- ----------------------------
--  Table structure for `newsletter_logs`
-- ----------------------------
DROP TABLE IF EXISTS `newsletter_logs`;
CREATE TABLE `newsletter_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `advertiser_id` int(11) NOT NULL,
  `email_type` varchar(20) NOT NULL DEFAULT '',
  `dateadd` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `newsletter_logs`
-- ----------------------------
BEGIN;
INSERT INTO `newsletter_logs` VALUES ('6', '1', 'inactivate_ad', '2015-05-08 11:57:45'), ('14', '1', 'inactivate_ad', '2015-05-12 09:56:44');
COMMIT;

-- ----------------------------
--  Table structure for `parts_main_categ`
-- ----------------------------
DROP TABLE IF EXISTS `parts_main_categ`;
CREATE TABLE `parts_main_categ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `parts_main_categ`
-- ----------------------------
BEGIN;
INSERT INTO `parts_main_categ` VALUES ('1', 'Air conditioning / Heating'), ('2', 'Body'), ('3', 'Brakes'), ('4', 'Dismantling object'), ('5', 'Doors'), ('6', 'Electric / Transmitter / Databox / Sensor'), ('7', 'Engine'), ('8', 'Exhaust/cleaning'), ('9', 'Exterior details'), ('10', 'Front details'), ('11', 'Fuel'), ('12', 'Gear box / Drive axle / Middle axle'), ('13', 'Hydraulic/Traction'), ('14', 'Instruments / Electric switches'), ('15', 'Interior'), ('16', 'Lock / Alarm'), ('17', 'Other'), ('18', 'Rearview Mirror'), ('19', 'Repair sheet metal / Consumables'), ('20', 'Spoilers / Wipers'), ('21', 'Steering wheel / Axle / Lever / Pedal'), ('22', 'Vehicle exterior / Suspension'), ('23', 'Vehicle front / Springs / Steering'), ('24', 'Wheels / Tires / Accessories'), ('25', 'Windows');
COMMIT;

-- ----------------------------
--  Table structure for `parts_sub_categ`
-- ----------------------------
DROP TABLE IF EXISTS `parts_sub_categ`;
CREATE TABLE `parts_sub_categ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `categ_id` int(3) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `parts_sub_categ`
-- ----------------------------
BEGIN;
INSERT INTO `parts_sub_categ` VALUES ('1', '7', 'Ax Came'), ('2', '7', 'Cylinder Head Fuel'), ('3', '7', 'Cam axle housing'), ('4', '7', 'Camshaft adjusting valve'), ('5', '7', 'Cylinder Head Diesel'), ('6', '7', 'Cylinder Head Fuel'), ('7', '7', 'Engine Diesel'), ('8', '7', 'Alternator bracket / Accessories'), ('9', '8', 'Exhaust manifold'), ('10', '8', 'Lambda probe'), ('11', '8', 'EGR Valve');
COMMIT;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user`
-- ----------------------------
BEGIN;
INSERT INTO `user` VALUES ('1', null, 'ileavalentin@gmail.com', 'Vali Ilea', '$2y$14$IPcJvB17aK5Gw5MEeH43uOY4PzmkdUri2nTSygCkSkky4FVMjs36e', '1', 'f34ecd2699b474f159e7924d59faaabd'), ('17', null, 'ileavalentin2@gmail.com', null, '$2y$14$gPJigLBEXDI2/oEn2vqFAOqeAHNDTKMAwO30/NOKq.3qhzhYzknCW', '1', null), ('18', null, 'ileavalentin3@gmail.com', null, '$2y$14$Dx.W2sIL/yBewETiAZ5CYe4CV5jxzJuhc4Vn4cR1cchU9Uue7oJEy', '1', null), ('19', null, 'ileavalentin4@gmail.com', null, '$2y$14$irpnXJpVUzLR7vPjqy/D1.uu4VWGYueT3yrESeZn0NUiTrYj5rBbi', '1', null), ('20', null, 'ileavalentin5@gmail.com', null, '$2y$14$vgmPWt4NjZ5sUkAlOo1GXu.d0/TS3q6stSN8YyNMXhsYHN5RBHw0K', '1', null), ('21', null, 'ileavalentin6@gmail.com', null, '$2y$14$6MsnUcu3i0rxAxFH52t2auTU0PS575XRKw5x47qtCdZ0oMChEcE46', '1', null), ('22', null, 'ileavalentin7@gmail.com', null, '$2y$14$sDGbWN4ZhhBvVsqc.DSd1OadlkgRc2Uj6lk1R6qbhNrs3P6GqFlAC', '1', null), ('23', null, 'ileavalentin8@gmail.com', null, '$2y$14$6.widPbSF1RIvM8QiPU8KeVbmlKyX1JA/frX8nGbBMRJrhS7grBBe', '1', null), ('24', null, 'ileavalentin9@gmail.com', null, '$2y$14$sTPuoYcJ6tzzjF5DR.sDyOks7Wym5vmBEnmFl1eNNFRb28I6ROv56', '1', null), ('25', null, 'vali1@gmail.com', null, '$2y$14$LqlZnhMEPbm0terax.vxPeemvHeI4dAettZwae5nivWc6ZprtxFcK', '1', null), ('26', null, 'vali1@tirbox.ro', null, '$2y$14$SBCtToVhZO9NBP3JnQdWYuPVZ62apiwpXp/wYfM5cOMprQSFWclTW', '1', null), ('27', null, 'vali2@tb.ro', null, '$2y$14$CXBe804C61bSh/oMFOcBceFRafe/z8pEnutLRPpL3KaaApr48HX.m', '1', null), ('28', null, 'vali3@tb.ro', null, '$2y$14$eHFm6lL/vZus2/uWaoGfKefxbr2aQ19ib7PHEtRjzdngXHT7ynsx2', '1', null), ('29', null, 'v3@tb.ro', null, '$2y$14$3PoLUAVhV.aibzbAdkhNqevCqfF7osMXfshMIruGqFhGeWPeNtqke', '1', null), ('38', null, 'v4@tb.ro', null, '$2y$14$Rrd1WzvXwkch9tq0ifJVk./jBzRmzz8OmQViuehKrHS2bp2ZRSm3i', '1', null), ('39', null, 'ileavalentin930@yahoo.co.uk', 'Valentin Ilea', '$2y$14$FEyWCqpNVxRW03cPFHoe7.eBGC6UE/VVFrcKMsyNEd4oQEPFAMxTa', '1', null), ('40', null, 'v5@tb.ro', null, '$2y$14$oRa63WtaPwrghlnkZp5SxOzdI0dRTC8Nkn9xp9yRjecWAcNjHt6Su', '1', null);
COMMIT;

-- ----------------------------
--  Table structure for `user_forgot_pass`
-- ----------------------------
DROP TABLE IF EXISTS `user_forgot_pass`;
CREATE TABLE `user_forgot_pass` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL DEFAULT '',
  `hash` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user_forgot_pass`
-- ----------------------------
BEGIN;
INSERT INTO `user_forgot_pass` VALUES ('4', 'ileavalentin@gmail.com', '7822a61bbce008a1ffe9a7018fb0d5cb78053839');
COMMIT;

-- ----------------------------
--  Table structure for `user_provider`
-- ----------------------------
DROP TABLE IF EXISTS `user_provider`;
CREATE TABLE `user_provider` (
  `user_id` int(11) NOT NULL,
  `provider_id` varchar(50) NOT NULL,
  `provider` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`provider_id`),
  UNIQUE KEY `provider_id` (`provider_id`,`provider`),
  CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user_provider`
-- ----------------------------
BEGIN;
INSERT INTO `user_provider` VALUES ('39', '10205761714497578', 'facebook');
COMMIT;

-- ----------------------------
--  Table structure for `user_role`
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roleId` varchar(255) NOT NULL DEFAULT '',
  `is_default` tinyint(1) NOT NULL,
  `parent_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user_role`
-- ----------------------------
BEGIN;
INSERT INTO `user_role` VALUES ('1', 'user', '0', null), ('2', 'parcauto', '0', null), ('3', 'admin', '0', null), ('10', 'guest', '1', null);
COMMIT;

-- ----------------------------
--  Table structure for `user_role_linker`
-- ----------------------------
DROP TABLE IF EXISTS `user_role_linker`;
CREATE TABLE `user_role_linker` (
  `user_id` int(11) unsigned NOT NULL,
  `role_id` varchar(11) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `user_role_linker`
-- ----------------------------
BEGIN;
INSERT INTO `user_role_linker` VALUES ('1', 'parcauto'), ('17', 'parcauto'), ('18', 'parcauto'), ('19', 'parcauto'), ('20', 'parcauto'), ('21', 'parcauto'), ('22', 'parcauto'), ('23', 'parcauto'), ('24', 'parcauto'), ('25', 'parcauto'), ('26', 'parcauto'), ('27', 'parcauto'), ('28', 'parcauto'), ('29', 'parcauto'), ('38', 'parcauto'), ('39', 'parcauto'), ('40', 'parcauto');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
