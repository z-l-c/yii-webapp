<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
    'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => "#"),
);
?>

<div class="div_table" >
	<?php
		$columns = $config->getColumns();

		$this->widget('GridWidget',array('config'=>$config));
	?>
</div>

<script type="text/javascript">
	var toolbar = [{
		text:'新增权限',
		iconCls:'icon-add',
		handler:function(){
			window.location.href = '<?php echo Yii::app()->createUrl('operation/create');?>';
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
			var operation_name = $(this).attr('name');
			var priority = $(this).val();
			if(isClear) {
				priority = 100;
			}
			priority_arr.push(new Array(operation_name, priority));
		});
		var priority_json = JSON.stringify(priority_arr);

		$.post("<?php echo Yii::app()->createUrl('operation/savePriority');?>", 
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
			url: '<?php echo Yii::app()->createUrl('operation/data');?>',
			rownumbers: true,
            singleSelect: true,
			striped: true,
			nowrap: false,
			width: '100%',
			height: table_grid_height + 60,
			toolbar: toolbar,
			pagination: false,
			queryParams: {cache_key:'<?php echo $config->cacheKey;?>'},
			onSortColumn: function(sort, order){},
			onLoadSuccess: function() {
				$('.del').click(function(){
					var del_name = $(this).attr('value');
					$.messager.confirm('提示', '确认删除该权限?<br/>如果是上级权限，则其所有子级也会被删除', function(r){
						if(r){
							$.post("<?php echo Yii::app()->createUrl('operation/delete');?>", 
								{
									'del_name': del_name
								}, function(data){
									if (!isNaN(data) && parseInt(data) > 0) {
										$('#<?php echo $config->tableName;?>').datagrid('reload');
									}else{
										$.messager.alert('失败',data,'error');
									}
								});
						}
					});
				});
			},
			view: detailview,
            detailFormatter:function(index,row){
                return '<div style="padding:2px;position:relative;"><table class="ddv"></table></div>';
            },
            onExpandRow: function(index,row){
            	var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
                ddv.datagrid({
                    url:'<?php echo Yii::app()->createUrl('operation/data');?>',
                    singleSelect: true,
                    rownumbers: false,
                    showHeader: false,
                    height:'auto',
                    columns:[[

                    	<?php 
                    		if(count($columns) > 0) {
                    			foreach ($columns as $key => $value) {
                    	?>
                        {field:'<?php echo $key;?>',title:'<?php echo $value['name'];?>',width:'<?php echo $value['width'];?>', <?php echo $value['options'];?>},
                        <?php }}?>

                    ]],
                    queryParams: {is_child: 1, name: row.name},
                    onResize:function(){
                        $('#<?php echo $config->tableName;?>').datagrid('fixDetailRowHeight',index);
                    },
                    onLoadSuccess:function(){
                        setTimeout(function(){
                            $('#<?php echo $config->tableName;?>').datagrid('fixDetailRowHeight',index);
                        },0);
                    }
                });
                $('#<?php echo $config->tableName;?>').datagrid('fixDetailRowHeight',index);
            }
		}).datagrid('columnMoving');

	})
</script>