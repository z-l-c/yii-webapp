<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
    'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => "#"),
);

$form = $this->beginWidget('CActiveForm', array(
    'htmlOptions' => array(
        'id' => 'search_form',
        'enctype' => 'multipart/form-data',
    ),
));
?>
	<div class="search_body">
		<div class="search_row">
			<span class="name">角色名：</span>
			<input id="search_name" type="text" class="easyui-textbox" data-options="prompt:'请输入角色名', height:28"></input>
		</div>

		<div class="search_btn_row">
			<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="javascript:query();">查询</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo'" onclick="javascript:$('#search_form').form('clear');"></a>
		</div>
	</div>
<?php $this->endWidget();?>
	
<!-- 表格部分 -->
<div class="div_table" >
	<?php
		$columns = $config->getColumns();

		$this->widget('GridWidget',array('config'=>$config));
	?>
</div>


<script type="text/javascript">
	var toolbar = [{
		text:'新增角色',
		iconCls:'icon-add',
		handler:function(){
			window.location.href = '<?php echo Yii::app()->createUrl('role/create');?>';
		}
	},'-',{
		text:'保存排序',
		iconCls:'icon-save',
		handler:function(){
			$.messager.confirm('提示', '确认保存排序?', function(r){
				if(r){
					savePriority();
				}
			});
		}
	},'-',{
		text:'重置排序',
		iconCls:'icon-reload',
		handler:function(){
			$.messager.confirm('提示', '确认重置排序?', function(r){
				if(r){
					savePriority(true);
				}
			});
		}
	}];

	function savePriority(isClear=false) {
		var priority_arr = new Array();
		$('.priority:visible').each(function(){
			var name = $(this).attr('name');
			var priority = $(this).val();
			if(isClear) {
				priority = 100;
			}
			priority_arr.push(new Array(name, priority));
		});
		var priority_json = JSON.stringify(priority_arr);

		$.post("<?php echo Yii::app()->createUrl('role/savePriority');?>", 
		{
			'priority_json': priority_json
		}, function(data){
			if (!isNaN(data) && parseInt(data) == 1) {
				$('#<?php echo $config->tableName;?>').datagrid('reload');
			}
		});
	}

	$(function(){
		$('#<?php echo $config->tableName;?>').datagrid({
			url: '<?php echo Yii::app()->createUrl('role/data');?>',
			rownumbers: true,
            singleSelect: true,
			striped: true,
			nowrap: false,
			width: '100%',
			height: table_grid_height,
			toolbar: toolbar,
			pagination: true,
			pageSize: <?php echo $rows?$rows:50;?>,
			queryParams: {cache_key:'<?php echo $config->cacheKey;?>'},
			onSortColumn: function(sort, order){},
			onLoadSuccess: function() {
				$('.del').click(function(){
					var del_name = $(this).attr('value');
					$.messager.confirm('提示', '确认删除该角色?', function(r){
						if(r){
							$.post("<?php echo Yii::app()->createUrl('role/delete');?>", 
								{
									'del_name': del_name
								}, function(data){
									if (!isNaN(data) && parseInt(data) > 0) {
										$('#<?php echo $config->tableName;?>').datagrid('reload');
									}else{
										$.messager.alert('失败',data,'error');
									}
								}
							);
						}
					});
				});
			},
		}).datagrid('columnMoving');
		
		$('#search_name').textbox({
			onChange: query,  
		});
	})
	function query() {
		var name = $('#search_name').textbox('getValue');
		var query = $('#<?php echo $config->tableName;?>').datagrid('options').queryParams;
		query.name = name;
		$('#<?php echo $config->tableName;?>').datagrid('reload');
	}
</script>
