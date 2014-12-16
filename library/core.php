<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 * 	核心类库
 * 工厂方法　+　加载类库　公共 方法
 */
class Core {
	
	protected static $_m = array();
	//*
	public function __construct() {
		set_error_handler(array('Core','_appError'));
		set_exception_handler(array('Core','_appException'));
		//注册自动加载类库
		spl_autoload_register(array($this, 'autoload'));
	}
	/**
	 *	错误
	 */
	static public function _appError($errno, $errstr, $errfile, $errline) {
		
		switch ($errno) {
			case E_ERROR:
			case E_USER_ERROR:
				$errorStr = "[$errno] $errstr ".basename($errfile)." Line: $errline.";
				halt($errorStr);
				break;
			case E_STRICT:
			case E_USER_WARNING:
			case E_USER_NOTICE:
			default:
				$errorStr = "[$errno] $errstr ".basename($errfile)." Line: $errline.";
				//halt($errorStr);
				break;
		}
	}
	
	static public function _appException($e) {
		if(__DEBUG__){
			$error = $e->__toString();
			halt($error);
		} else {
			exit;
		}
	}

	/**
     * Module autoloader
     */
    public static function autoload($className)
    {
        if(!preg_match('|^\w+$|',$className))
		{
			throw new Exception($className." Class File Not Found!");	
		}

		if(isset(self::$_coreClasses[$className]))
		{
			include(PATH_SYSTEM_APP.'library/'.self::$_coreClasses[$className]);
			return true;
		}
		throw new Exception($className." Class File Not Found!");
    }
	
	/**
	 * 加载类库
	 * $this->loadModule('user/usermodel')
	 */
	public function &loadModule($module, $new = true) {
		if (!empty(self::$_m[$module])){
			return self::$_m[$module];
		}
		//组织路径
		$moduleName = PATH_SYSTEM_APP.'module/'.$module.'.php';
		if (file_exists($moduleName)){
			include_once $moduleName;
			if ($new) {
				$modarr = explode('/', $module);
				$class  = end($modarr);
				$mod    = new $class();
				self::$_m[$module] = $mod;
				return $mod;
			}
		}
		throw new Exception($module." Class File Not Found!");
	}
	/**
	 *	初始化数据类工厂方法
	 */
	protected static function DB_Factory($dbConfig){
		$type = $dbConfig['type']; //类型
		$dbInfo = $dbConfig['info'];//联接信息
		//检查文件是否存在
		if(file_exists(PATH_SYSTEM_APP.'library/database/'.$type.'.php')){
			require_once(PATH_SYSTEM_APP.'library/database/'.$type.'.php');
			$classname = ucfirst($type);
			return $classname::getInstance($dbInfo['host'],$dbInfo['user'],$dbInfo['password'],$dbInfo['dbname']);
		}
		throw new Exception('Database Class Not Found');
	}
	/**
	 *	初始化缓存类工厂方法
	 */
	protected static function Cache_Factory($cacheConfig){
		$type = $cacheConfig['type']; //类型
		$cacheInfo = $cacheConfig['info'];//联接信息
		//检查文件是否存在
		if(file_exists(PATH_SYSTEM_APP.'library/cache/'.$type.'.php')){
			require_once(PATH_SYSTEM_APP.'library/cache/'.$type.'.php');
			$classname= ucfirst($type);
			return $classname::getInstance($cacheInfo);
		}
		throw new Exception('Cache Class File not found');
	}

    //系统内核所有类文件注册信息
	public static $_coreClasses = array(
        'IUpload'			=>	'util/iupload.php',
        'ITime'				=>	'util/itime.php',
        'IFile'				=>	'util/ifile.php',
        'IFilter'			=>	'util/ifilter.php',
        'IMysqlDiff'		=>	'util/imysqldiff.php',
        'IUpgradeDB'		=>	'util/iupgradedb.php',
        'ImagickService'	=>	'util/imagickservice.php',
        'IFdfs'				=>	'util/ifast.php',
        'Config'			=>	'config.php',
        'Cache'				=>	'cache.php',
        'Module'			=>	'module.php',
    );

}