<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
include 'icache.php'; //接口
class RedisCluster implements ICache
{

	private $_node = array();
    private $_nodeData = array();
    private $_keyNode = 0;
    private $_redis = null;
	private $_virtualNodeNum = 64;

	private function __construct($config) {
		if(!$config){
			throw new Exception('Cache config NULL');	
		} 
		foreach ($config as $key => $value) {
	        for ($i = 0; $i < $this->_virtualNodeNum; $i++) {
	            $this->_node[sprintf("%u", crc32($value . '_' . $i))] = $value . '_' . $i;
	        }
		}
	    ksort($this->_node);
	}

    private function __clone(){}
	/**
     * 单例，保证只有一个实例
     */
    static public function getInstance($config) {
        static $redisObj = null;
        if (!is_object($redisObj)) {
            $redisObj = new self($config);
        }
        return $redisObj;
    }
	/**
	 * hash 一致
     *  _connectRedis
	 */
	private function _connectRedis($key){
		$this->_nodeData = array_keys($this->_node);
        $this->_keyNode = sprintf("%u", crc32($key));
        $nodeKey = $this->_findServerNode();
        //如果超出环，从头再用二分法查找一个最近的，然后环的头尾做判断，取最接近的节点
        if ($this->_keyNode > end($this->_nodeData)) {
		    $this->_keyNode -= end($this->_nodeData);
		    $nodeKey2 = $this->_findServerNode();
		    if (abs($nodeKey2 - $this->_keyNode) < abs($nodeKey - $this->_keyNode)){
		    	$nodeKey = $nodeKey2;
		    }
        }
        list($config, $num) = explode('_', $this->_node[$nodeKey]);
        if (!$config){
        	throw new Exception('Cache config Error');	
        } 
        if (!isset($this->_redis[$config])) {
            $this->_redis[$config] =  new Redis;
            list($host, $port) = explode(':', $config);
            try{
                $this->_redis[$config]->pconnect($host,$port);    
            }catch(Exception $e){
                throw new Exception('Cache config Error '.$e->getMessage());  
            }
            
        }
        return $this->_redis[$config];
	}
	/**
     * 采用二分法从虚拟redis节点中查找最近的节点
     * @param unknown_type $m
     * @param unknown_type $b
     */
    private function _findServerNode($m = 0, $b = 0) {
        $total = count($this->_nodeData);
        if($total != 0 && $b == 0) $b = $total - 1;
        if($m < $b){
            $avg = intval(($m+$b) / 2);
            if($this->_nodeData[$avg] == $this->_keyNode){
                return $this->_nodeData[$avg];
            }elseif($this->_keyNode < $this->_nodeData[$avg] && ($avg-1 >= 0)){
                return $this->_findServerNode($m, $avg-1);  
            }else{
               return $this->_findServerNode($avg+1, $b);  
            } 
        }
        if(abs($this->_nodeData[$b] - $this->_keyNode) < abs($this->_nodeData[$m] - $this->_keyNode)){
            return $this->_nodeData[$b];
        }else{
            return $this->_nodeData[$m];
        } 
    }
    /**
     *	设置值
     */
	public function set($key, $value, $expire = 0) {
		// 永不超时
        if($expire == 0){
            $ret = $this->_connectRedis($key)->set($key, $value);
        }else{
            $ret = $this->_connectRedis($key)->setex($key, $expire,$value);
        }
        return $ret;
	}
	/**
	 *	get 到值
	 */
	public function get($key) {
        return $this->_connectRedis($key)->get($key);
    }
    /**
     * 删除缓存
     * @param string || array $key 缓存KEY，支持单个健:"key1" 或多个健:array('key1','key2')
     * @return int 删除的健的数量
     */
    public function remove($key){
        return $this->_connectRedis($key)->delete($key);
    }
    /**
     *	lpush 
     */
    public function lpush($key,$value){
    	return $this->_connectRedis($key)->lpush($key,$value);
    }

    /**
     *	add lpop
     */
    public function lpop($key){
		return $this->_connectRedis($key)->lpop($key);
    }
    /**
     * lrange 
     */
    public function lrange($key,$start,$end){
    	return $this->_connectRedis($key)->lrange($key,$start,$end);	
    }

    /**
     *	set hash opeation
     */
    public function hset($name,$key,$value){
    	if(is_array($value)){
    		return $this->_connectRedis($key)->hset($name,$key,serialize($value));	
    	}
    	return $this->_connectRedis($key)->hset($name,$key,$value);
    }
    /**
     *	get hash opeation
     */
    public function hget($name,$key = null,$serialize=true){
    	if($key){
    		$row = $this->_connectRedis($key)->hget($name,$key);
    		if($row && $serialize){
    			unserialize($row);
    		}
    		return $row;
    	}
    	return $this->_connectRedis($key)->hgetAll($name);
    }
    /**
     *  get all hash opeation
     */
    public function hGetAll($key){
        return $this->_connectRedis($key)->hgetAll($key);
    }
    /**
     *	delete hash opeation
     */
    public function hdel($name,$key = null){
    	if($key){
    		return $this->_connectRedis($key)->hdel($name,$key);
    	}
    	return $this->_connectRedis($key)->hdel($name);
    }
    /**
     * Transaction start
     */
    public function multi($key){
    	return $this->_connectRedis($key)->multi();	
    }
    /**
     * Transaction send
     */

    public function exec($key){
    	return $this->_connectRedis($key)->exec();	
    }
    /**
     *	关闭
     * 后面来优化算法了
     */
    public function close(){
    	foreach ($this->_node as $key => $value) {
    		list($config, $num) = explode('_',$value);
    		$this->_redis[$config]->close();
    	}
    }
} 