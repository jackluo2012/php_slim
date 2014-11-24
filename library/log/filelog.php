<?php
/**
 *	author by jackluo
 *  net.webjoy@gmail.com
 */
include 'ilog.php';
class FileLog implements IDB
{
	//设置操作句柄
	private $handler = null;
	/**
	 *	日志数组
	 */
	public function write($logs=array()) {
		if($this->path == '')
		{
			throw new IException('the file path is undefined');
		}
		$content = join("\t",$logs)."\t\r\n";
		//生成路径
		$fileName = $this->path;
		if(!file_exists($dirname = dirname($fileName)))
		{
			IFile::mkdir($dirname);
		}
		$result = error_log($content, 3 ,$fileName);

		if($result)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	public function setHandler($handler) {
		$this->handler = $handler;
	}
}