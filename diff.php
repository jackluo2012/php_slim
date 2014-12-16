<?php
/**
  * author jackluo
  * net.webjoy@gmail.com
  */
class IMysqlDiff
{

	
	private $master,$slave;
	

	public function __construct()
	{
		$this->master = array();
		$this->slave = array();
	}

	/**
	 *	master
	 */
	
	public function setMasterTable($host,$user,$pwd,$dbname){

		$conn 	 = mysql_connect($host,$user,$pwd); 
		if (!$conn) {
			throw new Exception("Database Connect Error");
		}

		$select  = mysql_select_db($dbname,$conn); //标准的数据库 
		if (!$select) {
			throw new Exception("SELECT Database Error");	
		}
		
		$q = mysql_query("show tables"); 
		while($s = mysql_fetch_array($q)){ 
			$name = $s[0]; 
			$q1 = mysql_query("desc $name"); 
			while ($s1 = mysql_fetch_array($q1)) { 
				$this->master[$name][] =$s1[0]; 
			} 
		} 
		mysql_close($conn);
	}
	

	/**
	 *	Slave 
	 */
	
	public function setSlaveTable($host,$user,$pwd,$dbname){
		$conn 	  = mysql_connect($host,$user,$pwd); 
		if (!$conn) {
			throw new Exception("Database Connect Error");
		}

		$select  = mysql_select_db($dbname,$conn); //标准的数据库 
		if (!$select) {
			throw new Exception("SELECT Database Error");	
		}
		
		$q = mysql_query("show tables"); 
		while($s = mysql_fetch_array($q)){ 
			$name = $s[0]; 
			$q1 = mysql_query("desc $name"); 
			while ($s1 = mysql_fetch_array($q1)) { 
				$this->slave[$name][] =$s1[0]; 
			} 
		} 
		mysql_close($conn);
	}

	/**
	 *	comparison
	 */
	
	public function comparison(){
		$f = $e = array(); 
		$str = $fuhao =''; 
		foreach($this->master as $k=>$v){ 
			
			if (!empty($this->slave[$k])){
				if (!is_array($this->slave[$k])) {
					$e[] = $k; 	//缺少表 
				}else if(count($this->slave[$k]) <> count($v)){
					foreach($v as $k1=>$v1){ 
						if(!in_array($v1,$this->slave[$k])){ 
							$f[$k][] = $v1; ////缺少表的字段 
						} 
					}
				}
			}else{
				$e[] = $k;
			}
		}
		return array('tables'=>$e,'fields'=>$f);
	}
	
}

/*
$diff = new IMysqlDiff();
$diff->setMasterTable('127.0.0.1','root','admin','platform_test');
$diff->setSlaveTable('127.0.0.1','root','admin','platform');
$result = 	$diff->comparison();

print_r($result);
//*/
//*/
