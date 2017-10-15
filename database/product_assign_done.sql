/*
SQLyog Community v11.52 (64 bit)
MySQL - 5.6.25 : Database - icheck_ctv
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*Table structure for table `product_assign_done` */

DROP TABLE IF EXISTS `product_assign_done`;

CREATE TABLE `product_assign_done` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gtin` bigint(20) unsigned NOT NULL,
  `user_id` int(11) DEFAULT '0',
  `user_approved` int(11) DEFAULT '0',
  `user_name` varchar(100) DEFAULT '',
  `product_name` varchar(255) DEFAULT '',
  `product_image` varchar(255) DEFAULT '',
  `product_caegories` int(10) DEFAULT '0',
  `status` tinyint(2) DEFAULT '0',
  `price` int(10) DEFAULT '0',
  `note` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `product_assign_done` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
