/*
Navicat MySQL Data Transfer

Source Server         : Combell_newserver
Source Server Version : 50623
Source Host           : 178.208.48.50:3306
Source Database       : dcms_groupdc_be

Target Server Type    : MYSQL
Target Server Version : 50623
File Encoding         : 65001

Date: 2016-05-20 08:48:09
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `countries`
-- ----------------------------
DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country_name` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of countries
-- ----------------------------
INSERT INTO `countries` VALUES ('1', 'BE', 'België', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `countries` VALUES ('2', 'NL', 'Nederland', null, '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO `countries` VALUES ('3', 'FR', 'France', null, '2015-01-05 11:52:37', '2015-01-05 11:52:37');
INSERT INTO `countries` VALUES ('4', 'DE', 'Deutschland', null, '2015-01-05 11:52:55', '2015-01-05 11:52:55');
INSERT INTO `countries` VALUES ('5', 'es', 'Espagna', 'bartr', '2015-06-18 15:02:43', '2015-06-18 15:02:43');
INSERT INTO `countries` VALUES ('6', 'it', 'Italy', 'bartr', '2015-06-18 15:02:50', '2015-06-18 15:02:50');
INSERT INTO `countries` VALUES ('7', 'int', 'International', 'bartr', '2015-06-18 15:03:00', '2015-06-18 15:03:00');
INSERT INTO `countries` VALUES ('8', 'US', 'United States', 'bartr', '2015-06-18 15:15:03', '2015-06-18 15:15:03');
INSERT INTO `countries` VALUES ('9', 'LU', 'Luxembourg', 'bartr', '2016-03-22 14:41:36', '2016-03-22 14:41:36');

-- ----------------------------
-- Table structure for `languages`
-- ----------------------------
DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(11) unsigned DEFAULT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `FK_langcountryid` (`country_id`),
  CONSTRAINT `languages_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of languages
-- ----------------------------
INSERT INTO `languages` VALUES ('1', '1', 'nl', 'Nederlands', 'BE', null, '0000-00-00 00:00:00', '2015-01-05 13:02:08');
INSERT INTO `languages` VALUES ('2', '1', 'fr', 'Frans', 'BE', null, '0000-00-00 00:00:00', '2015-01-05 13:02:02');
INSERT INTO `languages` VALUES ('3', '2', 'nl', 'Nederlands', 'NL', null, '2014-07-16 10:45:19', '2015-01-05 13:02:16');
INSERT INTO `languages` VALUES ('6', '3', 'fr', 'Français', 'FR', null, '2015-01-05 11:58:16', '2015-01-05 13:01:29');
INSERT INTO `languages` VALUES ('7', '4', 'de', 'Deutsch', 'DE', null, '2015-01-05 11:58:23', '2015-01-05 13:01:37');
INSERT INTO `languages` VALUES ('8', '5', 'es', 'Spanish', 'ES', 'bartr', '2015-06-19 16:06:05', '2015-06-19 16:06:05');
INSERT INTO `languages` VALUES ('9', '6', 'it', 'Italian', 'IT', 'bartr', '2015-06-19 16:07:32', '2015-06-19 16:07:32');
INSERT INTO `languages` VALUES ('10', '8', 'en', 'English', 'US', 'bartr', '2015-06-19 16:07:43', '2015-06-19 16:07:43');
INSERT INTO `languages` VALUES ('11', '7', 'en', 'English', 'INT', 'bartr', '2015-06-19 16:07:55', '2015-06-19 16:07:55');

-- ----------------------------
-- Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('22', 'admin@domain.com', 'admin', 'administrator', '2016-05-20 06:47:11', '2016-05-20 06:47:27', 'admin', '$2y$10$TYL4nAsgd6Zj4W7wDVkdB.Pvj3z92cw.Md4Z/C91g8FcNhW4Kftji', null, '2016-05-20 06:47:27');
