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

		$info = $this->_db->getRow("SELECT COUNT(*) AS total FROM `".$this->table."` WHERE `domain`='{$domain}'");
		if($info['total'] == '0'){
			return TRUE;
		}
		return false;
	}
	/**
	 *　根据ID获取公司信息
	 */
	function getCompanyInfo($comp_id){
		return $this->_db->getRow("SELECT * FROM `".$this->table."` c LEFT JOIN `".$this->table_mysql."` cm ON c.`comp_id`=cm.`comp_id` WHERE c.`comp_id`='{$comp_id}'");
	}
	/**
	 *	获取公司列表
	 */
	function getCompanyList($page,$pagesize,$wheresqlarr){

		$list = array();
		$where = $comma = '';
		if(empty($wheresqlarr)) {
			$where = '1';
		} elseif(is_array($wheresqlarr)) {
			foreach ($wheresqlarr as $key => $value) {
				$where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
				$comma = ' AND ';
			}
		} else {
			$where = $wheresqlarr;
		}
		
		$company = $this->_db->getRow("SELECT count(*) AS total FROM `".$this->table."` WHERE ".$where);
		$total = $company['total'];
		if ($total<0) {
			return array('total'=>0,'data'=>$list);
		}
		$list = $this->_db->getAll("SELECT * FROM `".$this->table."` WHERE ".$where." ORDER BY `comp_id` DESC limit ".$pagesize*($page-1).",$pagesize");

		return array('total'=>$total,'data'=>$list);
	}
}