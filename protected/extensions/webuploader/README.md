目录说明
========================

```bash
├── Uploader.swf                      # SWF文件，当使用Flash运行时需要引入。
├
├── webuploader.js                    # 完全版本。
├── webuploader.min.js                # min版本
├
├── webuploader.flashonly.js          # 只有Flash实现的版本。
├── webuploader.flashonly.min.js      # min版本
├
├── webuploader.html5only.js          # 只有Html5实现的版本。
├── webuploader.html5only.min.js      # min版本
├
├── webuploader.noimage.js            # 去除图片处理的版本，包括HTML5和FLASH.
├── webuploader.noimage.min.js        # min版本
├
├── webuploader.custom.js             # 自定义打包方案，请查看 Gruntfile.js，满足移动端使用。
└── webuploader.custom.min.js         # min版本
```

## 示例

请把整个 Git 包下载下来放在 php 服务器下，因为默认提供的文件接受是用 php 编写的，打开 examples 页面便能查看示例效果。


## 简单使用方法
传递参数可参看 WebUploadWidget.php

 <div id="uploader" class="webuploader">
      <!--用来存放item-->
      <div id="fileList" class="uploader-list"></div>
      <div id="filePicker">选择图片</div>
 </div>
 <?php $this->widget('application.extensions.webuploader.WebUploadWidget',array(
     'id'=>'filePicker',  //按钮元素id
     'upload_list'=>'fileList',  //回显缩略图列表元素id
     'upload_manager_url'=>$this->hostUrl().Yii::app()->createUrl('operation/uploads',array('maxSize'=>0.1,'isThumb'=>true,'thumbrate'=>0.1)), 
     'model'=>'Model name',  //数据model名称  
     'name'=>'column name'  //字段名称
 )); ?>