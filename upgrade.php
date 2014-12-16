<?php
	/**
	 * Email net.webjoy@gmail.com
	 * author jackluo
	 * 2014.11.21
	 */

	//*
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
				//$data = http_build_query($data);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 1);// 1s to timeout.
				$response = curl_exec($ch);
				if(curl_errno($ch)){
					//error
					return curl_error($ch);
				}
				$reslut = curl_getinfo($ch);
//				print_r($reslut);
				curl_close($ch);
/*				$info = array();
				if($response){
					$info = json_decode($response, true);
				}*/
				return $response;
			} else {
				throw new Exception('Do not support CURL function.');
			}
	}
	//*/
	//  
	function api_notice_increment($url, $data,$header)
	{
	    $ch = curl_init();     
	    if(is_array($header) && !empty($header)){
			$set_head = array();
			foreach ($header as $k=>$v){
				$set_head[] = "$k:$v";
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $set_head);
		}   
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

	 /**
   	  *  curl文件上传
   	  *  @var  struing  $r_file  上传文件的路劲和文件名  
   	  * 	
   	  */
	/* 	
	function upload_file($url,$r_file)
 	{
        $file = array("fax_file"=>'@'.$r_file,'type'=>'image/jpeg');//文件路径，前面要加@，表明是文件上传.
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$file);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $result = curl_exec($curl);  //$result 获取页面信息 
        curl_close($curl);
        echo $result ; //输出 页面结果
   }*/
	
   function upload_file($url,$filename,$path,$type,$header){
		$data = array(
			'pic'=>'@'.realpath($path).";type=".$type.";filename=".$filename
		);
		$ch = curl_init();
		if(is_array($header) && !empty($header)){
			$set_head = array();
			foreach ($header as $k=>$v){
				$set_head[] = "$k:$v";
			}
			curl_setopt($ch, CURLOPT_HTTPHEADER, $set_head);
		}
		curl_setopt($ch, CURLOPT_HEADER,0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true );
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// curl_getinfo($ch);
		$return_data = curl_exec($ch);
		curl_close($ch);
		echo $return_data;   	
   }


/*   $file = 'aaaa.jpg';
   echo substr($file, strrpos($file, '.')+1);
   exit;
*/
	if ($_POST) {
		
		// return content
		/*
		Array ( [pic] => Array ( [name] => 232.jpg [info] => Array ( [group_name] => group3 [filename] => M00/00/00/o4YBAFRu-qaAJPWDAAC1vHtKL0E331.jpg [photo_id] => 31aa3a8b8df1fb9f6e5266afa7f0760d ) ) ) 
		Array ( [pic] => Array ( [info] => Array ( [photo_id] => 31aa3a8b8df1fb9f6e5266afa7f0760d [img] => M00/00/00/o4YBAFRu-qaAJPWDAAC1vHtKL0E331.jpg [file_name] => 232.jpg [group] => group3 ) ) )
		*/	
		

		
		//
//		$path = $_SERVER['DOCUMENT_ROOT'];
/*
		print_r($_FILES);
		exit;
*/
		//$filename = $path."/232.jpg";
		//upload tmp
		//single 
		//*
		//*/	
		// multi
/*
		foreach($_FILES as $key => $val)
		{
			//上传的所有临时文件
			$tmpFile = isset($_FILES[$key]['tmp_name']) ? $_FILES[$key]['tmp_name'] : null;

			//没有找到匹配的控件
			if($tmpFile == null)
				continue;
			if(is_array($tmpFile))
			{
				foreach($tmpFile as $tmpKey => $tmpVal)
				{

				}
			}
		}
*/
		/*
		$data = array(
				'path'=>"@$path/232.jpg",
				'name'=>'h'
		);
		*/
		//'pic'=>'@/tmp/tmp.jpg', 'filename'=>'tmp'
		//$data = array('pic'=>"@$filename", 'filename'=>'tmp');
		/*
		$data = array(
			'uid'	=>	10086,
			'pic'	=>	'@$tmpfile'.';type='.$tmpType
		);
		$info = api_notice_increment($url, $data);
		*/
		//$info = curl_post($url, $data);
		//$info = api_notice_increment($url, $data);
		// 传统式上传
		//*
		$url = 'http://platform.com/upgradedb/file';	
		$tmpname = $_FILES['fname']['name'];
		$tmpfile = $_FILES['fname']['tmp_name'];
		$tmpType = $_FILES['fname']['type'];
		$header = array(
						'Appkey'=>'1119045005',
						'Appsecret'=>'95cc8bcafc2a0d2764403b00bd0bb55b'
					);
		$info = upload_file($url,$tmpname,$tmpfile,$tmpType,$header);
		print_r($info);
		exit;
//		*/
/*
		$file = 'H:\www\test\psuCARGLSPA-pola.jpg'; //要上传的文件
		$src = upload_curl_pic($file);
		echo $src;
*/
	}	
?>

<form action="http://platform.com/upgrade.php" enctype="multipart/form-data"  method="post">
  <p>UpLoad: <input type="text" name="fname" /></p>
  <p>UpLoad: <input type="file" name="fname" /></p>

  <input type="submit" value="Submit" />
</form>


