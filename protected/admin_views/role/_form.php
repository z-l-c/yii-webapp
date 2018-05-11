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

	<div id="tabs">
		<div title="信息" style="padding: 10px;">
			<div class="tabs-info">
				<div class="edit_row">
					<span class="name required">角色名：</span>
					<input id="AuthRoleForm_name" type="text" class="easyui-textbox" data-options="prompt:'请输入角色名'" value="<?php echo $model->name;?>" name="AuthRoleForm[name]"></input>
				</div>
				<div class="edit_row">
					<span class="name">描述：</span>
					<input id="AuthRoleForm_description" type="text" class="easyui-textbox" data-options="prompt:'请输入描述'" value="<?php echo $model->description;?>" name="AuthRoleForm[description]"></input>
				</div>
			</div>
		</div>

		<div title="权限" style="padding: 10px;">
			<div class="tabs-info">
		<?php 
		if (is_array($operation)) {
			$has_operation = is_array($has_operation) ? $has_operation : array();
			foreach ($operation as $parent => $child) {
				if($parent){
		?>
			<div class="op_row">
				<div class="edit_row parent">
					<div class="edit_checkbox">
						<label for="<?php echo $parent;?>"><?php echo $parent;?></label>
					<?php if (in_array($parent, $has_operation)) {?>
						<input id="<?php echo $parent;?>" type="checkbox" checked="checked" value="<?php echo $parent;?>" name="right_names[]"></input>
					<?php } else {?>
						<input id="<?php echo $parent;?>" type="checkbox" value="<?php echo $parent;?>" name="right_names[]"></input>
					<?php }?>
					</div>
				</div>
		<?php 	
				} else {
					echo '<div class="op_row"><div class="edit_row parent"></div>';
				}

				if(count($child) > 0){
					foreach ($child as $value) {
		?>
				<div class="edit_row child">
					<div class="edit_checkbox">
						<label for="<?php echo $value;?>"><?php echo $value;?></label>
					<?php if (in_array($value, $has_operation)) {?>
						<input id="<?php echo $value;?>" type="checkbox" checked="checked" value="<?php echo $value;?>" name="right_names[]"></input>
					<?php } else {?>
						<input id="<?php echo $value;?>" type="checkbox" value="<?php echo $value;?>" name="right_names[]"></input>
					<?php }?>
					</div>
				</div>
		<?php    
					}
				}
		echo '</div>';
			}
		}
		?>
			</div>
		</div>
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
<?php } elseif ($result == true) {?>
	$.messager.alert('提交成功','提交成功','info');
	goBack();
<?php }?>

function goBack() 
{
	window.location.href = "<?php echo $backUrl;?>";
}

$(function(){
	$('#tabs').tabs({
		width: '95%',
		height: 'auto',
		border: false,
		narrow: true,
		justified: true,
	});

	$('.parent').find('input[type=checkbox]').click(function(){
		var op_row = $(this).parents('.op_row');
		var parent = $(this);
		op_row.find('.child').each(function(){
			if(parent.is(':checked')){
				$(this).find('input[type=checkbox]').prop("checked", "true");
			}else{
				$(this).find('input[type=checkbox]').removeAttr("checked");
			}
		});
	});

	$('.child').find('input[type=checkbox]').click(function(){
		var op_row = $(this).parents('.op_row');
		var length = op_row.find('.child input[type=checkbox]:checked').length;
		if(length > 0){
			op_row.find('.parent').find('input[type=checkbox]').prop("checked", "true");
		}else{
			op_row.find('.parent').find('input[type=checkbox]').removeAttr("checked");
		}
	});

	$('#edit_form').submit(function(){
		var name = $.trim($('#AuthRoleForm_name').textbox('getValue'));
		if (name == '') {
			showTip('请输入角色名');
			return false;
		}
	});
})
</script>
