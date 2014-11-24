<?php
function curl_post($url, $data, $header = array()){
		if(function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			if(is_array($header) && !empty($header)){
				$set_head = array();
				foreach ($header as $k=>$v){
					$set_head[] = "$k:$v";
				}
				curl_setopt($ch, CURLOPT_HTTPHEADER, $set_head);
			}
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			$data = http_build_query($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 1);// 1s to timeout.
			$response = curl_exec($ch);
			if(curl_errno($ch)){
				//error
				return curl_error($ch);
			}

/*
			$reslut = curl_getinfo($ch);
			print_r($reslut);
*/
			curl_close($ch);
/*

			$info = array();
			if($response){
				$info = json_decode($response, true);
			}
*/			

			return $response;

		} else {
			throw new Exception('Do not support CURL function.');
		}
}
function api_notice_increment($url, $data)
	{
	    $ch = curl_init();        
	    curl_setopt($ch, CURLOPT_HEADER,0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST, 1);
//	    $data = http_build_query($data);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	    //curl_file_create
	//    $result =  curl_exec($ch);
	    $lst['rst'] = curl_exec($ch);
	    $lst['info'] = curl_getinfo($ch);
	    curl_close($ch); 
	
	    return $lst;
	//    return $result;
	}
$url = 'http://platform.com/download/image';
$data = array('photo_id'=>'31aa3a8b8df1fb9f6e5266afa7f0760d');
$header = array('Appkey'=>'1119045005','Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b');
//$info = api_notice_increment($url, $data);
$info = curl_post($url, $data, $header);
header("Content-type: image/png");
print_r($info);


/*
//定义编码
header( 'Content-Type:text/html;charset=utf-8 ');

//Atom
header('Content-type: application/atom+xml');

//CSS
header('Content-type: text/css');

//Javascript
header('Content-type: text/javascript');

//JPEG Image
header('Content-type: image/jpeg');

//JSON
header('Content-type: application/json');

//PDF
header('Content-type: application/pdf');

//RSS
header('Content-Type: application/rss+xml; charset=ISO-8859-1');

//Text (Plain)
header('Content-type: text/plain');

//XML
header('Content-type: text/xml');

// ok
header('HTTP/1.1 200 OK');

//设置一个404头:
header('HTTP/1.1 404 Not Found');

//设置地址被永久的重定向
header('HTTP/1.1 301 Moved Permanently');

//转到一个新地址
header('Location: http://www.example.org/');

//文件延迟转向:
header('Refresh: 10; url=http://www.example.org/');
print 'You will be redirected in 10 seconds';

//当然，也可以使用html语法实现
// <meta http-equiv="refresh" content="10;http://www.example.org/ />

// override X-Powered-By: PHP:
header('X-Powered-By: PHP/4.4.0');
header('X-Powered-By: Brain/0.6b');

//文档语言
header('Content-language: en');

//告诉浏览器最后一次修改时间
$time = time() - 60; // or filemtime($fn), etc
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');

//告诉浏览器文档内容没有发生改变
header('HTTP/1.1 304 Not Modified');

//设置内容长度
header('Content-Length: 1234');

//设置为一个下载类型
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="example.zip"');
header('Content-Transfer-Encoding: binary');
// load the file to send:
readfile('example.zip');

// 对当前文档禁用缓存
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header('Pragma: no-cache');

//设置内容类型:
header('Content-Type: text/html; charset=iso-8859-1');
header('Content-Type: text/html; charset=utf-8');
header('Content-Type: text/plain'); //纯文本格式
header('Content-Type: image/jpeg'); //JPG***
header('Content-Type: application/zip'); // ZIP文件
header('Content-Type: application/pdf'); // PDF文件
header('Content-Type: audio/mpeg'); // 音频文件
header('Content-Type: application/x-shockw**e-flash'); //Flash动画

//显示登陆对话框
header('HTTP/1.1 401 Unauthorized');
header('WWW-Authenticate: Basic realm="Top Secret"');
print 'Text that will be displayed if the user hits cancel or ';
print 'enters wrong login data';


xlsx download 
$filename = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/app/files/payment_status.csv';
header('Content-Disposition: attachment; filename=payment_status.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: ' . filesize($filename));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');
readfile($filename);

*/