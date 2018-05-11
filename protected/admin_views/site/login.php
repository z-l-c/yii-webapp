<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv=Content-Type content="text/html; charset=utf-8">
		<meta http-equiv=Content-Language content=zh-CN>
		<title><?php echo Yii::app()->params['title'];?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/css/login.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl;?>/js/jquery-easyui/themes/bootstrap/easyui.css" />
		<link rel="shortcut icon" href="" />
	</head>

	<body>
		<div id="container">
			<?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => "login_form",
                    'enableClientValidation' => true,
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
            ?>
			<div class="register">
				<div class="register_title">登录</div>
				<div class="input_row">
					<input id="AdminUser_loginname" type="text" class="form-control username" name="AdminUser[loginname]" placeholder="账号" value="<?php echo $model->loginname;?>" />
				</div>
				<div class="input_row">
					<input id="AdminUser_password" type="password" class="form-control password" name="AdminUser[password]" placeholder="密码" />
				</div>

				<div class="register_bottom">
					<label><input type="checkbox" name="isAuto" value="on" />记住我</label>
				</div>
				<div class="input_row">
					<button type="submit" class="btn_login">登&nbsp;&nbsp;录</button>
				</div>
			</div>
			<?php
                $this->endWidget();
            ?>
		</div>

		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/jquery.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->baseUrl;?>/js/jquery-easyui/jquery.easyui.min.js"></script>
		<script type="text/javascript">
			var window_width = $(window).width();
			var window_height = $(window).height();
			$('body').css('width', window_width + 'px');
			$('body').css('height', window_height + 'px');

		<?php if (is_string($result)) {?>
			$.messager.show({
				title: '提示',
				msg: '<?php echo $result;?>',
				showType: 'fade',
				style:{
					right:'',
					bottom:'',
					top:document.body.scrollTop+document.documentElement.scrollTop
				}
			});
		<?php }?>

			$("#login_form").submit(function(){
				var loginname = $.trim($('#AdminUser_loginname').val());
				var password = $.trim($('#AdminUser_password').val());
				if (loginname == '') {
					
					$.messager.show({
						title: '提示',
						msg: '请输入账号',
						showType: 'fade',
						style:{
							right:'',
							bottom:'',
							top:document.body.scrollTop+document.documentElement.scrollTop
						}
					});
				
					return false;
				}
				if (password == '') {
					$.messager.show({
						title: '提示',
						msg: '请输入密码',
						showType: 'fade',
						style:{
							right:'',
							bottom:'',
							top:document.body.scrollTop+document.documentElement.scrollTop
						}
					});
					
					return false;
				}
			});
		</script>
	</body>
</html>	


