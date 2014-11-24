<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
/**
 *	用户信息
 */
$app->post("/file", function () use($app, $module) {

//	$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_ERROR_SECRET));
/*	$fastdfs = IFdfs::getInstance();
	$path = $_SERVER['DOCUMENT_ROOT'];
	//$info = $fastdfs->upload_filename($path.'/321.jpg','group2','png');
	//$info = $fastdfs->upload_filename1($path.'/321.jpg','group2','png');
	$info = $fastdfs->download_filename('group1','M00/00/00/wKgBCFRsUF-ALGD-AAFkA0_nGT0998.png','黄柏金平台1.png');
*/
	//$info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRa7v6AGiv6AADjrkdvVoI383.png');
	//$info = $fastdfs->download_filename1('group2/M00/00/00/ooYBAFRa7v6AGiv6AADjrkdvVoI383.png',$path.'/heihei.jpg');

	//$ifdfs 	= new IFdfs();// IFdfs::getInstance();

	//$info = $ifdfs->download_filename('group2','M00/00/00/ooYBAFRpt3OANbCJAAlVE-DOvkk444.png','3.jpg');
	//	var_dump($ifdfs);

	/*
	$photoObj = $module->loadModule('upload/photomodel');//$module->loadModule('upload/photoModel');
	$info = $photoObj->run();
	
	Array ( [fname] => 
	Array ( [name] => 2.jpg [info] => 
	Array ( [group_name] => group1 
			[filename] => M00/00/00/oYYBAFRhhS-AGuIRAAQJ2UjNiTU170.png 
			[photo_id] => f51ed35dd599beed59d763d642b7be92 ) ) )
	*/

	//print_r($info);
//	echo 'Hello World';

	$req = $app->request();
	// POST 变量
	$photo_id = getstr($req->post('photo_id'),32) ;

	$downloadObj = $module->loadModule('download/downloadmodel');
	$downloadinfo = $downloadObj->getFileById($photo_id);
	if($downloadinfo){
		$fastdfs = IFdfs::getInstance(Config::get('fastDFS'));
		// buff
		$info = $fastdfs->download_filebuff($downloadinfo['group'],$downloadinfo['img']);
		ob_clean();
		echo $info;
		//$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>array('info'=>$info)));
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_DOWNLOAD_DATA_NOT_EXIST));
	}

    //print_r($config);
    //$fastdfs = IFdfs::getInstance(Config::get('fastDFS'));

    //$info = $fastdfs->upload_filename($path.'/321.jpg','group2','png');
    //array(2) { ["group_name"]=> string(6) "group2" ["filename"]=> string(44) "M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png" } 
    //$info = $fastdfs->upload_filename1($path.'/321.jpg','group2','png');
    //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
    //$info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
    //$info = $fastdfs->download_filename1('group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png',$path.'/hehe.jpg');
    //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
    //group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png
//    $info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
//    ob_clean();
//    header("Content-type: image/png");
//    echo $info;


//    exit;
});


