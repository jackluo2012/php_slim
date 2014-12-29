<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class FileMangerModel extends Module
{
	/**
	 *	init 初始化
	 */
	private $table = 'goods_photo';
	private $table_company = 'company';
//	private $table_mysql = 'company_mysql';
	function __construct(){
		parent::__construct();
	}
	/**
	 *	获取文件列表
	 */
	function getFileMangerList($page,$pagesize,$data){

		$list = array();
		$where = ' 1';	
		//数据不为空
		if (!empty($data['comp_id'])) {
			$where .= " AND p.`comp_id`='".$data['comp_id']."' ";
		}
		if (!empty($data['ext'])) {
			$where .= " AND p.`ext`='".$data['ext']."' ";	
		}

		$company = $this->_db->getRow("SELECT count(*) AS total FROM `".$this->table."` WHERE ".$where);
		$total = $company['total'];
		if ($total<0) {
			return array('total'=>0,'data'=>$list);
		}
		$list = $this->_db->getAll("SELECT p.*,c.`comp_name` FROM `".$this->table."` p LEFT JOIN `".$this->table_company."` c ON p.`comp_id`=c.`comp_id` WHERE ".$where." ORDER BY `comp_id` DESC limit ".$pagesize*($page-1).",$pagesize");
//		print_r("SELECT p.*,c.`comp_name` FROM `".$this->table."` p LEFT JOIN `".$this->table_company."` c ON p.`comp_id`=c.`comp_id` WHERE ".$where." ORDER BY `comp_id` DESC limit ".$pagesize*($page-1).",$pagesize");
//		exit;
		return array('total'=>$total,'data'=>$list);
	}
	/**
	 *　根据ID获取公司信息
	 */
	function getFileInfo($photo_id){
		return $this->_db->getRow("SELECT * FROM `".$this->table."` WHERE c.`photo_id`='{$photo_id}'");
	}
}