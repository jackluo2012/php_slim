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


CREATE TABLE `company` (
  `comp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公司ID',
  `user_name` varchar(20) NOT NULL COMMENT '临时用户名',
  `password` varchar(20) NOT NULL COMMENT '临时密码',
  `domain_prefix` varchar(10) NOT NULL COMMENT '域名前缀',
  `area_code` varchar(10) NOT NULL COMMENT '地区码',
  `comp_name` varbinary(20) NOT NULL COMMENT '公司名',
  `manger_name` varchar(15) NOT NULL COMMENT '负责人名字',
  `phone_number` varchar(11) NOT NULL COMMENT '电话',
  `comp_address` varbinary(50) NOT NULL COMMENT '公司详细地址',
  `status` enum('0','1','2') NOT NULL COMMENT '0.还未初始化,1,正常,2.停止服务',
  `online` int(11) NOT NULL COMMENT '创建时间',
  `ip` varchar(32) NOT NULL COMMENT '创建IP',
  PRIMARY KEY (`comp_id`),
  KEY `domain_prefix` (`domain_prefix`),
  KEY `user_name` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=100008 DEFAULT CHARSET=utf8;

CREATE TABLE `company_mysql` (
  `cm_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'mysql_ID',
  `comp_id` int(11) NOT NULL COMMENT '公司ID',
  `db_host` varchar(32) NOT NULL COMMENT '数据库HOST',
  `db_user` varchar(10) NOT NULL COMMENT '数据库User',
  `db_pwd` varchar(32) NOT NULL COMMENT '数据库pwd',
  `db_name` varchar(15) NOT NULL COMMENT '数据库name',
  `db_port` varchar(6) NOT NULL COMMENT '数据库port',
  PRIMARY KEY (`cm_id`),
  KEY `comp_id` (`comp_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
