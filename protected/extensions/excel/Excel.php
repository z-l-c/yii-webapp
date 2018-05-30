<?php
/**
 * Excel 
 */
class Excel extends CComponent
{
	/**
	 * excel reader object
	 * @var [type]
	 */
	public $_objReader;
	/**
	 * load excel file
	 * @var [type]
	 */
	public $_readExcel;
	/**
	 * excel writer
	 * @var [type]
	 */
	public $_objWriter;
	/**
	 * excel file gener
	 * @var [type]
	 */
	public $_writeExcel;


	/**
	 * init
	 */
	public function init()
	{
		require_once(__DIR__ . "/PHPExcel/IOFactory.php");
        require_once(__DIR__ . "/PHPExcel.php");
	}

	/**
	 * load an excel file
	 * @param string $filePath [description]
	 */
	public function setReaderObj($filePath)
	{
		$fileType = PHPExcel_IOFactory::identify($filePath); //文件名自动判断文件类型
		$this->_objReader = PHPExcel_IOFactory::createReader($fileType);
		$this->_readExcel = $this->_objReader->load($filePath);
	}

	/**
	 * get data from an excel file of first sheet
	 * start from second row
	 * @param  array $columns columns of the excel file,eq. array('A'=>'name','B'=>'age','C'=>'birthday',...)
	 * @return array eq. array('2'=>array('name'=>'','age'=>'','birthday'=>'',...),...)
	 */
	public function readDataFromFile($columns=array())
	{
		$currentSheet = $this->_readExcel->getSheet(0);
		$allRow = $currentSheet->getHighestRow(); //行数

		$data = array();
		for ($i=2; $i <= $allRow; $i++) { 
			if(is_array($columns)) {
				foreach ($columns as $key => $value) {
					$data[$i][$value] = $currentSheet->getCell("$key$i")->getValue();
				}
			}
		}

		return $data;
	}




	/**
	 * init write object
	 */
	public function setWriteObj()
	{
		$this->_writeExcel = new PHPExcel();
        $this->_objWriter = new PHPExcel_Writer_Excel2007($this->_writeExcel);
	}

	/**
	 * write data into an excel file
	 * @param  string $fileName name of the excel file to write
	 * @param  array  $columns  colums of the excel file,eq. array('A'=>'name','B'=>'age','C'=>'birthday',...)
	 * @param  array  $data     data of the excel file,
	 *         eq. array(array('A'=>'','B'=>'','C'=>'',...),...)
	 */
	public function writeDataToFile($fileName, $columns=array(), $data=array())
	{
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");;
		header('Content-Disposition:attachment;filename="'.$fileName.'.xls"');
		header("Content-Transfer-Encoding:binary");

		if(is_array($columns)) {
			foreach ($columns as $key => $name) {
				$this->_writeExcel->getActiveSheet()->setCellValue($key.'1', $name);
			}
		}

		if(is_array($data) && count($data) > 0) {
			$row = 2;
			foreach ($data as $d) {
				foreach ($d as $key => $value) {
					$this->_writeExcel->getActiveSheet()->setCellValue($key.$row, $value);
				}
				$row++;
			}
		}

		$this->_objWriter->save('php://output');
	}

}