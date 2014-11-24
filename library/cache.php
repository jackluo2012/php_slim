<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class Cache {

	private static $_keys = array();
	//加载缓存数据
	/**
     * 取得缓存的Key
     * @param String $key
     * @param array | String $subKey
     * @throws Exception
     * @return string
     */
	public static function key($key, $subKey = '')
	{
		 if( empty(self::$_keys) )
        {
            self::$_keys = Config::get('cacheKey');
        }
        if(isset(self::$_keys[$key]))
        {
            if(!empty($subKey)){
                if(is_array($subKey)){
                    return self::$_keys[$key].':'. implode('_',$subKey);
                }else{
                    return self::$_keys[$key].':'.$subKey;    
                }    
            }
        	return self::$_keys[$key];
        }
        throw new Exception('NOT DEFINED THE CACHE KEY:'. $key, E_USER_ERROR);
	}
}
