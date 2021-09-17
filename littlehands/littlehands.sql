-- MySQL dump 10.13  Distrib 8.0.19, for osx10.14 (x86_64)
--
-- Host: 127.0.0.1    Database: littlehands
-- ------------------------------------------------------
-- Server version	8.0.19-debug

-- 当ファイル内のINSERT文の一部は　「A5:SQL Mk-2」https://a5m2.mmatsubara.com/　にて自動生成されたものです

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @old_autocommit=@@autocommit;

--
-- Current Database: `littlehands`
--

/*!40000 DROP DATABASE IF EXISTS `littlehands`*/;

DROP DATABASE IF EXISTS `littlehands`;
CREATE DATABASE `littlehands` DEFAULT CHARACTER SET utf8mb4;

USE `littlehands`;





--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users`
    (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `nickname` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'ニックネーム',
        `firstname` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名前（姓）',
        `lastname` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '名前（名）',
        `firstname_kana` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'フリガナ（姓）',
        `lastname_kana` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'フリガナ（名）',
        `gender` TINYINT UNSIGNED DEFAULT 0 COMMENT '性別',
        `birth` DATE DEFAULT NULL COMMENT '生年月日',
        `tel` VARCHAR(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '電話番号',
        `self_introduction` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '自己紹介',
        `email` VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT 'メールアドレス',
        `password` VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT 'パスワード',
        `thumbnail_path` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'サムネイルパス',
        `role` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT '権限',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '登録日時',
        `updated_at` DATETIME DEFAULT NULL COMMENT '更新日時',
        `del_flg` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '論理削除',
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT 'ユーザ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--
-- ORDER BY:  `ID`

set autocommit=0;
INSERT INTO users(id,nickname,firstname,lastname,firstname_kana,lastname_kana,gender,birth,tel,self_introduction,email,password,thumbnail_path,`role`) VALUES (1,'ユーザ1','ゆーざ_1','たろう_1','ユーザ_1','タロウ_1','1','2020/01/01','09012345678','サンプル紹介_1','abcd1234@gmail.com','$2y$10$hVKCmyW4m606oBLe3M7Dy.ieM3Tcl1NQ1akx/I7yZVLAlv1Oqt6nG','img_1','0');
INSERT INTO users(id,nickname,firstname,lastname,firstname_kana,lastname_kana,gender,birth,tel,self_introduction,email,password,thumbnail_path,`role`) VALUES (2,'ユーザ2','ゆーざ_2','はなこ_2','ユーザ_2','ハナコ_2','2','2020/02/02','08012345678','サンプル紹介_2','qwer1234@gmail.com','$2y$10$xVTyO5s.hswxqPjpWtG.u.foD0FBQg82w4QLix9QpihgktqpAOBwu','img_2','0');
INSERT INTO users(id,nickname,firstname,lastname,firstname_kana,lastname_kana,gender,birth,tel,self_introduction,email,password,thumbnail_path,`role`) VALUES (3,'ユーザ3','ゆーざ_3','いちろう_3','ユーザ_3','イチロウ_3','1','2020/03/03','07012345678','サンプル紹介_3','zxcv1234@gmail.com','$2y$10$QS2vCdsopeS9sxkXyBjhz.mhTaOTZHcJYiIi2XzKXNm5PD3UcBdYW','img_3','0');
commit;





--
-- Table structure for table `hands`
--

DROP TABLE IF EXISTS `hands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hands`
    (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
        `hand_type` TINYINT UNSIGNED  NOT NULL COMMENT 'おてつだいタイプ',
        `title` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'タイトル',
        `fee` INT(11) UNSIGNED NOT NULL COMMENT '報酬',
        `body` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '本文',
        `photos_path` VARCHAR(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '写真パス',
        `page_view` INT(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '閲覧数',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '登録日時',
        `updated_at` DATETIME DEFAULT NULL COMMENT '更新日時',
        `del_flg` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '論理削除',
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT 'おてつだい';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hands`
--
-- ORDER BY:  `ID`

