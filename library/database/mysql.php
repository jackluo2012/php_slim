<?php
/**
 *	author by jackluo
 *  net.webjoy@gmail.com
 */
include 'idb.php';
class Mysql implements IDB
{
	/**
	 * 连接标识符
	 *
	 * @var integer
	 */
	private $connectId;
	
	//操作时间
	private $transTimes = 0; 
	//记录集
	private	$PDOStatement = null;
	//返回ID
	private $lastInsertId = null;
	private $dbname;
	//当前查询语句
	private static $_instance;
	//
	/**
	 * 数据库驱动初始化
	 *
	 * @param ustring $数据库名
	 * @param  string主机（端口）
	 * @param string 数据库用户
	 * @param string  数据库密码
	 */
	private function __construct( $host, $user, $password, $dbname){
		try{
			$this->dbname = $dbname;
			$this->connectId = new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password);
		}catch(PDOException $e){
			//这个应该存入日志表
			throw new Exception("Connect Error Infomation:".$e->getMessage());
		}
	}
	private function __clone(){}
	public static function getInstance($host, $user, $password, $dbname){
		if(!(self::$_instance instanceof self))   
    	{    
    	   self::$_instance = new self($host, $user, $password, $dbname);    
    	}  
    	return self::$_instance;
	}
	/**
	 * 释放查询结果
	 */
	public function free(){
		$this->PDOStatement = null;
	}
	/**
	 *获得所有的查询数据
	 */
	public function getAll($sql=null){
		if($sql != null){
			$this->query($sql);
		}	
		return $this->PDOStatement->fetchAll(constant('PDO::FETCH_ASSOC'));
	}
	/**
	 *	获得一条查询结果
	 */
	public function getRow($sql=null){
		if($sql !=null)
		{
			$this->query($sql);
		}
		//返回　数组集
		return $this->PDOStatement->fetch(constant('PDO::FETCH_ASSOC'),constant('PDO::FETCH_ORI_NEXT'));
	}
	/**
	 *	执行sql语句，自动判断力时行　查询或者执行操作
	 */
	public function doSql($sql=''){
		if ($this->isMainIps($sql)) {
			return $this->execute($sql);
		}else{
			return $this->getAll($sql);
		}
	}
	/**
	 *	根据指定ID查找表中记录
	 * 
	 */
	public function findById($tabName,$priId,$fields='*')
	{
		$sql = 'SELECT %s FROM %s WHERE id=%d';   
        return $this->getRow(sprintf($sql, $this->parseFields($fields), $tabName, $priId));
	}
	/**
	 *	播放(单条)记录
	 */
	public function insert($table,$data)
	{
		//过滤提交数据
		$data = $this->filterPost($table,$data);
		foreach ($data as $key => $val) {
			if (is_array($val) && strtolower($val[0]=='exp')) {
				$val = $val[1];
			}elseif(is_scalar($val)){
				$val = $this->fieldFormat($val);	
			}else{
				continue;
			}
			$data[$key] = $val;
		}
		$fields = array_keys($data);
		array_walk($fields, array($this,'addSpecialChar'));//过虑
		$fieldsStr = implode(',', $fields);
		$values = array_values($data);
		$valuesStr = implode(',', $values);
		$sql = 'INSERT INTO '.$table.' ('.$fieldsStr.') VALUES ('.$valuesStr.')'; 
		return $this->execute($sql);
	}
	/**  
    * 保存某个字段的值  
    * @access function  
    * @param string $field 要保存的字段名  
    * @param string $value 字段值  
    * @param string $table 数据表  
    * @param string $where 保存条件  
    * @param boolean $asString 字段值是否为字符串  
    * @return void  
    */   
	public function update($table,$sets,$where){
		$sets = $this->filterPost($table,$sets);   
        $sql = 'UPDATE '.$table.' SET '.$this->parseSets($sets).$this->parseWhere($where);   
        return $this->execute($sql);
	}
	/**  
    * 删除记录  
    * @access function  
    * @param mixed $where 为条件Map、Array或者String  
    * @param string $table 数据表名  
    * @param string $limit  
    * @param string $order  
    * @return false | integer  
    */   
	public function remove($table,$where) {   
        $sql = 'DELETE FROM '.$table.$this->parseWhere($where);   
        return $this->execute($sql);   
    }
    /**  
    * 关闭数据库  
    * @access function  
    */   
    public function close() {   
        $this->connectId = null;   
    }  
	/**  
    * where分析  
    * @access function  
    * @param mixed $where 查询条件  
    * @return string  
    */   
    public  function parseWhere($where) {   
        $whereStr = '';   
        if(is_string($where) || is_null($where)) {   
            $whereStr = $where;   
        }else if(is_array($where)){
        	foreach ($where as $key => $value) {
        		$whereStr .= $whereStr==''?('`'.$key."`='".$value."'"):(' AND `'.$key."`='".$value."'");	
        	}
        }  
        return empty($whereStr)?'':' WHERE '.$whereStr;   
    } 
    /**  
    * fields分析  
    * @access function  
    * @param mixed $fields  
    * @return string  
    */   
    function parseFields($fields) {   
        if(is_array($fields)) {   
            array_walk($fields, array($this, 'addSpecialChar'));   
            $fieldsStr = implode(',', $fields);   
        }else if(is_string($fields) && !empty($fields)) {   
            if( false === strpos($fields,'`') ) {   
                $fields = explode(',',$fields);   
                array_walk($fields, array($this, 'addSpecialChar'));   
                $fieldsStr = implode(',', $fields);   
            }else {   
                $fieldsStr = $fields;   
            }   
        }else $fieldsStr = '*';   
        return $fieldsStr;   
    } 
    /**  
    * sets分析,在更新数据时调用  
    * @access function  
    * @param mixed $values  
    * @return string  
    */   
    private function parseSets($sets) {   
        $setsStr = '';   
        if(is_array($sets)){   
            foreach ($sets as $key=>$val){   
                $key = $this->addSpecialChar($key);   
                $val = $this->fieldFormat($val);   
                $setsStr .= "$key = ".$val.",";   
            }   
            $setsStr = substr($setsStr,0,-1);   
        }else if(is_string($sets)) {   
            $setsStr = $sets;   
        }   
        return $setsStr;   
    }   
    /**  
    * 字段格式化  
    * @access function  
    * @param mixed $value  
    * @return mixed  
    */   
    private function fieldFormat(&$value) {   
        if(is_int($value)) {   
            $value = intval($value);   
        } else if(is_float($value)) {   
            $value = floatval($value);   
        } elseif(preg_match('/^\(\w*(\+|\-|\*|\/)?\w*\)$/i',$value)){   
            // 支持在字段的值里面直接使用其它字段   
            // 例如 (score+1) (name) 必须包含括号   
            $value = $value;   
        }else if(is_string($value)) {   
            $value = '\''.$this->escape_string($value).'\'';   
        }   
        return $value;   
    }   
    /**  
    * 字段和表名添加` 符合  
    * 保证指令中使用关键字不出错 针对mysql  
    * @access function  
    * @param mixed $value  
    * @return mixed  
    */   
    private function addSpecialChar(&$value) {   
        if( '*' == $value || false !== strpos($value,'(') || false !== strpos($value,'.') || false !== strpos($value,'`')) {   
        //如果包含* 或者 使用了sql方法 则不作处理   
        } elseif(false === strpos($value,'`') ) {   
            $value = '`'.trim($value).'`';   
        }   
        return $value;   
    }         
	/**
	 * 执行查询
	 */
	public function query($sql=''){
		//获取数据库联接
		if(!$this->connectId){
			return false;
		}
		//会影响效率
		$this->queryStr = $sql;
		if(!empty($this->PDOStatement)){
			$this->free();	
		} 
		$this->PDOStatement = $this->connectId->prepare($this->queryStr);
		return $this->PDOStatement->execute();

	}
	/**  
    * SQL指令安全过滤  
    * @access function  
    * @param string $str SQL指令  
    * @return string  
    */   
    public function escape_string($str) {   
        return addslashes($str);   
    }   
	/**
	 *	执行SQL
	 */
	public function execute($sql=''){
		if(!$this->connectId){
			return false;
		}
		try{
			$this->queryStr = $sql;   
        	//释放前次的查询结果   
        	if( !empty($this->PDOStatement) ) $this->free();
			$result = $this->connectId->exec($sql);
			if (false == $result) {
				return false;
			}
			return $this->connectId->lastInsertId();
		}catch (PDOException $e){
			//这个应该存入日志表
			throw new Exception("Connect Error Infomation:".$e->getMessage());
		}
	}

	//允许的操作
	public function isMainIps($query){
        $queryIps = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK';   
        if (preg_match('/^\s*"?(' . $queryIps . ')\s+/i', $query)) {   
            return true;   
        }   
        return false;		
	}

	/**
	 *	过滤POST提交数据
	 */
	public function filterPost($table,$data){
		$table_column = $this->getFields($table);
		$newdata = array();
		foreach ($table_column as $key=>$val){   
            if(array_key_exists($key,$data) && ($data[$key])!==''){   
                $newdata[$key] = $data[$key];   
            }   
        } 
        return $newdata;  
	}
 	/**  
    * 取得数据表的字段信息  
    * @access function  
    * @return array  
    */   
    public function getFields($tableName) {   
        // 获取数据库联接   
        $sql = "SELECT   
        ORDINAL_POSITION ,COLUMN_NAME, COLUMN_TYPE, DATA_TYPE,   
        IF(ISNULL(CHARACTER_MAXIMUM_LENGTH), (NUMERIC_PRECISION + NUMERIC_SCALE), CHARACTER_MAXIMUM_LENGTH) AS MAXCHAR,   
        IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY, EXTRA, COLUMN_COMMENT   
        FROM   
        INFORMATION_SCHEMA.COLUMNS   
        WHERE   
        TABLE_NAME = :tabName AND TABLE_SCHEMA='".$this->dbname."'";   
        $this->queryStr = sprintf($sql, $tableName);   
        $sth = $this->connectId->prepare($sql);   
        $sth->bindParam(':tabName', $tableName);   
        $sth->execute();   
        $result = $sth->fetchAll(constant('PDO::FETCH_ASSOC'));   
        $info = array();   
        foreach ($result as $key => $val) {   
            $info[$val['COLUMN_NAME']] = array(   
            'postion' => $val['ORDINAL_POSITION'],   
            'name' => $val['COLUMN_NAME'],   
            'type' => $val['COLUMN_TYPE'],   
            'd_type' => $val['DATA_TYPE'],   
            'length' => $val['MAXCHAR'],   
            'notnull' => (strtolower($val['IS_NULLABLE']) == "no"),   
            'default' => $val['COLUMN_DEFAULT'],   
            'primary' => (strtolower($val['COLUMN_KEY']) == 'pri'),   
            'autoInc' => (strtolower($val['EXTRA']) == 'auto_increment'),   
            'comment' => $val['COLUMN_COMMENT']   
            );   
        }   
        return $info;   
    } 
	/**
	 *	开启事务
	 */
	public function startTrans(){
		if(!$this->connectId){
			return false;
		}
		if($this->transTimes ==0){
			$this->connectId->beginTransaction();
		}
		$this->transTimes++;
		return ;
	}
	/**
	 *	提交事务
	 */
	public function commit(){
		if(!$this->connectId){
			return false;
		}
		if($this->transTimes > 0){
			$result = $this->connectId->commit();
			$this->transTimes = 0;
			if(!$result){

				return false;
			}
		}
		return true;
	}	
	//事务回滚
	public function rollback(){
		if(!$this->connectId){
			return false;
		}
		if ($this->transTimes > 0) {   
            $result = $this->connectId->rollback();   
            $this->transTimes = 0;   
            if(!$result){   
                return false;   
            }   
        }   
        return true;
	}
}