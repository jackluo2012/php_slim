<?php
	function curl_file_get_contents($url){
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	    curl_setopt($ch, CURLOPT_USERAGENT, _USERAGENT_);
	    curl_setopt($ch, CURLOPT_REFERER,_REFERER_);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $r = curl_exec($ch);
	    $reslut = curl_getinfo($ch);
	    
	    print_r($reslut);
	    curl_close($ch);
	    return $r;
	}

	$path = $_SERVER['DOCUMENT_ROOT'].'/test.sql';
	$data = file_get_contents($path);
	//print_r($data);

	$data = curl_file_get_contents($path);
	print_r($data);