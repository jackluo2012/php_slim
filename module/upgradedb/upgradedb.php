<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
/**
 *	用户信息
 */
$app->post("/file", function () use($app, $module) {

	if (empty($_FILES['pic'])) {
		//
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_UPGRADE_DATA_NOT_ERROR));	
	}
	//print_r($_FILES['pic']['type']);
	//exit;
	if ($_FILES['pic']['type']!='text/x-sql') {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_UPLOAD_EXTENDS_NOT_ALLOW));	
	}
/*
	echo 1212;
	exit;
*/	$path = $_FILES['pic']['tmp_name'];
	
	$upgradeobj = $module->loadModule('upgradedb/upgradedbmodel');
	$result = $upgradeobj->upgrade($path);
	if ($result) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK));	
	}
	$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_UPGRADE_DATA_NOT_ERROR));	
});


