<?php
$form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation' => true,
    'htmlOptions' => array(
        'id' => "edit_form",
        'enctype' => "multipart/form-data",
    ),
));
?>

<div class="edit_body">
	<div class="edit_row">
		<span class="name">名称：</span>
	
		<input id="AuthRoleForm_name" type="text" class="easyui-textbox" data-options="prompt:'请输入操作名', height:32, required:true" value="<?php echo $model->name;?>" name="AuthRoleForm[name]"></input>
	
	</div>

	<?php if($menu_model){?>
	<div class="edit_row">
		<span class="name">是否为上级：</span>
		<?php if ($model->name) {?>
			<!-- <span style="width: 138px; display: inline-block;"><?php echo $menu_model->is_parent==1?"是":"否";?></span> -->
			<select class="AuthRoleForm_is_parent" disabled="disabled">
				<option value="0" <?php echo $menu_model->is_parent!=1?"selected":"";?>>否</option>
				<option value="1" <?php echo $menu_model->is_parent==1?"selected":"";?>>是</option>
			</select>
			<input id="AuthRoleForm_is_parent" type="hidden" value="<?php echo $menu_model->is_parent;?>" name="AuthRoleForm[is_parent]"></input>
		<?php } else {?>
			<select id="AuthRoleForm_is_parent" class="AuthRoleForm_is_parent" name="AuthRoleForm[is_parent]">
				<option value="0" <?php echo $menu_model->is_parent!=1?"selected":"";?>>否</option>
				<option value="1" <?php echo $menu_model->is_parent==1?"selected":"";?>>是</option>
			</select>
		<?php } ?>
	</div>
	<div class="edit_row" id="show_parent" style="<?php echo $menu_model->is_parent==1?"display:none":""?>">
		<span class="name">上级：</span>
		<select id="AuthRoleForm_parent" class="easyui-combobox" name="AuthRoleForm[parent]" data-options="editable:false, height:32, required:true">
			<option value=""></option>
			<?php if($parent_menu) {
					foreach ($parent_menu as $value) {
			?>
				<option value="<?php echo $value->name; ?>" <?php echo $menu_model->parent==$value->name?"selected":"";?>><?php echo $value->name;?></option>
			<?php }}?>

		</select>
	</div>
	<div class="edit_row" id="show_url" style="<?php echo $menu_model->is_parent==1?"display:none":""?>">
		<span class="name">菜单链接：</span>
		<input id="AuthRoleForm_url" type="text" class="easyui-textbox" value="<?php echo $menu_model->url;?>" name="AuthRoleForm[url]" data-options="prompt:'请输入菜单链接', height:32"></input>
	</div>
	<div class="edit_row" id="show_icon" style="<?php echo $menu_model->is_parent!=1?"display:none":""?>">
		<span class="name">菜单图标：</span>
		<?php if($menu_model->icon){ ?>
		<span class="has_val"><img src="<?php echo $menu_model->icon;?>" width="20" height="20" /></span>
		<?php } ?>
		<div id="uploader" class="webuploader">
		    <!--用来存放item-->
		    <div id="fileList" class="uploader-list"></div>
		    <div id="filePicker">选择图片</div>
		</div>
		<?php $this->widget('application.extensions.webuploader.WebUploadWidget', array('id'=>'filePicker','upload_list'=>'fileList','upload_manager_url'=>$this->hostUrl().Yii::app()->createUrl('operation/uploads',array('maxSize'=>0.1)),'fileType'=>'image','fileSize'=>0.1,'fileNum'=>1,'thumbWidth'=>25,'thumbHeight'=>25,'model'=>'AuthRoleForm','name'=>'icon')); ?>
	</div>
	<?php }?>

	<div class="edit_row">
		<span class="name">描述：</span>
		<input id="AuthRoleForm_description" type="text" class="easyui-textbox" value="<?php echo $model->description;?>" name="AuthRoleForm[description]" data-options="prompt:'请输入描述', height:32"></input>
	</div>

	<input id="create_menu" type="hidden" name="create_menu" value="0" />

	<div class="edit_btn_row">
		<?php if(!$model->name){?>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok', width:120" onclick="javascript:formSubmit(1);">提交并建菜单</a>
		<?php }?>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok', width:100" onclick="javascript:formSubmit(0);">提交</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-cancel', width:100" onclick="javascript:goBack();">取消</a>
	</div>
</div>

<?php $this->endWidget();?>	

<script type="text/javascript">
<?php if (is_string($result)) {?>
	$.messager.alert('提交失败','<?php echo $result;?>','error');
<?php } elseif ($result) {?>
	$.messager.alert('提交成功','提交成功','info');
	goBack();
<?php }?>

function goBack() 
{
	window.location.href = "<?php echo $backUrl;?>";
}

function formSubmit(val)
{
	$('#create_menu').val(val);
	$('#edit_form').submit();
}

$(function(){
	$('.AuthRoleForm_is_parent').combobox({
		editable: false, 
		height: 32,
		onChange: function(v){
			$('#AuthRoleForm_parent').val('');
			$('#AuthRoleForm_url').val('');
			$('#AuthRoleForm_icon').val('');
			if(v == 1){
				$('#show_parent').hide();
				$('#show_url').hide();
				$('#show_icon').show();
			}else{
				$('#show_parent').show();
				$('#show_url').show();
				$('#show_icon').hide();
			}
		}
	});
	$("#edit_form").submit(function(){
		var name = $.trim($('#AuthRoleForm_name').textbox('getValue'));
		if (name == '') {
			showTip('请输入名称');
			return false;
		}
	});

})
</script>

