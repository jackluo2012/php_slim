<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class CompanyModel extends Module
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
	 *	保存用公司
	 *	
	 */
	function save($data,$where=null){
		//设置缓存失效
		$key = Cache::key('cdomain');
		$this->_cache->remove($key);
		if($where){
			return $this->_db->update($this->table,$data,$where);
		}
		return $this->_db->insert($this->table,$data);
	}

	function saveCompany($data,$db_info){
		//加入事务
		$this->_db->startTrans();
		try {
			$return_id = $this->save($data);
			$db_info['comp_id'] = $return_id;
			$this->save_mysql($db_info);//save mysql			
			return $this->_db->commit();	
		} catch (Exception $e) {
			return $this->_db->rollback();
		}
		return false;
	}
	/**
	 *	保存mysql_info
	 *	
	 */
	function save_mysql($data,$where=null){
		//设置缓存失效
//		$key = Cache::key('cdomain');
//		$this->_cache->remove($key);
		if($where){
			return $this->_db->update($this->table_mysql,$data,$where);
		}
		return $this->_db->insert($this->table_mysql,$data);
	}

	/**
	 *	检查用户名是否已存在
	 */
	function getUserIsExist($name){
		$info = $this->_db->getRow("SELECT COUNT(*) AS total FROM `".$this->table."` WHERE `user_name`='{$name}'");
		if($info['total'] == '0'){
			return TRUE;
		}
		return false;
	}
	/**
	 *	检查域名是否存在
	 */
	function getDomainExist($domain){

		$info = $this->_db->getRow("SELECT COUNT(*) AS total FROM `".$this->table."` WHERE `domain_prefix`='{$domain}'");
		if($info['total'] == '0'){
			return TRUE;
		}
		return false;
	}
	/**
	 *　根据ID获取公司信息
	 */
	function getCompanyInfo($comp_id){
		
		return $this->_db->getRow("SELECT * FROM `".$this->table."` WHERE `comp_id`='{$comp_id}'");
	}
	/**
	 *	获取公司列表
	 */
	function getCompanyList(){

		return $this->_db->getAll("SELECT * FROM `".$this->table."`");
	}
}