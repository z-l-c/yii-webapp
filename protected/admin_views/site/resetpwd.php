<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
);

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
		<span class="name required">原密码：</span>
		<input id="origin_password" type="text" class="easyui-passwordbox" data-options="prompt:'请输入原密码'" value="" name="AdminUser[origin_password]"></input>
	
	</div>

	<div class="edit_row">
		<span class="name required">新密码：</span>
		<input id="AdminUser_password" type="text" class="easyui-passwordbox" data-options="prompt:'请输入密码'" value="" name="AdminUser[password]"></input>
	</div>

	<div class="edit_row">
		<span class="name required">确认密码：</span>
		<input id="confirm_password" type="text" class="easyui-passwordbox" data-options="prompt:'请再次输入密码'" value="" name="AdminUser[confirm_password]"></input>
	</div>

	<div class="edit_btn_row">
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-ok', width:100" onclick="javascript:$('#edit_form').submit();">提交</a>
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

$(function(){

	$("#edit_form").submit(function(){
		var origin_password = $.trim($('#origin_password').passwordbox('getValue'));
		var password = $.trim($('#AdminUser_password').passwordbox('getValue'));
		var confirm_password = $.trim($('#confirm_password').passwordbox('getValue'));
		if (origin_password == '') {
			showTip('请输入原密码');
			return false;
		}
		if (password == '') {
			showTip('请输入新密码');
			return false;
		}
		if (confirm_password != password) {
			showTip('两次密码不一致');
			return false;
		}
	});

})
</script>

