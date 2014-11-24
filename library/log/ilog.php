<?php
/**
 *	net.webjoy@gmail.com
 *  author jackluo
 */
interface ILog
{
	/**
     * @brief 实现日志的写操作接口
     * @param array  $logs 日志的内容
     */
    public function write($logs = array());
}