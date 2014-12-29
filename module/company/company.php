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
	$domain = getstr($app->request()->post('domain'),24);//域名前缀
	$manger_name = getstr($app->request()->post('manger_name'),15);
	$comp_name = getstr($app->request()->post('comp_name'),20);
	$phone_number = getstr($app->request()->post('phone_number'),11);
	$comp_address = getstr($app->request()->post('comp_address'),50);
	$status = intval($app->request()->post('status'));
	
	$db_host = getstr($app->request()->post('db_host'),32);
	$db_user = getstr($app->request()->post('db_user'),10);
	$db_pwd = getstr($app->request()->post('db_pwd'),32);
	$db_port = getstr($app->request()->post('db_port'),6);
	$db_name = getstr($app->request()->post('db_name'),30);
	// maybe is array
	if(empty($user_name) || empty($password) || empty($area_code) ||empty($domain)
		|| empty($manger_name) || empty($phone_number) || empty($comp_address ) || empty($db_host) 
		|| empty($db_user) || empty($db_name) || empty($db_port) || empty($db_name)){
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
		'password'=>md5($password),
		'area_code'=>$area_code,
		'comp_name'=>$comp_name,
		'domain'=>$domain,
		'manger_name'=>$manger_name,
		'phone_number'=>$phone_number,
		'comp_address'=>$comp_address,
		'status'=>(string) $status,
		'online'=>time(),
		'ip'=>getip(),
	);
	$db_info = array(
		'db_host'=>$db_host,
		'db_user'=>$db_user,
		'db_pwd' =>$db_pwd,
		'db_port'=>$db_port,
		'db_name'=>$db_name
	);

	$result = $model->saveCompany($setarr,$db_info);//保存进数据库
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
	$password = getstr($app->request()->post('password'),32);
	$comp_name = getstr($app->request()->post('comp_name'),20);
	$phone_number = getstr($app->request()->post('phone_number'),11);
	$domain = getstr($app->request()->post('domain'),24);//域名前缀
	$comp_address = getstr($app->request()->post('comp_address'),50);
	$comp_status = intval($app->request()->post('status'));//获取状态
	$db_host = getstr($app->request()->post('db_host'),32);
	$db_user = getstr($app->request()->post('db_user'),10);
	$db_pwd = getstr($app->request()->post('db_pwd'),32);
	$db_port = getstr($app->request()->post('db_port'),6);
	$db_name = getstr($app->request()->post('db_name'),30);
	
	$setarr = $db_info= array();
	//参数检查
	if(empty($comp_id)){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_PARAMS_NOT_COMPLETE));	
	}
	$compay_info = $model->getCompanyInfo($comp_id);
	if (empty($compay_info)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_DATA_INFO_NOT_EXIST));	
	}
//	$comp_status = empty($comp_status)?'0':'1';
	if (!empty($area_code)) {
		$setarr['area_code'] = $area_code;
	}
	if (!empty($comp_name)) {
		$setarr['comp_name'] = $comp_name;	
	}
	if (!empty($password)) {
		$setarr['password'] = md5($password);	
	}
	
	if (!empty($manger_name)) {
		$setarr['manger_name'] = $manger_name;	
	}
	if (!empty($phone_number)) {
		$setarr['phone_number'] = $phone_number;	
	}
	if (!empty($comp_address)) {
		$setarr['comp_address'] = $comp_address;	
	}
	if (!empty($domain)) {
		$setarr['domain'] = $domain;	
	}
	if ($comp_status ===0) {
		$setarr['status']='0';
	}else if(!empty($comp_status)){
		$setarr['status']=(string) $comp_status;
	}
	if (!empty($db_host)) {
		$db_info['db_host'] = $db_host;
	}
	if (!empty($db_user)) {
		$db_info['db_user'] = $db_user;
	}
	if (!empty($db_pwd)) {
		$db_info['db_pwd'] = $db_pwd;
	}
	if (!empty($db_name)) {
		$db_info['db_name'] = $db_name;
	}
	if (!empty($db_port)) {
		$db_info['db_port'] = $db_port;
	}
	$result = $mysql_result =  null;
	//检查公司信息是否存在
	if($setarr){
		$result = $model->save($setarr,array('comp_id'=>$comp_id));//保存进数据库
	}
	if($db_info){
		$mysql_result = $model->save_mysql($db_info,array('comp_id'=>$comp_id));//保存进数据库
	}
	if($result || $mysql_result){
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK));
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_ERROR));
	}
});
/**
 *	获取列表
 */
$app->post("/list", function () use($app, $module) {
	
	$model = $module->loadModule('company/companymodel');
	$page = intval($app->request()->post('page'));//当前页
	$pagesize = intval($app->request()->post('pagesize'));//分页大小
	$whereinfo = $app->request()->post('sqlarr');//条件 

	if ($page<=0) {
		$page = 1;
	}
	if (empty($pagesize)) {
		$pagesize = 10;
	}
	$infoarr = $model->getCompanyList($page,$pagesize,$whereinfo);	
	if (empty($infoarr)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_COMPANY_DATA_EMPTY));	
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>$infoarr));	
	}
});
/**
 *	根据ID获取公司详情
 */
$app->post("/info", function () use($app, $module) {
	
	$model = $module->loadModule('company/companymodel');

	$comp_id = intval($app->request()->post('comp_id'));//当前页

	$info = $model->getCompanyInfo($comp_id);
	if (empty($info)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_COMPANY_DATA_EMPTY));	
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>$info));	
	}
});

