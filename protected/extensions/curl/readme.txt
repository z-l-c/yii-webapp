安装：
将curl文件夹放入application的extensions文件中即可

------------------------------------------------------------------------------

使用方法：

配置：
在application的main config的components中添加以下配置
'curl'=>array(
            'class'=>'application.extensions.curl.Curl',
        ),

调用方法()：
$return = Yii::app()->curl->get(url, param_array);
$return = Yii::app()->curl->post(url, data_array);