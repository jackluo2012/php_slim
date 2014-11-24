<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
/**
 *	用户信息
 */
$app->post("/image", function () use($app, $module) {
	
	$photoObj = $module->loadModule('upload/photomodel');//$module->loadModule('upload/photoModel');
	$info = $photoObj->run();
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
		//	print_r($_POST);
///*
//		$streamoObj = $module->loadModule('upload/streammodel');
		$buff = $_POST['path'];
		$ext  = $_POST['ext'];
//		$md5file = $_POST['md5file'];
/*		if ($buff && $ext && $md5file) {
			$info = $streamoObj->get_md5_file($md5file);					
		}*/
//		*/


		$config = Config::get('fastDFS');
        //print_r($config);
        $fastdfs = IFdfs::getInstance($config);

        $info = $fastdfs->upload_filebuff($buff,$ext);
        print_r($info);
//*/
});



$app->post("/book", function () use($app, $module) {
	echo 'Hello World';
});

