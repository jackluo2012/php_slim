<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */

//需要定义主目录常量
define ( 'PATH_SYSTEM_APP', dirname ( __FILE__ ) . '/');
define('OUT_PUT', 'out.put');
//加载核心类库
require PATH_SYSTEM_APP.'classloader.php';
//控制器
require PATH_SYSTEM_APP.'library/Slim/Slim.php';
//设置字符集
header("Content-type: text/html;charset=UTF-8");

\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();//controller模块
//初始化模块
$module = new Core();//model模块

/**
 *  添加验证权限接口
 */
$app->add(new \Slim\AuthMiddleware($module));
/**
 * 回调函数
 */
$app->hook( OUT_PUT ,function($response,$data = array()) use($app){
    if (is_int($response)) {
        $response['data'] = $data;
    }
    if (!isset($response['error'])) {
        $response['error'] = ERROR_CODE_OK;
    }else{
        $response['error'] = strval($response['error']);
    }
    //设置 header 头
    $app->response->headers->set('Content-Type', 'application/json');
    $app->response->setBody(json_encode($response));
    $app->stop();

});

//~ $software->debug = true;
/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */

/**
 *  规范化
 */
$uri = $app->request()->getResourceUri();
$route = substr($uri,0,strpos($uri,'/',1));
$uri = substr($uri,strlen($route));
$dir = 'module';
$app->group($route,function() use ($app,$module,$route,$uri,$dir){
    $dir = $dir.$route;

    $file = $dir.$route.'.php';
    if (file_exists($file)) {
        include($file);
    }
    unset($uri,$route,$dir);
});
// GET route
$app->get(
    '/',
    function () use ($app,$module) {
//      echo 'Hello World';
        /*
        $config = array(
            'tracker_host'   => '192.168.2.230',// 默认的FastDFS的地址
            'tracker_port'   => '22122',// 默认的FastDFS的端口
            'group'          => ''
        );
        $fastdfs = IFdfs::getInstance($config);
        var_dump($fastdfs);
        //*/
        //*
        $config = Config::get('fastDFS');
        //print_r($config);
        $fastdfs = IFdfs::getInstance($config);


        //$info = $fastdfs->upload_filename($path.'/321.jpg','group2','png');
        //array(2) { ["group_name"]=> string(6) "group2" ["filename"]=> string(44) "M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png" } 
        //$info = $fastdfs->upload_filename1($path.'/321.jpg','group2','png');
        //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
        //$info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
        //$info = $fastdfs->download_filename1('group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png',$path.'/hehe.jpg');
        //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
        //group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png
        $info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
        ob_clean();
        header("Content-type: image/png");
        echo $info;
        /*
        $group_name="group2";
        $filename="M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png";
//        $file_id="group3/M00/00/00/wKgAUE5zkhH8yBZwAAGH3hvfjJA398.jpg";
        $timestamp="2011-09-17 02:14:41";
         
         
        $info = fastdfs_storage_download_file_to_file($group_name,$filename, "test.jpg");
        */
        
        exit;


        //*/
        /*
        $path = $_SERVER['DOCUMENT_ROOT'];

        var_dump($module);
        echo 12121;
        $user = $module->loadModule('user/usermodel');
        */
        /*        
        $fileName = $path.'/data/232.jpg';
        $image = new ImagickService($fileName);
    //    
    //    
    //    $image->resize_to();
    //    $image->resize(200,200);
    //    $image->add_text('http://www.webjoy.net');
        $image->add_watermark($path.'/data/watermark.jpg');

        $image->output(true);
        $image->save_to($path.'/data/mark.jpg');
        exit;
        var_dump($image);

    //    echo 1212;
    //    */
        /* Create Imagick object */

    //    echo 'Hello';
        /*    	
        $user = $module->loadModule('user/usermodel');
    	$cache = $module->loadModule('cache/cacheModel');
        echo $cache->set('userinfo','jackluo');
        echo $cache->get('userinfo');

    	$setarr = array(
    		'name'=>'jackluo',
    		'web'=>'www.webjoy.net',
    		'born'=>'2014-10-22'
    	);
        print_r($user->getUserById(11));
    	var_dump($user->saveUser($setarr));
    	print_r($user->getUserById(11));
    	print_r($user->getAllUser());
    	print_r($user->saveUser(array('name'=>'jackluo'),array('id'=>'0')));
        */
        //print_r($user->removeUser('0'));
        //$str = '你妹啊!!!';
		//echo getstr($str,2);
    }
);


$app->get('/image',function() use ($app,$module){
        $config = Config::get('fastDFS');
        $fastdfs = IFdfs::getInstance($config);

        //$info = $fastdfs->upload_filename($path.'/321.jpg','group2','png');
        //array(2) { ["group_name"]=> string(6) "group2" ["filename"]=> string(44) "M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png" } 
        //$info = $fastdfs->upload_filename1($path.'/321.jpg','group2','png');
        //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
        //$info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
        //$info = $fastdfs->download_filename1('group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png',$path.'/hehe.jpg');
        //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
        //group2/M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png
        $info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png');
        //$info = $fastdfs->download_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png','hehe.png');
        ob_clean();
        header("Content-type: image/png");
        echo $info;

});
$app->get('/shui',function() use ($app,$module){
        $group = 'group2';
        $masterfile = 'M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218.png';
        $prefix_name = '_thum_';
        $fastdfs = IFdfs::getInstance(Config::get('fastDFS'));
        $info = $fastdfs->download_filebuff($group,$masterfile);
        // shui ying 
        $imgick = new ImagickService();
        $imgick->read_image_buff($info);
        $imgick->resize_to(500,500);
        $imgick->add_text('www.webjoy.net');
        $content = $imgick->get_images_blob();
        $slave_info = $fastdfs->upload_slave_filebuff($content,$group,$masterfile,$prefix_name);
        unset($imgick);
        print_r($slave_info);
        exit;
});
$app->get('/slave',function() use ($app,$module){
    ob_clean();
    // Array ( [group_name] => group2 [filename] => M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218_thum_.png ) 
    $fastdfs = IFdfs::getInstance(Config::get('fastDFS'));
    $info = $fastdfs->download_filebuff('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218_thum_.png');
    header("Content-type: image/png");
    echo $info;
    exit;
});
$app->get('/delete',function() use ($app,$module){
    ob_clean();
    // Array ( [group_name] => group2 [filename] => M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218_thum_.png ) 
    $fastdfs = IFdfs::getInstance(Config::get('fastDFS'));
    $info = $fastdfs->delete_filename('group2','M00/00/00/ooYBAFRtiMyAOkfjAADjrkdvVoI218_thum_.png');
});


$app->post('/',function() use ($app,$module){
        
        //$user = $module->loadModule('user/usermodel');
        //exit;
        $uploadobj =  new IUpload();
        // var_dump($uploadobj);
        //      $uploadobj->setDir($_SERVER['DOCUMENT_ROOT'].'/upload');
        $upState = $uploadobj->execute();
        print_r($upState);
});
$app->get(
    '/data',
    function () use ($app){
            echo 'data';
        }
    );

$app->run();
