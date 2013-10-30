<?php

class m131021_010250_data_interface extends \yii\db\Migration
{
	public function up()
	{
		$sql = <<< END
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table interface
# ------------------------------------------------------------


DROP TABLE IF EXISTS `data_interface`;

CREATE TABLE `data_interface` (
  `id` char(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `system_id` varchar(255) NOT NULL,
  `last_sync` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `dataInterfaceRegistry` FOREIGN KEY (`id`) REFERENCES `registry` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `data_interface_log`;

CREATE TABLE `data_interface_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_interface_id` char(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `status` enum('failed', 'success', 'running', 'interrupted') DEFAULT 'running',
  `message` longblob,
  `peak_memory` int(11) unsigned DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `ended` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `dataInterfaceLogRegistry` FOREIGN KEY (`data_interface_id`) REFERENCES `data_interface` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `key_translation`;

CREATE TABLE `key_translation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `data_interface_id` char(36) CHARACTER SET ascii COLLATE ascii_bin NULL DEFAULT NULL,
  `registry_id` char(36) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '',
  `key` VARCHAR(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `keyTranslationInterface` (`data_interface_id`),
  KEY `keyTranslationInterfaceKey` (`data_interface_id`, `key`),
  KEY `keyTranslationRegistry` (`registry_id`),
  CONSTRAINT `keyTranslationInterface` FOREIGN KEY (`data_interface_id`) REFERENCES `data_interface` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keyTranslationRegistryFk` FOREIGN KEY (`registry_id`) REFERENCES `registry` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

END;
		return $this->execute($sql);
	}



	public function down()
	{
		echo "m131021_010250_data_interface does not support migration down.\n";
		return false;
	}
}
