<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class DomainModel extends Module
{
	/**
	 *	init 初始化
	 */
	private $table = 'company';
	private $table_mysql = 'company_mysql';
	
	function __construct(){
		parent::__construct();
	}

	/**
	 *	get all domain list
	 *	
	 */
	
	function all_domain(){
		$domainlist = array();
		//设置缓存失效
		$key = Cache::key('cdomain');
		$domainlist = $this->_cache->hGetAll($key);
		if (empty($domainlist)) {
			$temparr = $this->_db->getAll("SELECT c.`domain`,cm.* FROM `company` c LEFT JOIN `company_mysql` cm ON c.`comp_id`=cm.`comp_id` WHERE c.`status`='1'");	
			$this->_cache->multi($key);
			foreach ($temparr as $value) {
				$resutl = $this->_cache->hset($key,$value['domain'],$value);
				$domainlist[$value['domain']] = serialize($value);
			}
			$this->_cache->exec($key);
		}
		return $domainlist;
	}

	function find_domain($domain){
		$domains = $this->all_domain();
		if (!empty($domains) && !empty($domains[$domain])) {
			return unserialize($domains[$domain]);
		}
		return ;
	}
}