<?php
/**
* 图片上传
*/
class ImageUploaderHelper extends FileUploaderHelper
{	
	const E_SUCCESS = 1; //成功
	const E_TYPEERR = 100; //文件类型错误
	const E_SIZEERR = 101; //文件大小错误
	const E_FAILURE = -1;  //失败

	public $isThumbnail; //是否生成缩略图
	public $thumbRate; //压缩比例

	public $return = array();

	function __construct($maxSize, $isThumbnail, $thumbRate) 
	{
		$this->maxSize = $maxSize > 0 ? $maxSize : 2;  //默认2M
		$this->isThumbnail = $isThumbnail == true ? true : false; //默认false
		$this->thumbRate = $thumbRate > 0 ? $thumbRate : 0.1;   //默认比例0.1
	}

	/**
	 * 重写父类方法 参数：单个$_FILE 输出：文件访问路径
	 * @param  File $file 上传文件
	 */
	public function fileUpload($file) 
	{
		$this->allowType = array('gif', 'image/jpeg', 'image/bmp', 'image/png');
		$this->upload_path = '/images/uploads/'.date('Ym');
		$this->file_path = Yii::app()->basePath.'/../admin'.$this->upload_path;
		//创建上传目录
		$this->checkFilePath();

		$this->originName = $file['name'];
		$this->tempName = $file['tmp_name'];
		$this->file_size = $file['size'];
		$this->file_type = $file['type'];
		$tmp = explode('.', $this->originName);
		$this->file_extension = $tmp[1];

		//检查文件类型是否为图片
		if (!$this->checkFileType()) {
			$this->return['result'] = self::E_TYPEERR;
			$this->return['error'] = "文件必须为图片";
			return $this->return;
		}
		
		//检查图片大小
		if (!$this->checkFileSize()) {
			$this->return['result'] = self::E_SIZEERR;
			$this->return['error'] = "图片大小不能大于".$this->maxSize."M (1M=1024K)";
			return $this->return;
		}
		
		$this->file_name = $this->getRandName(16).'.'.$this->file_extension;
		if (!$this->copyFileToPath()) {
			$this->return['result'] = self::E_FAILURE;
			$this->return['error'] = "图片上传失败";
			return $this->return;
		}

		$this->return['result'] = self::E_SUCCESS;
		$this->return['path'] = $this->upload_path.'/'.$this->file_name;

		if ($this->isThumbnail) {
			$this->imageThumbnail();
		}

		return $this->return;
	}

	/**
	 * 图片生成缩略图
	 */
	public function imageThumbnail() 
	{
		$originFile = $this->file_path.'/'.$this->file_name;

		$piece = explode('.', $this->file_name);
		$thumbFile = $this->file_path.'/'.$piece[0].'_thumbnail.'.$piece[1];

		list($width_orig, $height_orig) = getimagesize($originFile);
		$width_thumbnail = $width_orig * $this->thumbRate;
		$height_thumbnail = $height_orig * $this->thumbRate;

		$imageThumb = Yii::app()->imagehelper->load($originFile);
		$imageThumb->resize($width_thumbnail, $height_thumbnail);
		$imageThumb->save($thumbFile);
	}

}
