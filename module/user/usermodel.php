<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class UserModel extends Module
{
	/**
	 *	init 初始化
	 */
	function __construct(){
		parent::__construct();
	}
	/**
	 *	保存用户名
	 *	
	 */
	function saveUser($data,$where=null){
		if($where){
			return $this->_db->update('author',$data,$where);
		}
		return $this->_db->insert('author',$data);
	}
	/**
	 *	返回一条数据
	 */
	function getUserById($id){
		$key = Cache::key('userinfo');
		$id = intval($id);
		return $this->_db->getRow("SELECT * FROM `author` WHERE `id`={$id}");
	}
	/**
	 *	返回一组数据
	 */
	function getAllUser(){
		return $this->_db->getAll("SELECT * FROM `author`");
	}
	/**
	 *	删除数据
	 */
	function removeUser($id){

		return $this->_db->remove('author',array('id'=>$id,'name'=>'jackluo'));
	}
}