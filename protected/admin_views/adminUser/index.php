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
			<span class="name">昵称：</span>
			<input id="search_name" type="text" class="easyui-textbox" data-options="prompt:'请输入昵称', width:120, height:28" />
		</div>
		<div class="search_row">
			<span class="name">角色：</span>
			<select id="search_role" class="easyui-combobox" data-options="editable:false, width:100, height:28">
				<option value="">-全部-</option>
			<?php foreach ($role_array as $v) {?>
				<option value="<?php echo $v;?>"><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>
		<div class="search_row">
			<span class="name">状态：</span>
			<select id="search_status" class="easyui-combobox" data-options="editable:false, width:80, height:28">
				<option value="">-全部-</option>
			<?php foreach ($is_disabled_array as $k => $v) {?>
				<option value="<?php echo $k;?>"><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="search_btn_row">
			<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-search'" onclick="javascript:query();">查询</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo'" onclick="javascript:reset();"></a>
		</div>
	</div>
<?php $this->endWidget();?>

<div class="div_table">
	<?php
		$this->widget('GridWidget',array('config'=>$config));
	?>
</div>

<script type="text/javascript">
	var toolbar = [{
		text:'新增用户',
		iconCls:'icon-add',
		handler:function(){
			window.location.href = '<?php echo Yii::app()->createUrl('adminUser/create');?>';
		}
	}];
	$(function(){
		$('#<?php echo $config->tableName;?>').datagrid({
			url: '<?php echo Yii::app()->createUrl('adminUser/data');?>',
			rownumbers: true,
            singleSelect: true,
			striped: true,
			nowrap: false,
			width: '100%',
			height: table_grid_height,
			toolbar: toolbar,
			pagination: true,
			pageSize: <?php echo $rows?$rows:50;?>,
			queryParams: {cache_key:'<?php echo $config->cacheKey;?>',is_disabled:0},
			onSortColumn: function(sort, order){},
			onLoadSuccess: function() {
				$('.able').click(function(){
					var id = $(this).attr('value');
					var title = $(this).attr('title');

					$.messager.confirm(title+'用户', '确认'+title+'该用户?', function(r){
						if(r){
							$.post("<?php echo Yii::app()->createUrl('adminUser/able');?>", 
								{
									'id': id,
									'title': title,
								}, function(data){
									if (!isNaN(data) && parseInt(data) > 0) {
										$('#<?php echo $config->tableName;?>').datagrid('reload');
									}
								}
							);
						}
						
					});
				});
			}
		});

		$('#search_name').textbox({
			onChange: query,  
		});
		$('#search_role').combobox({
			onChange: query,  
		});
		$('#search_status').combobox({
			value: 0,
			onChange: query,  
		});
	});

	function query() {
		var nickname = $('#search_name').textbox('getValue');
		var role_name = $('#search_role').combobox('getValue');
		var is_disabled = $('#search_status').combobox('getValue');
		var query = $('#<?php echo $config->tableName;?>').datagrid('options').queryParams;
		query.nickname = nickname;
		query.role_name = role_name;
		query.is_disabled = is_disabled;
		$('#<?php echo $config->tableName;?>').datagrid('reload');
	}
	function reset() {
		$('#search_role').combobox('setValue', '');
		$('#search_status').combobox('setValue', '');
		$('#search_form').form('clear');
	}
</script>