set autocommit=0;
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (1,'2','1','タイトル1　てつだって','10','本文1　てつだって','img/2','5');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (2,'2','2','タイトル2　てつだうよ','9','本文2　てつだうよ','img/2','6');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (3,'2','1','タイトル3　てつだって','8','本文3　てつだって','img/2','8');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (4,'2','1','タイトル4　てつだって','7','本文4　てつだって','img/2','10');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (5,'2','1','タイトル5　てつだって','6','本文5　てつだって','img/2','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (6,'2','2','タイトル6　てつだうよ','5','本文6　てつだうよ','img/2','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (7,'2','1','タイトル7　てつだって','4','本文7　てつだって','img/2','1');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (8,'2','1','タイトル8　てつだって','3','本文8　てつだって','img/2','50');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (9,'2','1','タイトル9　てつだって','2','本文9　てつだって','img/2','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (10,'2','1','タイトル10　てつだって','1','本文10　てつだって','img/2','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (11,'2','1','タイトル11　てつだって','100','本文11　てつだって','img/2','9');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (12,'2','2','タイトル12　てつだうよ','101','本文12　てつだうよ','img/2','15');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (13,'2','1','タイトル13　てつだって','102','本文13　てつだって','img/2','20');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (14,'2','1','タイトル14　てつだって','103','本文14　てつだって','img/2','34');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (15,'2','1','タイトル15　てつだって','104','本文15　てつだって','img/2','21');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (16,'2','1','タイトル16　てつだって','105','本文16　てつだって','img/2','56');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (17,'2','2','タイトル17　てつだうよ','106','本文17　てつだうよ','img/2','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (18,'2','2','タイトル18　てつだうよ','105','本文18　てつだうよ','img/2','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (19,'2','2','タイトル19　てつだうよ','108','本文19　てつだうよ','img/2','9');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (20,'2','1','タイトル20　てつだって','109','本文20　てつだって','img/2','8');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (21,'2','1','タイトル21　てつだって','110','本文21　てつだって','img/2','4');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (22,'2','1','タイトル22　てつだって','99999999','本文22　てつだって','img/2','1');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (23,'2','1','タイトル23　てつだって','100000','本文23　てつだって','img/2','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (24,'2','2','タイトル24　てつだうよ','5000','本文24　てつだうよ','img/2','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (25,'2','2','タイトル25　てつだうよ','0','本文25　てつだうよ','img/2','5');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (26,'3','1','タイトル26　てつだって','10','本文26　てつだって','img/3','5');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (27,'3','2','タイトル27　てつだうよ','9','本文27　てつだうよ','img/3','6');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (28,'3','1','タイトル28　てつだって','8','本文28　てつだって','img/3','8');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (29,'3','1','タイトル29　てつだって','7','本文29　てつだって','img/3','10');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (30,'3','1','タイトル30　てつだって','6','本文30　てつだって','img/3','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (31,'3','2','タイトル31　てつだうよ','5','本文31　てつだうよ','img/3','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (32,'3','1','タイトル32　てつだって','4','本文32　てつだって','img/3','1');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (33,'3','1','タイトル33　てつだって','3','本文33　てつだって','img/3','50');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (34,'3','1','タイトル34　てつだって','2','本文34　てつだって','img/3','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (35,'3','1','タイトル35　てつだって','1','本文35　てつだって','img/3','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (36,'3','1','タイトル36　てつだって','100','本文36　てつだって','img/3','9');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (37,'3','2','タイトル37　てつだうよ','101','本文37　てつだうよ','img/3','15');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (38,'3','1','タイトル38　てつだって','102','本文38　てつだって','img/3','20');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (39,'3','1','タイトル39　てつだって','103','本文39　てつだって','img/3','34');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (40,'3','1','タイトル40　てつだって','104','本文40　てつだって','img/3','21');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (41,'3','1','タイトル41　てつだって','105','本文41　てつだって','img/3','56');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (42,'3','2','タイトル42　てつだうよ','106','本文42　てつだうよ','img/3','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (43,'3','2','タイトル43　てつだうよ','105','本文43　てつだうよ','img/3','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (44,'3','2','タイトル44　てつだうよ','108','本文44　てつだうよ','img/3','9');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (45,'3','1','タイトル45　てつだって','109','本文45　てつだって','img/3','8');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (46,'3','1','タイトル46　てつだって','110','本文46　てつだって','img/3','4');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (47,'3','1','タイトル47　てつだって','99999999','本文47　てつだって','img/3','1');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (48,'3','1','タイトル48　てつだって','100000','本文48　てつだって','img/3','2');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (49,'3','2','タイトル49　てつだうよ','5000','本文49　てつだうよ','img/3','0');
INSERT INTO hands(id,user_id,hand_type,title,fee,body,photos_path,page_view) VALUES (50,'3','2','タイトル50　てつだうよ','0','本文50　てつだうよ','img/3','5');
commit;





--
-- Table structure for table `addresses`
--

DROP TABLE IF EXISTS `addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `addresses`
    (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ユーザID',
        `hand_id` INT(11) UNSIGNED DEFAULT NULL COMMENT 'おてつだいID',
        `zip_code_1` VARCHAR(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '郵便番号1',
        `zip_code_2` VARCHAR(4) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '郵便番号2',
        `state` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '都道府県',
        `city` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '市区町村',
        `address1` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'その他住所',
        `address2` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '番地',
        `address3` VARCHAR(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '建物名',
        `lat` DOUBLE(9,6) DEFAULT NULL COMMENT '経度',
        `lng` DOUBLE(9,6) DEFAULT NULL COMMENT '緯度',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '登録日時',
        `updated_at` DATETIME DEFAULT NULL COMMENT '更新日時',
        `del_flg` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '論理削除',
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT '住所';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addresses`
--
-- ORDER BY:  `ID`

set autocommit=0;
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (1,'2','0','160','0023','東京都','新宿区','西新宿','３丁目７－26','222',35.684756,139.690575);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (2,'2','1','160','0023','東京都','新宿区','西新宿','１丁目１５－５','名無しビル',35.688736,139.697376);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (3,'2','2','160','0022','東京都','新宿区','新宿','４丁目１－９','新宿ビル 3F',35.688929,139.702598);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (4,'2','3','160','0023','東京都','新宿区','西新宿','１丁目１－５','ネルミビル17F',35.689204,139.699147);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (5,'2','4','160','0022','東京都','新宿区','新宿','２丁目７－３','新宿センターハイツ９F',35.689633,139.708184);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (6,'2','5','160','0022','東京都','新宿区','新宿','３丁目１－３２','',35.689755,139.706392);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (7,'2','6','160','0023','東京都','新宿区','西新宿','１丁目２５－１','新宿セントラルビルヂング50階',35.691958,139.695421);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (8,'2','7','160','0023','東京都','新宿区','歌舞伎町','１丁目１６－５','ドンキビル1F',35.693807,139.701765);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (9,'2','8','169','0072','東京都','新宿区','大久保','１丁目２－１７','',35.69917,139.707401);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (10,'2','9','160','0022','東京都','新宿区','新宿','２丁目７－３','',35.689633,139.708184);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (11,'2','10','160','0022','東京都','新宿区','新宿','１丁目８','',35.688728,139.711083);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (12,'2','11','151','0051','東京都','渋谷区','千駄ヶ谷','５丁目３０－５','',35.68575,139.704295);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (13,'2','12','151','0053','東京都','渋谷区','代々木','２丁目４４－１２','代々木第１ビル１０１号',35.682942,139.696441);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (14,'2','13','151','0053','東京都','渋谷区','代々木','１丁目５８－１','代々木ハイツ２１０',35.685042,139.700935);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (15,'2','14','160','0015','東京都','新宿区','大京町','２４','',35.684359,139.716988);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (16,'2','15','160','0015','東京都','新宿区','大京町','１２－９','大京ビル1F',35.684126,139.717842);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (17,'2','16','160','0016','東京都','新宿区','信濃町','20','花林ビルディング',35.683387,139.720212);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (18,'2','17','160','0016','東京都','新宿区','信濃町','34','',35.680142,139.720198);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (19,'2','18','164','0011','東京都','中野区','中央','2-2-31','中野Nビル5階',35.69759,139.68267);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (20,'2','19','160','0023','東京都','新宿区','西新宿','3丁目１０－１３','',35.685432,139.689143);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (21,'2','20','160','0023','東京都','新宿区','西新宿','５丁目１０－１０','',35.690781,139.686282);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (22,'2','21','160','0023','東京都','新宿区','西新宿','4丁目１０－９','',35.688796,139.686409);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (23,'2','22','160','0023','東京都','新宿区','西新宿','1-1-1','第一ビル111',35.692463,139.699609);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (24,'2','23','160','0023','東京都','新宿区','歌舞伎町','2-2-1-111','',35.694983,139.706375);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (25,'2','24','151','0053','東京都','渋谷区','代々木','3','代々木building901',35.683,139.694328);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (26,'2','25','160','0015','東京都','新宿区','大京町','15','',35.684992,139.717447);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (27,'3','0','160','0023','東京都','新宿区','西新宿','３丁目７－26','333',35.684756,139.690575);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (28,'3','26','160','0023','東京都','新宿区','西新宿','１丁目１５－５','名無しビル',35.688736,139.697376);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (29,'3','27','160','0022','東京都','新宿区','新宿','４丁目１－９','新宿ビル 3F',35.688929,139.702598);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (30,'3','28','160','0023','東京都','新宿区','西新宿','１丁目１－５','ネルミビル17F',35.689204,139.699147);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (31,'3','29','160','0022','東京都','新宿区','新宿','２丁目７－３','新宿センターハイツ９F',35.689633,139.708184);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (32,'3','30','160','0022','東京都','新宿区','新宿','３丁目１－３２','',35.689755,139.706392);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (33,'3','31','160','0023','東京都','新宿区','西新宿','１丁目２５－１','新宿セントラルビルヂング50階',35.691958,139.695421);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (34,'3','32','160','0023','東京都','新宿区','歌舞伎町','１丁目１６－５','ドンキビル1F',35.693807,139.701765);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (35,'3','33','169','0072','東京都','新宿区','大久保','１丁目２－１７','',35.69917,139.707401);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (36,'3','34','160','0022','東京都','新宿区','新宿','２丁目７－３','',35.689633,139.708184);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (37,'3','35','160','0022','東京都','新宿区','新宿','１丁目８','',35.688728,139.711083);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (38,'3','36','151','0051','東京都','渋谷区','千駄ヶ谷','５丁目３０－５','',35.68575,139.704295);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (39,'3','37','151','0053','東京都','渋谷区','代々木','２丁目４４－１２','代々木第１ビル１０１号',35.682942,139.696441);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (40,'3','38','151','0053','東京都','渋谷区','代々木','１丁目５８－１','代々木ハイツ２１０',35.685042,139.700935);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (41,'3','39','160','0015','東京都','新宿区','大京町','２４','',35.684359,139.716988);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (42,'3','40','160','0015','東京都','新宿区','大京町','１２－９','大京ビル1F',35.684126,139.717842);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (43,'3','41','160','0016','東京都','新宿区','信濃町','20','花林ビルディング',35.683387,139.720212);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (44,'3','42','160','0016','東京都','新宿区','信濃町','34','',35.680142,139.720198);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (45,'3','43','164','0011','東京都','中野区','中央','2-2-31','中野Nビル5階',35.69759,139.68267);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (46,'3','44','160','0023','東京都','新宿区','西新宿','3丁目１０－１３','',35.685432,139.689143);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (47,'3','45','160','0023','東京都','新宿区','西新宿','５丁目１０－１０','',35.690781,139.686282);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (48,'3','46','160','0023','東京都','新宿区','西新宿','4丁目１０－９','',35.688796,139.686409);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (49,'3','47','160','0023','東京都','新宿区','西新宿','1-1-1','第一ビル111',35.692463,139.699609);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (50,'3','48','160','0023','東京都','新宿区','歌舞伎町','2-2-1-111','',35.694983,139.706375);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (51,'3','49','151','0053','東京都','渋谷区','代々木','3','代々木building901',35.683,139.694328);
INSERT INTO addresses(id,user_id,hand_id,zip_code_1,zip_code_2,`state`,city,address1,address2,address3,lat,lng) VALUES (52,'3','50','160','0015','東京都','新宿区','大京町','15','',35.684992,139.717447);
commit;





--
-- Table structure for table `exchanges`
--

DROP TABLE IF EXISTS `exchanges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exchanges`
    (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `hand_id` INT(11) UNSIGNED NOT NULL COMMENT 'おてつだいID',
        `sequential_no` INT(11) UNSIGNED NOT NULL COMMENT '連番',
        `host_user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ホストユーザID',
        `guest_user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ゲストユーザID',
        `body_host_id` INT(11) UNSIGNED NOT NULL COMMENT '本文ユーザID',
        `body` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '本文',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '登録日時',
        `updated_at` DATETIME DEFAULT NULL COMMENT '更新日時',
        `del_flg` BOOLEAN NOT NULL DEFAULT FALSE COMMENT '論理削除',
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT 'やりとり';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exchanges`
--
-- ORDER BY:  `ID`

set autocommit=0;
INSERT INTO exchanges(id,hand_id,sequential_no,host_user_id,guest_user_id,body_host_id,body) VALUES (1,'1','1','2','3','3','詳しく聞きたいです。');
INSERT INTO exchanges(id,hand_id,sequential_no,host_user_id,guest_user_id,body_host_id,body) VALUES (2,'1','2','2','3','2','なんでも聞いてください。');
INSERT INTO exchanges(id,hand_id,sequential_no,host_user_id,guest_user_id,body_host_id,body) VALUES (3,'1','3','2','3','3','どんなおてつだいをご希望ですか？');
INSERT INTO exchanges(id,hand_id,sequential_no,host_user_id,guest_user_id,body_host_id,body) VALUES (4,'1','4','2','3','2','草むしりです。');
INSERT INTO exchanges(id,hand_id,sequential_no,host_user_id,guest_user_id,body_host_id,body) VALUES (5,'1','5','2','3','3','何時間くらいかかりそうですか？');
commit;





--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites`
    (
        `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `hand_id` INT(11) UNSIGNED NOT NULL COMMENT 'おてつだいID',
        `host_user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ホストユーザID',
        `guest_user_id` INT(11) UNSIGNED NOT NULL COMMENT 'ゲストユーザID',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP() COMMENT '登録日時',
        PRIMARY KEY(`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT 'お気に入り';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--
-- ORDER BY:  `ID`

set autocommit=0;
commit;























--
-- Dumping events for database 'littlehands'
--

--
-- Dumping routines for database 'littlehands'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
SET autocommit=@old_autocommit;

-- Dump completed on 2020-01-22  9:56:18
