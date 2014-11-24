<?php
interface IDB{

	public static function getInstance($host, $user, $password, $dbname);
	public function insert($table,$data);
	public function update($table,$data,$where);
	public function remove($table,$where);
	public function query($sql);
	public function getAll($sql);
	public function getRow($sql);
	public function startTrans();
	public function commit();
	public function rollback();
	public function close();
}