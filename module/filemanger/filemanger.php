<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
/**
 *	获取文件资源列表
 */
$app->post("/list", function () use($app, $module) {
	$model = $module->loadModule('filemanger/filemangermodel');
	$page = intval($app->request()->post('page'));//当前页
	$pagesize = intval($app->request()->post('pagesize'));//分页大小
	$comp_id = intval($app->request()->post('comp_id'));//公司ID
	$ext = $app->request()->post('ext');//文件扩张名
	//条件
	if (!empty($comp_id)) {
		$data['comp_id']=$comp_id;
	}
	if (!empty($ext)) {
		$data['ext'] = $ext;
	}

	if ($page<=0) {
		$page = 1;
	}
	if (empty($pagesize)) {
		$pagesize = 10;
	}
	$infoarr = $model->getFileMangerList($page,$pagesize,$data);	
	if (empty($infoarr)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_COMPANY_DATA_EMPTY));	
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>$infoarr));	
	}
});
/**
 *	根据ID文件详情
 */
$app->post("/info", function () use($app, $module) {

	$model = $module->loadModule('filemanger/filemangermodel');

	$photo_id = intval($app->request()->post('photo_id'));//当前页

	$info = $model->getFileInfo($photo_id);
	if (empty($info)) {
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_FILE_DATA_EMPTY));	
	}else{
		$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_OK,'data'=>$info));	
	}
});