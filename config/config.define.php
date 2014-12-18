<?php
/**
 * 常量定义
 * author jackluo
 * net.webjoy@gmail.com
 */
define ('ERROR_CODE_OK',0x0000); // 成功
define ('ERROR_CODE_ERROR',0x0002); // 保存失败
define ('ERROR_CODE_NOKNOW',0x0003); // 未知
define ('ERROR_CODE_PARAMS_NOT_COMPLETE',0x0004); // 参数不完整
define ('ERROR_CODE_ERROR_SECRET',0x0005); // 错误的密钥
define ('ERROR_CODE_DATA_INFO_NOT_EXIST',0x0006); // 信息不存在
//10以上给公共用
define ('ERROR_CODE_COMPANY_USER_EXIST',0x0010); // 公司登陆用户已存在
define ('ERROR_CODE_COMPANY_DOMAIN_EXIST',0x0011); // 公司登陆域名已存在
// download 
define ('ERROR_CODE_DOWNLOAD_DATA_NOT_EXIST',0x0012); // 要下载的数据不存在
// upload
define ('ERROR_CODE_UPLOAD_EXTENDS_NOT_ALLOW',0x0013); // 不允许上传的类型
define ('ERROR_CODE_UPLOAD_DATA_STREAM_ERROR',0x0014); // 不允许上传的数据出错
//company 
define ('ERROR_CODE_COMPANY_DATA_STREAM_ERROR',0x0015); // 不允许上传的数据出错
//upgrade
define ('ERROR_CODE_UPGRADE_DATA_NOT_ERROR',0x0016); // upgrade 出错
//define ('ERROR_CODE_UPGRADE_DATA_SUCCESSFUL',0x0016); // upgrade 出错
//domain
define ('ERROR_CODE_DOMAIN_DATA_EMPTY',0x0017); // empty 出错