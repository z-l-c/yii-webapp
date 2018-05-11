安装：
将kindeditor文件夹放入application的extensions文件中即可

------------------------------------------------------------------------------

使用方法：

在view文件中使用，id参数为文本输入框元素的id，paramOptions为设定文本编辑器有哪些功能

$this->widget('application.extensions.kindeditor.KEditorWidget', array('id'=>'Resthome_content','paramOptions'=>"{items:['fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic','underline', 'removeformat', '|', 'justifyleft', 'justifycenter','justifyright', 'insertorderedlist','insertunorderedlist']}"));