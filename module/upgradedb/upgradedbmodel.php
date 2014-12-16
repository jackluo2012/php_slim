<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class UpgradeDbModel extends Module
{
	private $table = 'company_mysql';
	//构造函数
	function __construct()
	{
		parent::__construct();
	}
	/**
	 * get dbinfo
	 */
	function getDbList(){

		return $this->_db->getAll("SELECT * FROM `".$this->table."`");
	}
	/**
	 *	upgrade
	 */
	function upgrade($sql_file){

		// db info list
		$list = $this->getDbList();
		//print_r($list);
		//exit;
		if ($list) {
			foreach ($list as $k => $val) {
				$update = new IUpgradeDB($val['db_host'],$val['db_user'],$val['db_pwd'],$val['db_port'],$val['db_name']);
				$update->install_sql($sql_file);
				unset($update);						
			}
		}
		return true;
	}
}