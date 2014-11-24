<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 * 权限验证
 */
class VerifyModel extends Module
{
	/**
	 *	init 初始化
	 */

	private $table ='auth_verify';
	function __construct(){
		parent::__construct();
	}
	/**
	 * 验证是否有权限
	 */
	public function verify($appkey,$secret){
		$appkey = intval($appkey);
		$data = array();
		$key = Cache::key('authinfo',$appkey);
		
		//从缓存中取数据
		$appSecret = $this->_cache->get($key);
		if(empty($appSecret)){
			//去数据库里面取
			$data =	$this->_db->getRow("SELECT `app_secret` FROM {$this->table} WHERE `id`='{$appkey}'");
			if(empty($data)){
				return false;
			}
			if($secret == $data['app_secret']){
				$this->_cache->set($key,$data['app_secret']);//保存到缓存中
				unset($data);
				return true;
			}
		}
		if($appSecret == $secret){
			return true;
		}
		return false;
	}
}