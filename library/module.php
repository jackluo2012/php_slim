<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 * 	模块---缓存 + 数据库
 */
class Module extends Core
{

	protected $_db;
	public static $database;
	protected $_cache;
	public static $cache;
	public function __construct() {
		if( !self::$cache )
		{
			$cacheConfig = Config::get('cacheConfig');
			$this->_cache = Core::Cache_Factory($cacheConfig);
			self::$cache = $this->_cache;
		} else {
			$this->_cache = self::$cache;
		}
		if( !self::$database )
		{
			$dbConfig = Config::get('dbConfig');
			$this->_db = Core::DB_Factory($dbConfig);
			self::$database = $this->_db;
		} else {
			$this->_db = self::$database;
		}
	}


}