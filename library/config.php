<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class Config {

	protected static $_config;

	public static function &get($key = null)
	{
		if(null == self::$_config)
	    {
		    self::$_config = include PATH_SYSTEM_APP. 'config/config.php';
	    }
	    if (isset(self::$_config[$key]))
	    {
	        return self::$_config[$key];
	    }
	    return self::$_config;
	}
	
	public static function set($config)
	{
		 self::$_config = $config;
	}
}