<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
<?php 
	header("Cache-control: private;Content-type:text/html;charset=utf-8");

	//CSS
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js/jquery-easyui/themes/bootstrap/easyui.css?t='.time());
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js/jquery-easyui/themes/bootstrap/combo.css?t='.time());	
	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/js/jquery-easyui/themes/icon.css?t='.time());

	Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl.'/css/class.css');

	//JS
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-easyui/jquery.easyui.min.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-easyui/locale/easyui-lang-zh_CN.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-easyui/exts/columns-ext.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-easyui/exts/datagrid-groupview.js');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-easyui/exts/datagrid-detailview.js');

	Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl.'/js/admin.js');

	$all_menus = MenuItem::getValideMenus();
	$all_menus = is_array($all_menus) ? $all_menus : array();
?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="language" content="en" />
		<meta name="keywords" content="<?php echo Yii::app()->params['keywords'];?>" />
		<link rel="shortcut icon" href="" />
		<title><?php echo CHtml::encode(Yii::app()->name.' - '.$this->pageTitle);?></title>
	</head>

	<body>
		<div id="ibody">
			<div class="left_bar">
				<div class="logo">
					<img src="<?php echo $this->imgUrl('logo.png');?>">
				</div>

				<div class="left_menu">
					<?php if(count($all_menus)>0){
							foreach ($all_menus as $menu) {$count++;?>
					<div class="accordion <?php echo $this->breadcrumbs['menu']->name==$menu->name?'click':'';?>">
						<div class="parent_menu">
							<img src='<?php echo Yii::app()->baseUrl.$menu->icon;?>' />
							<?php echo $menu->name;?>
						</div>
						<img src='<?php echo $this->imgUrl('forward.png');?>' class="sub_menu_arrow" />
						
						<div style="display: none;">
							<div class="tooltips">
							<?php if (is_array($menu->sub_menus) && count($menu->sub_menus) > 0) {
									foreach ($menu->sub_menus as $sub_menu) {?>
									
									<a href="<?php echo $sub_menu->url;?>" class="<?php echo $this->breadcrumbs['sub_menu']->name==$sub_menu->name?'click':'';?>">

										<?php echo $sub_menu->name;?>
									</a>
							<?php }}?>
							</div>
						</div>

					</div>
					<?php }}?>
				</div>
			</div>

			<div class="right_container">
				<div class="right_header">
					<div class="right_header_left">
						<a href="<?php echo Yii::app()->createUrl('site/index');?>" class="dashboard easyui-linkbutton" data-options="iconCls:'icon-dashboard',plain:true">Dashboard</a>
					</div>

					<div class="right_header_right">
						<a class="user_hello">您好，<?php echo Yii::app()->user->nickname;?></a>
						
						<div id="downMenu" style="width: 140px;">
							<div data-options="iconCls:'icon-key',href:'<?php echo Yii::app()->createUrl('site/resetPwd');?>'">&nbsp;修改密码</div>
							<div class="menu-sep"></div>
							<div data-options="iconCls:'icon-switch',href:'<?php echo Yii::app()->createUrl('site/logout');?>'">&nbsp;退出系统</div>
						</div>
					</div>
					
				</div>
				<div class="top_guidepost">
					<ul>
						<li><img src="<?php echo $this->imgUrl('spot.png'); ?>" /></li>
						<li class="first">
						<?php if (!$this->breadcrumbs) {
							echo Yii::app()->name;
						 } else {?>
							<a href="<?php echo Yii::app()->createUrl('site/index');?>">
								<?php echo Yii::app()->name; ?>
							</a>
						<?php }?>
						</li>
					<?php $i = 0; 
					foreach ($this->breadcrumbs as $sub_menu) {?>
						<li>&gt;</li>
						<li class="<?php echo $i == count($this->breadcrumbs) - 1 ? ' last' : '';?>">
						<?php if ($sub_menu->url == '#') {
                            echo $sub_menu->name;
                        } else {?>
							<a href="<?php echo $sub_menu->url;?>">
								<?php echo $sub_menu->name;?>
							</a>
						<?php }?>
						</li>
					<?php $i++; }?>
					</ul>
					
					<?php if($this->breadcrumbs['last_menu']){?>
					<div class="back_btn">
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-back'" onclick="javascript:goBack()">返回</a>
					</div>
					<?php }?>
				</div>
				
				<div id="container">
					<?php echo $content;?>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$('.accordion').tooltip({
				position: 'right',
			    content: function(){
					return $(this).find('.tooltips');
				},
				onShow: function(){
					$(this).tooltip('tip').css({
						left: $('.left_bar').width(),
						backgroundColor: '#1a1a1a',
						borderColor: '#353535',
						boxShadow: '1px 1px 3px #292929'
					});
					var item = $(this);
					item.tooltip('tip').mouseenter(function(){
			            item.tooltip('show');
		            }).mouseleave(function(){
			            item.tooltip('hide');
		            });
				},
			});
			$('.user_hello').menubutton({
				menu: '#downMenu',
				iconCls: 'icon-user-white',
				menuAlign: 'right'
			});
		</script>
	</body>
</html>
