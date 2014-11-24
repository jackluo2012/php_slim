<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
interface ICache
{
	public static function getInstance($config);
	public function get($key);
	public function set($key,$val);
	public function remove($key);
	public function close();
}