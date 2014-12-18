图片上传api说明:

允许上传的文件类型
	'jpg','gif','png','zip','rar','docx','doc'

提供两种上传方式 
1.流式上传
// 流式上传
接口地址:	http://platform.com/upload/stream;
上传方式：POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交的数据：包含以下内容
$data   = array(
		'path'=>file_get_contents($tmpfile),//文件流
		'ext'=>$ext,//后缀 不要.
		'filename'=>$tmpname, //文件名
		'md5file'=>$md5file, //文件 码,防止重得提交
	);

返回:
	{"data":{"photo_id":"9fa941ed227854a51edb1647387951d2","img":"M00\/00\/00\/wKgBCFRz9myAbNNcAAEe10UotHQ354.jpg","file_name":"6446027056db8afa73b23eaf953dadde1410240902.jpg","group":"group1"},"error":1}
访问文件：
http://img.fromai.cn/group1/M00/00/00/wKgBCFRz9myAbNNcAAEe10UotHQ354.jpg	
-----------------------------------------------------
详细请查看 upload.php 提供 了demo
-----------------------------------------------------

2.传统方式上传:

接口地址:	http://platform.com/upload/image;
上传方式：POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交的数据：包含以下内容
	$_FILES 信息如何下
$data = array(
	'pic'=>'@'.realpath($path).";type=".$type.";filename=".$filename
);
返回: {"data":{"pic":{"name":"d31b0ef41bd5ad6e61c2497283cb39dbb6fd3c6a.jpg","info":{"group_name":"group1","filename":"M00\/00\/00\/wKgBCFRz_buAdCh0AABuZ74lVgs192.jpg","photo_id":"05459a5e581668abb700cafe8d2fe3ab"}}},"error":1}

访问文件：
http://img.fromai.cn/group1/M00/00/00/wKgBCFRz_buAdCh0AABuZ74lVgs192.jpg	
// 注意
如何传的是错误的类型如
{"data":{"pic":{"name":"1406969126.mp3","info":[],"flag":-7}},"error":1}
请配置 info中是否有值,如何有，代表上传成功

-----------------------------------------------------
详细请查看 upload.php 提供 了demo
-----------------------------------------------------



下载api说明:
目前只提供流式下载：
自行设置Header 头(Demo中提供了详细的Header，可以作为参考)
接口地址:http://platform.com/download/file
下载方式:POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交的数据：包含以下内容
$data = array('photo_id'=>'31aa3a8b8df1fb9f6e5266afa7f0760d');
返回的是流式数据:
-----------------------------------------------------------------------
详细请查看 download.php 提供了 demo
-----------------------------------------------------------------------


平台创建公司接口说明：
接口创建地址: http://platform.com/company/create
方式:POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交数据:
$data = array(
		'user_name'=>'jackluo', //用户名
		'password'=>'jackluo',	//密码
		'area_code'=>'101010100', //地区码,要根据 地区表
		'domain_prefix'=>'luo', //域名前缀
		'manger_name'=>'罗敬程',//管理者名字
		'phone_number'=>'132281918931',//联系电话
		'comp_address'=>'成都市成华区东桂小区4号园'//地址
	);

返回数据：
{"error":"1"}
----------------------------------------------------------------------------	
详细请查看 company.php 提供了详细的 demo
----------------------------------------------------------------------------	

平台修改公司接口说明：
接口创建地址: http://platform.com/company/change
方式:POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交数据:
$data = array(
		'user_name'=>'jackluo', //用户名
		'password'=>'jackluo',	//密码
		'area_code'=>'101010100', //地区码,要根据 地区表
		'domain_prefix'=>'luo', //域名前缀
		'manger_name'=>'罗敬程',//管理者名字
		'phone_number'=>'132281918931',//联系电话
		'comp_address'=>'成都市成华区东桂小区4号园'//地址
		'comp_status'=>'0'	//状态
	);

返回数据：
{"error":"1"}
----------------------------------------------------------------------------	
详细请查看 company.php 提供了详细的 demo
----------------------------------------------------------------------------

平台修改公司接口说明：
接口创建地址: http://platform.com/company/change
方式:POST 提交
必须带HEADER头:
$header = array(
	'Appkey'=>'1119045005',
	'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
);
提交数据:
$data = array(
		'user_name'=>'jackluo', //用户名
		'password'=>'jackluo',	//密码
		'area_code'=>'101010100', //地区码,要根据 地区表
		'domain_prefix'=>'luo', //域名前缀
		'manger_name'=>'罗敬程',//管理者名字
		'phone_number'=>'132281918931',//联系电话
		'comp_address'=>'成都市成华区东桂小区4号园'//地址
		'comp_status'=>'0'	//状态
	);

返回数据：
{"error":"1"}
----------------------------------------------------------------------------	
详细请查看 company.php 提供了详细的 demo
----------------------------------------------------------------------------