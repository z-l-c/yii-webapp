<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */

?>
<?php echo "<?php\n"; ?>
	Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl."/themes/css/content.css");
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>



<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>\n"; ?>

<div class="main_right_content_title">
	<div class="main_right_content_title_font">用户管理</div>
	<div class="main_right_content_title_navi">
		<div class="main_right_content_title_navi_root">
			<a href="<?php echo Yii::app()->createUrl("/system/index");?>">用户管理</a>
		</div>
		<div class="main_right_content_title_navi_symbol">></div>
		<div class="main_right_content_title_navi_this">
			<a href="<?php echo Yii::app()->request->baseUrl;?>">用户详细</a>
		</div>
	</div>
</div>

<div class="main_right_content_content">
	<div class="main_right_content_content_top"></div>
		<div class="main_right_content_content_mid">

			<div class="main_right_content_content_form">
				<div class="main_right_content_content_msg <?php if (!empty($msg)) echo 'main_right_content_content_msg_display';?>"><?php echo $msg;?></div>
				
				<?php
			foreach($this->tableSchema->columns as $column)
			{
				if($column->autoIncrement)
					continue;
			?>
				<div class="main_right_content_content_form_block">
					<div class="main_right_content_content_form_block_font"><?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?></div>
					<div class="main_right_content_content_form_block_input">
						<?php echo "<?php echo ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
<!-- 							<span class="required_info">*</span> -->
					</div>
				</div>
			<?php
			}
			?>	
				<div class="main_right_content_content_form_btn">
					<input class="main_right_content_content_form_btn_font"
						type="submit" value="确定" />
				</div>
			</div>
		</div>
	<div class="main_right_content_content_bottom"></div>
</div>
<?php echo "<?php\n"; ?>
	$this->endWidget ();
	?>
