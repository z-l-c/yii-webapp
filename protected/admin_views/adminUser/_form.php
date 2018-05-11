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
					<span class="name required">登录名：</span>
					<?php if($model->isNewRecord){?>
					<input id="AdminUser_loginname" type="text" class="easyui-textbox" data-options="prompt:'请输入登录名'" value="<?php echo $model->loginname;?>" name="AdminUser[loginname]"></input>
					<?php }else{ ?>
					<input id="AdminUser_loginname" type="text" disabled="disabled" class="easyui-textbox" value="<?php echo $model->loginname;?>" />
					<?php } ?>
				</div>
				<div class="edit_row">
					<span class="name required">昵称：</span>
					<input id="AdminUser_nickname" type="text" class="easyui-textbox" data-options="prompt:'请输入昵称'" value="<?php echo $model->nickname;?>" name="AdminUser[nickname]"></input>
				</div>
				<div class="edit_row">
					<span class="name">密码：</span>
					<input id="AdminUser_password" type="text" class="easyui-passwordbox" data-options="prompt:'请输入密码'" value="" name="AdminUser[password]"></input>
				</div>

				<div class="edit_row">
					<span class="name">确认密码：</span>
					<input id="confirm_password" type="text" class="easyui-passwordbox" data-options="prompt:'请再次输入密码'" value="" name="AdminUser[confirm_password]"></input>
				</div>
			</div>
		</div>

		<div title="角色" style="padding: 10px;">
			<div class="tabs-info">
				<div class="op_row">
					<div class="edit_row child">
						<div class="edit_checkbox">
							<label for="无">无</label>
							<input id="无" type="radio" checked="checked" value="" name="right_names[]" />
						</div>
					</div>
				<?php if(count($role_array)>0){
				foreach ($role_array as $value) {?>
					<div class="edit_row child">
						<div class="edit_checkbox">
							<label for="<?php echo $value;?>" class="label-tool"><?php echo $value;?></label>
						<?php if (is_array($has_auths) && in_array($value, $has_auths)) {?>
							<input id="<?php echo $value;?>" type="radio" checked="checked" value="<?php echo $value;?>" name="right_names[]" />
						<?php } else {?>
							<input id="<?php echo $value;?>" type="radio" value="<?php echo $value;?>" name="right_names[]" />
						<?php }?>
						</div>
					</div>
				<?php }} ?>
				</div>
			</div>
		</div>

		<div title="附加权限" style="padding: 10px;">
			<div class="tabs-info">
		<?php 
		if (is_array($operation)) {
			$has_auths = is_array($has_auths) ? $has_auths : array();
			foreach ($operation as $parent => $child) {
				if($parent){
		?>
			<div class="op_row">
				<div class="edit_row parent">
					<div class="edit_checkbox">
						<label for="<?php echo $parent;?>"><?php echo $parent;?></label>
					<?php if (in_array($parent, $has_auths)) {?>
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
					<?php if (in_array($value, $has_auths)) {?>
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

	$('.label-tool').each(function(){
		var role_name = $(this).text();
		$(this).tooltip({
			content: $('<div></div>'),
			onUpdate: function(cc){
				cc.panel({
					width: 600,
					height: 'auto',
					title: '拥有权限',
					href: '<?php echo Yii::app()->createUrl('role/hasAuths')?>',
					queryParams: {role:role_name},
				})
			}
		});	
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

	$("#edit_form").submit(function(){
		var loginname = $.trim($('#AdminUser_loginname').textbox('getValue'));
		var nickname = $.trim($('#AdminUser_nickname').textbox('getValue'));
		var password = $.trim($('#AdminUser_password').passwordbox('getValue'));
		var confirm_password = $.trim($('#confirm_password').passwordbox('getValue'));

		if (loginname.length < 4 || loginname.length > 10) {
			showTip('登录名长度4-10个字符');
			return false;
		}else{
			var reg = new RegExp(/^[a-zA-Z][0-9A-Za-z_]*$/);
		    if (!reg.test(loginname)) {
		    	showTip('登录名只允许字母开头，只包含字母、数字、下划线');
		        return false;
		    }
		}
		if (nickname == '') {
			showTip('请输入昵称');
			return false;
		}
		if (confirm_password != password) {
			showTip('两次密码不一致');
			return false;
		}
	});
})
</script>
