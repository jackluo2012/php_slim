DROP TABLE IF EXISTS `company`;

CREATE TABLE `company` (
  `comp_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '公司ID',
  `user_name` varchar(20) NOT NULL COMMENT '临时用户名',
  `password` varchar(20) NOT NULL COMMENT '临时密码',
  `data_name` varchar(10) NOT NULL COMMENT '数据库名',
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


