<?php
/**
 *	author jackluo
 *  email net.webjoy@gmail.com
 */
class IUpgradeDB{
	private $db_host,$db_user,$db_pwd,$db_port,$db_name,$is_connect;
	public function __construct($db_host,$db_user,$db_pwd,$db_port,$db_name){
		$this->db_host = $db_host;
		$this->db_user = $db_user;
		$this->db_pwd  = $db_pwd;
		$this->db_port = $db_port;
		$this->db_name = $db_name;
		$this->is_connect = false;
	}

	/**
	 *	测试链接数据库
	 */
	function check_mysql()
	{
		$is_connect = false;
		if($this->db_host != '' && function_exists('mysql_connect'))
		{
			$this->is_connect = mysql_connect($this->db_host,$this->db_user,$this->db_pwd);
		}
		if($this->is_connect)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * 解析备份文件中的SQL
	 */
	function parseSQL($fileName)
	{
		//执行sql query次数的计数器 默认值
		$queryTimes = 0;

		//与前端交互的频率(数值与频率成反比,0表示关闭交互)
		$waitTimes  = 5;

		$percent   = 0;
		$fhandle   = fopen($fileName,'r');
		$firstLine = fgets($fhandle);
		rewind($fhandle);// she 0
//		$path = $_SERVER['DOCUMENT_ROOT'].'/err.log';
		//跨过BOM头信息
		$charset[1] = substr($firstLine,0,1);
		$charset[2] = substr($firstLine,1,1);
		$charset[3] = substr($firstLine,2,1);
		if(ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191)
		{
			fseek($fhandle,3);
		}

		//计算安装进度
		$totalSize  = filesize($fileName);

		while(!feof($fhandle))
		{
			$lstr = fgets($fhandle);     //获取指针所在的一行数据

			//判断当前行存在字符
			if(isset($lstr[0]) && $lstr[0]!='#')
			{
				$prefix = substr($lstr,0,2);  //截取前2字符判断SQL类型
				switch($prefix)
				{
					case '--' :
					case '//' :
					{
						continue;
					}

					case '/*':
					{
						if(substr($lstr,-5) == "*/;\r\n" || substr($lstr,-4) == "*/\r\n"){
							continue;
						}else{
							$this->skipComment($fhandle);
							continue;
						}
					}

					default :
					{
						$sqlArray[] = trim($lstr);
						
						if(substr(trim($lstr),-1) == ";")
						{

							$rcount   = 1;
//							$sqlStr   = str_ireplace(array('pre_','pre_'),$db_pre,join($sqlArray),$rcount); //更换表前缀
			
							$sqlStr = join($sqlArray);

							$sqlArray = array();
							$result   = mysql_query($sqlStr,$this->is_connect);
							/*
							$queryTimes++;
							if($waitTimes > 0 && ($queryTimes/$waitTimes == 1))
							{
								$queryTimes = 0;

								//计算安装进度百分比
								$percent    = ftell($fhandle)/($totalSize+1);
								$this->sqlCallBack($sqlStr,$result,$percent,$is_test);
								set_time_limit(1000);
							}*/
						}
					}
				}
			}
		}
	}
	/**
	 * 略过注释
	 */
	function skipComment($fhandle)
	{
		//$lstr = fgets($fhandle,4096);
		$lstr = fgets($fhandle);
		if(substr($lstr,-5) == "*/;\r\n" || substr($lstr,-4) == "*/\r\n"){
			return true;
		}else{
			$this->skipComment($fhandle);
		}
	}

	/** 
	 * sql回调函数 -- 前端反馈
	 */
	function sqlCallBack($sql,$result,$percent)
	{
		//创建表
		if(preg_match('/create\s+table\s+(\S+)/i',$sql,$match))
		{
			$tableName = isset($match[1]) ? $match[1] : '';
			$message   = '创建表'.$tableName;
		}
		//插入数据
		else if(preg_match('/insert\s+into/i',$sql))
		{
			$message   = '插入数据';
		}
		//其余操作
		else
		{
			$message   = '执行SQL';
		}

		//判断sql执行结果
		if($result)
		{
			$isError  = false;
			$message .= '...';
		}
		else
		{
			$isError  = true;
			$message .= ' 失败! '.$sql.'<br />'.mysql_error();
		}

		$return_info = array(
			'isError' => $isError,
			'message' => $message,
			'percent' => $percent
		);

		$this->showProgress($return_info);
		usleep(5000);
	}

	/** 
	 * 	输出json数据 -- 前端反馈
	 */
	function showProgress($return_info)
	{
		echo '<script type="text/javascript">parent.update_progress('.JSON::encode($return_info).');</script>';
		flush();
		if($return_info['isError'] == true)
		{
			exit;
		}

	/*	
	<div id='install_state' style='display:none'>
		<strong>安装进度</strong>
		<label>正在安装,请稍后...</label>
		<div class="loading"><span style="width:0px;"></span><img src="./images/loading.gif" style='width:500px;height:20px' /></div>
	</div>
	<script type='text/javascript'>
	//更新进度条
	function update_progress(obj)
	{
		var whole       = $('#install_state img').css('width');
		var nowProgress = obj.percent ? parseInt(whole) * parseFloat(obj.percent) : 0;

		if(obj.isError == true)
		{
			$('#error_div').show();
			$('#error_div label').html(obj.message);
			$('#install_state label').addClass('red_box');
			$('.next').attr('disabled','');
		}
		else
		{
			$('#install_state label').removeClass('red_box');
		}

		$('#install_state label').html(obj.message);
		$('#install_state .loading span').css('width',nowProgress);

		if(obj.percent == 1)
		{
			window.location.href = 'index.php?act=install_4';
		}
	}
	</script>
	*/	


	}




	/**
	 *	安装mysql数据库
	 */
	function install_sql($sql_file)
	{
		//链接mysql数据库
		$mysql_link = $this->check_mysql();
		if(!$mysql_link)
		{
			throw new Exception("Mysql Connect Error");
			
		}

		//检测SQL安装文件
		if(!file_exists($sql_file))
		{
			throw new Exception("Mysql File Update Not fund", 1);
		}

		mysql_query("set names 'UTF8'",$this->is_connect);
		if(!@mysql_select_db($this->db_name,$this->is_connect))
		{
			throw new Exception("Mysql Database Not Exits");
		}
		//安装SQL
		$this->parseSQL($sql_file);	
		mysql_close($this->is_connect);
	}
	/*
	function install_sql()
	{

		//链接mysql数据库
		$mysql_link = $this->check_mysql();
		if(!$mysql_link)
		{
			$this->showProgress(array('isError' => true,'message' => 'mysql链接失败'.mysql_error()));
		}

		//检测SQL安装文件
		$sql_file = ROOT_PATH.'./install/iwebshop.sql';
		if(!file_exists($sql_file))
		{
			$this->showProgress(array('isError' => true,'message' => '安装的SQL文件'.basename($sql_file).'不存在'));
		}


		//执行SQL,创建数据库操作
		
		mysql_query("set names 'UTF8'",$this->is_connect);

		if(!@mysql_select_db($this->db_name))
		{
			$DATABASESQL = '';
			if(version_compare(mysql_get_server_info(), '4.1.0', '>='))
			{
		    	$DATABASESQL = "DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
			}
			if(!mysql_query('CREATE DATABASE `'.$this->db_name.'` '.$DATABASESQL))
			{
				$this->showProgress(array('isError' => true,'message' => '用户权限受限，创建'.$this->db_name.'数据库失败，请手动创建数据表'));
			}
		}
		
		if(!@mysql_select_db($this->db_name,$this->is_connect))
		{
			$this->showProgress(array('isError' => true,'message' => $this->db_name.'数据库不存在'.mysql_error($this->is_connect)));
		}

		//安装SQL
		$this->parseSQL($sql_file);
		//执行完毕
		$this->showProgress(array('isError' => false,'message' => '安装完成','percent' => 1));
		mysql_close($this->is_connect);
	}
	*/
}
/*
	$db_host  = '127.0.0.1';
	$db_user  = 'root';
	$db_pwd   = 'admin';
	$db_port  = '3306';
	$db_name  = 'platform_test';
	$sql_file = $_SERVER['DOCUMENT_ROOT'].'/test.sql';

//	print_r($sql_file);
	$update = new UploadMysql($db_host,$db_user,$db_pwd,$db_port,$db_name);
	$update->install_sql($sql_file);
	echo 1212;
*/
