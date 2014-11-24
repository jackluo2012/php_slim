<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class CacheModel extends Module
{
	/**
	 *
	 *	init 初始化
	 */
	function __construct(){
		parent::__construct();
	}
	public function get($key){
		$key = Cache::key($key);
		return $this->_cache->get($key);
	}
	public function set($key,$value)
	{
		$key = Cache::key($key);
		return $this->_cache->set($key,$value);
	}
}