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
/*
1. 创建 
$url = 'http://platform.com/company/create';
$header = array('Appkey'=>'1119045005','Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b');
$data = array(
		'user_name'=>'jackluo', //用户名
		'password'=>'jackluo',	//密码
		'area_code'=>'101010100', //地区码,要根据 地区表
		'domain_prefix'=>'luo', //域名前缀
		'manger_name'=>'罗永浩',//管理者名字
		'comp_name'=>'锤子科技',//公司名字
		'phone_number'=>'132281918931',//联系电话
		'comp_address'=>'成都市成华区东桂小区4号园'//地址
	);
$result = curl_post($url, $data, $header);
print_r($result);
*/
//修改
$url = 'http://platform.com/company/change';
$header = array('Appkey'=>'1119045005','Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b');
$data = array(
		'comp_id'=>'1', //用户名
		'area_code'=>'101010100', //地区码,要根据 地区表
		'manger_name'=>'罗永浩',//管理者名字
		'comp_name'=>'锤子科技',//公司名字
		'phone_number'=>'13228191831',//联系电话
		'comp_address'=>'成都市成华区东桂小区4号园',//地址
		'comp_status'=>'0'   //状态
	);
$result = curl_post($url, $data, $header);
print_r($result);

