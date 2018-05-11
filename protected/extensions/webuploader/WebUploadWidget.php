<?php

/**
 * 图片上传组件
 */

class WebUploadWidget extends CInputWidget
{
    public $id;   //文件上传元素的id
    public $isAuto = true; //自动上传
    public $upload_list; //上传文件展示列表元素id
    public $upload_manager_url; //上传文件处理脚本url 如：http://xxxxx
    public $fileType; //上传类型 空值：普通文件，image：图片
    public $fileExt = 'jpg,jpeg,png'; //允许的上传类型
    public $fileSize = 2; //文件大小 单位：M
    public $fileNum = 0;  //文件数 0:不限制
    public $thumbWidth = 100;  //回显图片宽
    public $thumbHeight = 100; //回显图片高
    public $model; //模型名称
    public $name; //字段名称

    /**
     * 初始化组件.
     */
    public function init()
    {
         // 阻止从命令行执行.
         if (Yii::app() instanceof CConsoleApplication)
              return;

         /** @var CClientScript $cs */
         $cs = Yii::app()->getClientScript(); 
         $cs->registerCssFile($this->assetsUrl.'/webuploader.css', 'screen');
         $cs->registerScriptFile($this->assetsUrl.'/webuploader.js', CClientScript::POS_END);
    }

    /**
     * 运行组件.
     */
    public function run()
    {
        $script = $this->getScript();
        /** @var CClientScript $cs */
        $cs = Yii::app()->getClientScript();
        $cs->registerScript($this->id, $script, CClientScript::POS_END);
        
    }

    public function getAssetsUrl()
    {
        $assetsPath = Yii::getPathOfAlias('ext.webuploader.assets');
        $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, YII_DEBUG);
        return $assetsUrl;
    }

    /**
    * 获取文件限制
    */
    public function getAllow()
    {
        $accept = "";
        
        if($this->fileType == 'image'){
            // 只允许选择图片文件
            $accept = "
                accept: {
                    title: 'Images',
                    extensions: '".$this->fileExt."',
                    mimeTypes: 'image/*'
                }";
        }

        return $accept;
    }

    /**
    * 组件所需的脚本
    */
    public function getScript()
    {
        $script = "
            var uploader = WebUploader.create({
                // 选完文件后，是否自动上传。
                auto: true,
                // swf文件路径
                swf: '".$this->assetsUrl."/Uploader.swf',
                // 文件接收服务端。
                server: '".$this->upload_manager_url."',
                
                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#".$this->id."', 

                //允许重复上传
                duplicate: true,

                ".$this->getAllow()."
            });

            uploader.on( 'fileQueued', function( file ) {
                var \$list = $('#".$this->upload_list."');
                if(\$list.find('.file-item').size() >= ".$this->fileNum." && ".$this->fileNum ." > 0) {
                   uploader.cancelFile(file.id);

                   if(typeof $.messager.alert == 'function'){
                        $.messager.alert('上传失败', '上传文件超过限制数', 'error');
                    }else{
                        alert('上传文件超过限制数".$this->fileNum."');
                    }
                   return false;
                }

                if (file.size > ".($this->fileSize*1024*1024).") {
                    uploader.cancelFile(file.id);

                    if(typeof $.messager.alert == 'function'){
                        $.messager.alert('上传失败', '上传文件大小不能超过".$this->fileSize."M', 'error');
                    }else{
                        alert('上传文件大小不能超过".$this->fileSize."M');
                    }
            
                    return false;
                }

                var \$li = $(
                        '<div id=\"' + file.id + '\" class=\"file-item thumbnail\">' +
                            '<img>' + 
                        '</div>'
                        ),
                \$img = \$li.find('img');

                \$list.append( \$li );

                // 创建缩略图
                // thumbnailWidth x thumbnailHeight 为 100 x 100
                uploader.makeThumb( file, function( error, src ) {
                    if ( error ) {
                        \$img.replaceWith('<span>不能预览</span>');
                        return;
                    }

                    \$img.attr( 'src', src );
                    \$img.next(':input').val(src);
                }, ".$this->thumbWidth.", ".$this->thumbHeight." );
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on( 'uploadSuccess', function( file, response ) {
                if (response['status'] == 0) {
                    if(typeof $.messager.alert == 'function'){
                        $.messager.alert('上传失败', response['message'], 'error');
                    }else{
                        alert(response['message']);
                    }
                    $('#' + file.id).remove();
                    return false;
                }

                $('#' + file.id).addClass('upload-state-done');
                $('#' + file.id).append('<input type=\"hidden\" value=\"' + response['path'] + '\" name=\"".$this->model."[".$this->name."][]\"></input>');

                $('#' + file.id).hover(function(){
                    $(this).append(
                        '<div class=\"thumbnail_box\">' + 
                            '<i class=\"icon icon-remove-circle\"></i>' + 
                        '</div>'
                    );
                    $('#' + file.id + ' .thumbnail_box').on('click', function(){
                        var thumbnail_box = $(this);
                        if(typeof $.messager.confirm == 'function'){
                            $.messager.confirm('删除文件', '确认删除该文件？', function(r){
                                if(r){
                                    $('#' + file.id).remove();
                                }
                            });
                        }else{
                            if(confirm('确认删除文件？')){
                               $('#' + file.id).remove(); 
                            }
                        }
                    });
                }, function(){
                    $(this).children('.thumbnail_box').remove();
                });

            });

            // 文件上传失败，显示上传出错。
            uploader.on( 'uploadError', function( file ) {
                var \$li = $( '#'+file.id ),
                    \$error = \$li.find('div.error');

                // 避免重复创建
                if ( !\$error.length ) {
                    \$error = $('<div class=\"error\"></div>').appendTo( \$li );
                }
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on( 'uploadComplete', function( file ) { 
                //TODO
            });

            ";

        return $script;
    }
}

?>