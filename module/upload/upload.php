<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
/**
 *	用户信息
 */
$app->post("/file", function () use($app, $module) {
	
	$photoObj = $module->loadModule('upload/photomodel');
	$comp_id = intval($app->request()->post('comp_id'));	//公司ID
	//数据失败
	if(empty($comp_id)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_PARAMS_NOT_COMPLETE));
	}
	/**
	 * 检查公司信息是否存在
	 */
	$companyObj = $module->loadModule('company/companymodel');
	$companyinfo = $companyObj->getCompanyInfo($comp_id);
	if (empty($companyinfo)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_DATA_INFO_NOT_EXIST));	
	}
	$info = $photoObj->run($comp_id);

	$app->applyHook(OUT_PUT,array('data'=>$info));
	/*
	Array ( [fname] => 
	Array ( [name] => 2.jpg [info] => 
	Array ( [group_name] => group1 
			[filename] => M00/00/00/oYYBAFRhhS-AGuIRAAQJ2UjNiTU170.png 
			[photo_id] => f51ed35dd599beed59d763d642b7be92 ) ) )
	*/
	print_r($info);
//	echo 'Hello World';
});

//stream 
$app->post("/stream", function () use($app, $module) {
/*
Array
(
    [rst] => Array
(
    [group_name] => group3
    [filename] => M00/00/00/o4YBAFRvEMOAEIywAAA9xXln_4s001.jpg
)

*/
/*			print_r($_POST);
			exit;*/
///*
		$req = $app->request();
		// POST 变量
		$buff = $req->post('path');	//变量流
		$ext  = $req->post('ext');	//文件扩长名
		$filename = $req->post('filename'); //文件名
		$md5file = getstr($req->post('md5file'),32); //文件md5码
		$comp_id = intval($req->post('comp_id'));	//公司ID
		//文件对象 
		$streamObj = $module->loadModule('upload/streammodel');
		$companyObj = $module->loadModule('company/companymodel');
		if (empty($buff) || empty($ext) || empty($md5file) || empty($filename) || empty($comp_id)) {
			$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_PARAMS_NOT_COMPLETE));
		}

		/**
		 * 检查公司信息是否存在
		 */
		$companyinfo = $companyObj->getCompanyInfo($comp_id);
		if (empty($companyinfo)) {
			$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_DATA_INFO_NOT_EXIST));	
		}

		$result = $streamObj->saveStream($buff,$ext,$filename,$md5file,$comp_id);
		if ($result == '10001') {
			$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_UPLOAD_EXTENDS_NOT_ALLOW));	
		}else if($result == '10002'){
			$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_UPLOAD_DATA_STREAM_ERROR));
		}
		$app->applyHook(OUT_PUT,array('data'=>$result));
		

/*
		$config = Config::get('fastDFS');
        //print_r($config);
        $fastdfs = IFdfs::getInstance($config);

        $info = $fastdfs->upload_filebuff($buff,$ext);
*/

//        print_r($info);
//*/
});



$app->post("/book", function () use($app, $module) {
	echo 'Hello World';
});

