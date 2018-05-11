<?php 
$this->breadcrumbs = array(
	'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"), 
	'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => "#"),
);
?>

<div class="div_table">
	<?php
		$this->widget('GridWidget',array('config'=>$config));
	?>
</div>

<div id="detail_win">
	
	<table id="detail_table"></table>

</div>

<script type="text/javascript">
	$('#detail_table').propertygrid({
		width: '100%',
		height: '100%',
		columns: [[
			{field:'name', title:'项', width: 1, align: 'center'},
			{field:'value', title:'内容', width: 3, align: 'center'},
		]],

	});

	$('#detail_win').window({
		title: '日志详情',
		width: 600,
		height: 400,
		closed: true,
		minimizable: false,
		maximizable: false,
		maximized: true,
	});

	var labels = eval('('+'<?php echo json_encode($labels);?>'+')');

	$(function(){
		$('#<?php echo $config->tableName;?>').datagrid({
			url: '<?php echo Yii::app()->createUrl('log/data');?>',
			rownumbers: true,
            singleSelect: true,
			striped: true,
			nowrap: false,
			width: '100%',
			height: table_grid_height + 60,
			pagination: true,
			pageSize: <?php echo $rows?$rows:50;?>,
			queryParams: {cache_key:'<?php echo $config->cacheKey;?>'},
			onSortColumn: function(sort, order){},
			onLoadSuccess: function() {
				$('.view_detail').click(function(event) {
					var rowNum = $('#detail_table').propertygrid('getRows').length;

					setTimeout(function(){
						var row = $('#<?php echo $config->tableName;?>').datagrid('getSelected');

						var rowIndex = 0;
						for (var i in labels) {
							var value;
							if(row[i] instanceof Object){
								value = printObject(row[i]);
							}else{
								value = row[i];
							}

							var rows = {
								name: labels[i],
								value: value,
							};

							if(rowNum > 0){
								$('#detail_table').propertygrid('updateRow',{
									index: rowIndex,
									row: rows
								});
								rowIndex++;
							}else{
								$('#detail_table').propertygrid('appendRow', rows);
							}
						}

						$('#detail_win').window('open');
						
					},500);
				});
			}
		}).datagrid('columnMoving');
	});
</script>

