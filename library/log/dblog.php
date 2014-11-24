<?php
/**
 *	author by jackluo
 *  net.webjoy@gmail.com
 */
include 'ilog.php';
class DBLog implements IDB
{
	//设置操作句柄
	private $handler = null;
	/**
	 *	日志数组
	 */
	public function write($logs=array()) {
		if(!is_array($logs) || empty($logs))
		{
			throw new Exception('the $logs parms must be array');
		}
		$content = join("\t",$logs)."\t\r\n";
	}
	public function setHandler($handler) {
		$this->handler = $handler;
	}
}