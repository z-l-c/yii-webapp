<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
// CrudCode
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $dataProvider CActiveDataProvider */

?>

		<div class="main_right_content">
		<div class="main_right_content_title">
			<div class="main_right_content_title_font"></div>
			<div class="main_right_content_content_block_action_add">
				<div class="main_right_content_content_block_action_font">
					<a class="action_add"
						href="<?php echo '<?php' ?> echo Yii::app()->createUrl("<?php echo $this->controller.'/create'; ?>");?>">增加</a>
				</div>
			</div>
		</div>
		<div class="main_right_content_content">
			<table cellspacing=1 border="0">
				<tr class="main_right_content_content_title">
				<?php
				foreach($this->tableSchema->columns as $column)
				{
					if($column->autoIncrement)
						continue;
				?>
					<td width="15%"><?php echo $column->comment; ?></td>
				<?php }?>
					<td width="25%">操作</td>
				</tr>
					<?php echo "<?php"; ?>
					$i=0;
					foreach ( $items as $item ) {
					$edit_url = Yii::app()->createUrl('<?php echo $this->controller;?>/update',array('id'=>$item->id,'page'=>$_REQUEST['page']));
					?>
						<tr class="main_right_content_content_body">
						<?php 
						foreach($this->tableSchema->columns as $column)
						{
							if($column->autoIncrement)
								continue;
						?>
					<td><?php echo "<?php"; ?> echo $item-><?php echo $column->name;?> ?></td>
					<?php }?>
					<td>
						<div class="main_right_content_content_block_action">
							<div class="main_right_content_content_block_action_edit" style="margin-left: 50px;">
								<div class="main_right_content_content_block_action_font">
									<a class="action_edit"
										href="<?php echo "<?php"; ?> echo $edit_url?>">编辑</a>
								</div>
							</div>
							<div class="main_right_content_content_block_action_del">
								<div class="main_right_content_content_block_action_font">
									<a class="action_del">删除</a> <input id="display_id"
										type="hidden" value="<?php echo "<?php"; ?> echo $item->id;?>" />
								</div>
							</div>
						</div>
					</td>
				</tr>
				<?php echo '<?php } ?>' ?>
					</table>
		</div>
	</div>
	
		<input id="del_baseurl" type="hidden" value="<?php echo "<?php"; ?> echo Yii::app()->createUrl("<?php echo $this->controller ."/delete"?>",array('page'=>$pages->currentPage +1));?>" />
	<div class="main_footer <?php echo "<?php"; ?> if($pages->pageCount <= 1) echo 'main_footer_hidden';?>">
		<div class="main_footer_page">
			<?php echo "<?php"; ?>
			$this->widget ( 'CLinkPager', array (
					'header' => '',
					'cssFile' => 'false',
					'firstPageLabel' => '首页',
					'lastPageLabel' => '末页',
					'prevPageLabel' => '上一页',
					'nextPageLabel' => '下一页',
					'maxButtonCount' => 6,
					'pages' => $pages
			) );
			?>
			<div class="main_footer_page_search">
				<div class="main_footer_page_search_box">
					<input id="pages" type="text" />
				</div>
				<div class="main_footer_page_search_font">GO</div>
				<input id="display_total" type="hidden" value="<?php echo "<?php"; ?> echo $pages->pageCount; ?>" />
				<input id="page_baseurl" type="hidden" value="<?php echo "<?php"; ?> echo Yii::app()->createUrl('<?php echo $this->controller ."/index"?>');?>" />
			</div>
		</div>
	</div>
</div>
	
