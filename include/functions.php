<?php
/**
 * auth by jackluo
 * net.webjoy@gmail.com
 * 公共的方法
 */

function getstr($string, $length, $in_slashes=0, $out_slashes=0, $bbcode=0, $html=0) {

	$string = trim($string);
	$sppos = strpos($string, chr(0).chr(0).chr(0));
	if($sppos !== false) {
		$string = substr($string, 0, $sppos);
	}
	if($in_slashes) {
		$string = dstripslashes($string);
	}
	$string = preg_replace("/\[hide=?\d*\](.*?)\[\/hide\]/is", '', $string);
	if($html < 0) {
		$string = preg_replace("/(\<[^\<]*\>|\r|\n|\s|\[.+?\])/is", ' ', $string);
	} elseif ($html == 0) {
		$string = dhtmlspecialchars($string);
	}

	if($length) {
		$string = cutstr($string, $length);
	}
	
	if($out_slashes) {
		$string = daddslashes($string);
	}
	return trim($string);
}
/**
 *	反引用一个引用字符串
 */
function dstripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dstripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
/**
 *	截取字符串
 */
function cutstr($string, $length, $dot = ' ...') {
	if(strlen($string) <= $length) {
		return $string;
	}

	$string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);

	$strcut = '';
	//'utf-8'  
	if(true) {

		$n = $tn = $noc = 0;
		while($n < strlen($string)) {

			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t < 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}

			if($noc >= $length) {
				break;
			}

		}
		if($noc > $length) {
			$n -= $tn;
		}

		$strcut = substr($string, 0, $n);

	} else {
		for($i = 0; $i < $length; $i++) {
			// 返回字符的 ASCII 码值 
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}

	$strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);

	return $strcut.$dot;
}
/**
 * 过虑掉xss 攻击
 */
function dhtmlspecialchars($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtmlspecialchars($val);
		}
	} else {
		$string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
		if(strpos($string, '&amp;#') !== false) {
			$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
		}
	}
	return $string;
}
//过虑掉单引号
function daddslashes($string, $force = 1) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = daddslashes($val, $force);
		}
	} else {
		$string = addslashes($string);
	}
	return $string;
}

/**
 *	获取IP
 */
function getip(){
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
	  $cip = $_SERVER["HTTP_CLIENT_IP"];	
	}
	elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
  		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif(!empty($_SERVER["REMOTE_ADDR"])){
	  $cip = $_SERVER["REMOTE_ADDR"];
	}
	else{
	  $cip = "noknow";
	}
	return $cip;
}
/**
 * @brief 检测文件是否有可执行的代码
 * @param string  $file 要检查的文件路径
 * @return boolean 检测结果
 */
function checkHex($file)
{
	$resource = fopen($file, 'rb');
	$fileSize = filesize($file);
	fseek($resource, 0);
	// 读取文件的头部和尾部
	if ($fileSize > 512)
	{
		$hexCode = bin2hex(fread($resource, 512));
		fseek($resource, $fileSize - 512);
		$hexCode .= bin2hex(fread($resource, 512));
	}
	// 读取文件的全部内容
	else
	{
		$hexCode = bin2hex(fread($resource, $fileSize));
	}
	fclose($resource);
	/* 匹配16进制中的 <% (  ) %> */
	/* 匹配16进制中的 <? (  ) ?> */
	/* 匹配16进制中的 <script  /script>  */
	if (preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054.*?2F5343524950543E)|(3C736372697074.*?2F7363726970743E)/is", $hexCode))
	{
		return false;
	}
	else
	{
		return true;
	}
}

/**
 * 清理URL地址栏中的危险字符，防止XSS注入攻击
 * @param string $url
 * @return string
 */
function clearUrl($url)
{
	return str_replace(array('\'','"','&#',"\\"),'',$url);
}
/**
 * 模拟Post提交
 */
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
			curl_close($ch);
			$info = array();
			if($response){
				$info = json_decode($response, true);
			}
			return $info;
		} else {
			throw new Exception('Do not support CURL function.');
		}
}