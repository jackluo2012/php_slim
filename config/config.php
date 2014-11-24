<?php
return array(
    'webConfig'=>array(
        'base_url'=>'http://platform.com'
    ),
    'dbConfig' => array(
    	'type' =>'mysql',
    	'info' =>array(
	    	'host'	=> '127.0.0.1',
	    	'port'	=> '3306',
	    	'dbname'	=> 'platform',
	    	'user'	=> 'root',
	    	'password'	=> 'admin',
    	)
    ),
    'cacheConfig' => array(
    	'type'=>'rediscluster',
    	'info'=>array(
            '127.0.0.1:6379',
    		'192.168.2.29:6379',
    	)
    ),
    'fastDFS'=>array(
        'tracker_host'   => '192.168.2.230',// 默认的FastDFS的地址
        'tracker_port'   => '22122',// 默认的FastDFS的端口
        'group'   => ''// 默认的FastDFS的组名(group1/group2)
    ),
    'cacheKey' =>array(
        //Autho
        'authinfo'    => 'a:info',

        //Company 表
        'cdomain'  =>  'c:domain',

        //User 表
        'userinfo'     => 'u:info',
        'userinfoex'   => 'u:infoext',
        'findcard'     => 'u:find',
        
        // goods_photo
        'photoinfo'    => 'p:info',   

    )
);