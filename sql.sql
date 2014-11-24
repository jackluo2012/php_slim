CREATE TABLE `auth_verify` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `app_secret` VARCHAR(32) NOT NULL,
  PRIMARY KEY (`id`)  
) ENGINE=INNODB AUTO_INCREMENT=1000000 DEFAULT CHARSET=utf8;


CREATE TABLE `goods_photo`(
	`photo_id` char(32) not null comment 'md5',
	`img`varchar(255) DEFAULT null comment '',
	`group` varchar(10) DEFAULT null comment '',
	PRIMARY KEY(`photo_id`)
) ENGINE=INNODB  DEFAULT CHARSET=utf8;