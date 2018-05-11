<?php
/**
* 文件上传
*/
class FileUploaderHelper
{
	/**
	 * 允许上传的文件类型
	 * @var array
	 */
	public $allowType = array(); 

	/**
	 * 最大上传文件大小,单位：M
	 * @var float
	 */
	public $maxSize;

	/**
	 * 原文件名
	 * @var string
	 */
	public $originName; 

	/**
	 * 临时文件名
	 * @var string
	 */
	public $tempName; 

	/**
	 * 上传目录
	 * @var string
	 */
	public $upload_path;

	/**
	* 文件路径
	* @var string
	*/
	public $file_path;

	/**
	 * 上传后文件名称
	 * @var string
	 */
	public $file_name; 

	/**
	 * 文件大小
	 * @var float
	 */
	public $file_size; 

	/**
	 * 文件类型
	 * @var string
	 */
	public $file_type; 

	/**
	 * 文件后缀名
	 * @var string
	 */
	public $file_extension;

	/**
	 * 生成随机名称
	 */
	public function getRandName($len = 10) 
	{
		$ret = Common::generateStr($len);

		if($this->checkFileExist($ret)){
			return $this->getRandName($len);
		}
		return $ret;
	}

	/**
	* 检查文件是否已存在
	*/
	public function checkFileExist($name)
	{
		if(is_file($this->file_path.'/'.$name.$this->file_extension)){
			return true;
		}

		return false;
	}

	/**
	 * 检查上传目录
	 */
	public function checkFilePath() 
	{
		if (!is_dir($this->file_path)) {
			mkdir($this->file_path, 0777, true);
            chmod($this->file_path, 0777);
		}
	}

	/**
	 * 检查文件大小
	 */
	public function checkFileSize()	
	{
		if ($this->file_size > $this->maxSize*1024*1024) 
			return false;
		return true;
	}

	/**
	 * 检查文件类型
	 */
	public function	checkFileType() 
	{
		if (in_array($this->file_type, $this->allowType)) 
			return true;
		else 
			return false;
	}

	/**
	 * 上传文件到上传目录
	 */
	public function copyFileToPath() 
	{
		$file = $this->file_path.'/'.$this->file_name;
		
		//上传文件
		if (!move_uploaded_file($this->tempName, $file))
			return false;

		return true;
	}
}
