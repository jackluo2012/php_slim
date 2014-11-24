<?php
/**
 * 常量定义
 * author jackluo
 * net.webjoy@gmail.com
 */
define ('ERROR_CODE_OK',0x0001); // 成功
define ('ERROR_CODE_ERROR',0x0002); // 保存失败
define ('ERROR_CODE_NOKNOW',0x0003); // 未知
define ('ERROR_CODE_PARAMS_NOT_COMPLETE',0x0004); // 参数不完整
define ('ERROR_CODE_ERROR_SECRET',0x0005); // 错误的密钥

//10以上给公共用
define ('ERROR_CODE_COMPANY_USER_EXIST',0x0010); // 公司登陆用户已存在
define ('ERROR_CODE_COMPANY_DOMAIN_EXIST',0x0011); // 公司登陆域名已存在
// download 
define ('ERROR_CODE_DOWNLOAD_DATA_NOT_EXIST',0x0012); // 公司登陆域名已存在