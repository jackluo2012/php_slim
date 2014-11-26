<?php
/**
 * author jackluo
 * net.webjoy@gmail.com
 */
class StreamModel extends Module
{
	
	/**
	 *	允许上传的流文件
	 */
	private $allowType = array('jpg','gif','png','zip','rar','docx','doc');
	
	/**
	 *	init 初始化
	 */
	function __construct(){
		parent::__construct();
	}
	
	/**
	 *	检查是否是允许的流文件
	 */
	private function checkType($ext){
		return in_array($ext, $this->allowType);
	}

	/**
	 *	@para $buff $ext
	 *	$buff 文件流
	 *	$ext  文件扩长名
	 *  $filename 文件名
	 */
	function saveStream($buff,$ext,$filename,$md5file){

		if(!$this->checkType($ext)){
			return '10001';
		}	

		$info = $this->get_md5_file($md5file);
		if(empty($info)){
			//fastDFS		
	        $fastdfs = IFdfs::getInstance(Config::get('fastDFS'));
	        //存储
	        $info = $fastdfs->upload_filebuff($buff,$ext);
	        //上传成功后图片信息
			if($info){
				$insertData = array(
					'photo_id'  	=> $md5file,
					'img' 			=> $info['filename'],
					'file_name' 	=> $filename,
					'group' 		=> $info['group_name'],
				);
				//不处理返回的值了
				$this->insert($insertData);
				return $insertData;	
			}
			return '10002';
		}
		return $info;
	}

	// gen ju md5 huo qu wen jian
	private function get_md5_file($fileMD5){
		return $this->_db->getRow("SELECT * FROM `goods_photo` WHERE `photo_id`='{$fileMD5}'");
	}

	/**
	 * @brief 图片信息入库
	 * @param array $insertData 要插入数据
	 *		  object $photoObj  图库对象
	 */
	private function insert($insertData)
	{
		return $this->_db->insert('goods_photo',$insertData);
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