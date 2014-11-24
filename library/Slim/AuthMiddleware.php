<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 * 验证方法
 */
namespace Slim;

class AuthMiddleware extends \Slim\Middleware
{
	/**
	 * 拿到操作数据库
	 */
	private  $module;

	public function __construct(&$module){
		$this->module = $module;
	}
	public function call()
    {
        // 获取应用的引用
        $app = $this->app;
        //获取Header 信息
        $header = $app->request()->headers(); 
    	//
    	$do = $this->auth($header);
        if($do){
        	// 调用里层中间件和应用
	        $this->next->call();
        }else{
        	$app->applyHook(OUT_PUT,array('error'=>ERROR_CODE_ERROR_SECRET));
        }
    }
    /**
     *	查找Header 信息　进行匹配
     */
    private function auth($header){
    	$appkey = $header['Appkey'];
    	$appsecret = $header['Appsecret'];
        $verifyModel = $this->module->loadModule('verify/verifymodel');
        return $verifyModel->verify($appkey,$appsecret);
    }
}