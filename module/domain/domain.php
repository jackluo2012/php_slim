<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
 /**
  *	创建-公司
  */
$app->post("/find", function () use($app, $module) {
	$model = $module->loadModule('domain/domainmodel');
	$domain_name = getstr($app->request()->post('domain'),24);//用户名
	$info = array();
	$info = $model->find_domain($domain_name);
	if($info){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>$info));
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_DOMAIN_DATA_EMPTY));
	}	
});