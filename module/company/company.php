<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
 /**
  *	创建-公司
  */
$app->post("/create", function () use($app, $module) {

	$model = $module->loadModule('company/companymodel');
	$user_name = getstr($app->request()->post('user_name'),20);//用户名
	$password = getstr($app->request()->post('password'),20);
	$area_code = getstr($app->request()->post('area_code'),10);//地区码
	$domain_prefix = getstr($app->request()->post('domain_prefix'),10);//域名前缀
	$manger_name = getstr($app->request()->post('manger_name'),15);
	$comp_name = getstr($app->request()->post('comp_name'),20);
	$phone_number = getstr($app->request()->post('phone_number'),11);
	$comp_address = getstr($app->request()->post('comp_address'),50);
	
	if(empty($user_name) || empty($password) || empty($area_code) ||empty($domain_prefix)
		|| empty($manger_name) || empty($phone_number) || empty($comp_address)){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_PARAMS_NOT_COMPLETE));	
	}
	//检查用户名是否已用
	if(!$model->getUserIsExist($user_name)){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_COMPANY_USER_EXIST));
	}
	//检查域名　
	if(!$model->getDomainExist($domain_prefix)){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_COMPANY_DOMAIN_EXIST));
	}
	
	$setarr = array(
		'user_name'=>$user_name,
		'password'=>$password,
		'area_code'=>$area_code,
		'comp_name'=>$comp_name,
		'domain_prefix'=>$domain_prefix,
		'manger_name'=>$manger_name,
		'phone_number'=>$phone_number,
		'comp_address'=>$comp_address,
		'status'=>'0',
		'online'=>time(),
		'ip'=>getip(),
	);
	$result = $model->saveCompany($setarr);//保存进数据库
	if($result){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK));
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_ERROR));
	}
});
/**
 *	更改公司状态
 */
$app->post("/change", function () use($app, $module) {
	$model = $module->loadModule('company/companymodel');
	$comp_id = intval($app->request()->post('comp_id'));//公司ID
	$area_code = getstr($app->request()->post('area_code'),10);//地区码
	$manger_name = getstr($app->request()->post('manger_name'),15);
	$comp_name = getstr($app->request()->post('comp_name'),20);
	$phone_number = getstr($app->request()->post('phone_number'),11);
	$comp_address = getstr($app->request()->post('comp_address'),50);
	$comp_status = intval($app->request()->post('comp_status'));//获取状态

	//参数检查
	if(empty($user_name) || empty($password) || empty($area_code) 
		|| empty($manger_name) || empty($phone_number) || empty($comp_address)){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_PARAMS_NOT_COMPLETE));	
	}
	//检查公司信息是否存在
	

	$setarr = array(
		'area_code'=>$area_code,
		'comp_name'=>$comp_name,
		'manger_name'=>$manger_name,
		'phone_number'=>$phone_number,
		'comp_address'=>$comp_address,
		'status'=>$comp_status
	);
	$result = $model->save($setarr,array('comp_id',$comp_id));//保存进数据库
	if($result){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK));
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_ERROR));
	}
});