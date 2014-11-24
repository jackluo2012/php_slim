set names utf8;
create database platform;

CREATE TABLE `auth_verify` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_secret` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1119045006 DEFAULT CHARSET=utf8;

CREATE TABLE `goods_photo` (
  `photo_id` char(32) NOT NULL COMMENT 'md5',
  `img` varchar(255) NOT NULL,
  `file_name` varchar(50) NOT NULL,
  `group` varchar(10) NOT NULL,
  PRIMARY KEY (`photo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;