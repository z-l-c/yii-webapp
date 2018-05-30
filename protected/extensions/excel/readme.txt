安装：
将excel文件夹放入application的extensions文件中即可

------------------------------------------------------------------------------

使用方法：

配置：
在application的main config的components中添加以下配置
'excel'=>array(
            'class'=>'application.extensions.excel.Excel',
        ),

导出调用方法()：
$excel = Yii::app()->excel;
$excel->setWriteObj();
$excel->writeDataToFile(filename, array('A'=>'name','B'=>'age','C'=>'mobile'), array(array('A'=>'Li','B'=>27,'C'=>'13234213'),array('A'=>'Wang','B'=>24,'C'=>'1867624213')));


导入调用方法()：
$excel = Yii::app()->excel;
$excel->setReaderObj(filepath);
$data = $excel->readDataFromFile(array('A'=>'name','B'=>'age','C'=>'mobile'));