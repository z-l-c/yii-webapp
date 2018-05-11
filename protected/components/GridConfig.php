<?php
/**
* Grid configure
*/
class GridConfig
{
	private $_quee;//队列数组
	private $_freezon;//
	private $_columns;
	public $dataUrl;//获取数据的url地址
	public $domId;//table的id
	public $tableName;//该表格的名字，全站唯一，用于cookie记录表格配置
	public $cacheKey; //表格行数缓存键名
	public function setColumns($columns = array())
	{
		$this->_columns = $columns;
		$this->_quee = array();
		$_freenArray= array();

		$noWidth = 0;//没有设定宽度的列数量
		//将冻结的可以放到最前面
		foreach ($columns as $key => $value) {
			if ($value['freezon']) {
				//数组里规定了冻结
				$_freenArray[] = $key;
			}else{
				$this->_quee[] = $key;
			}

			if (!$value['width']) {
				$noWidth++;
			}
		}
		$this->_freezon = $_freenArray;
		// $_quee = array_merge($_freenArray, $this->_quee);
		
		if ($noWidth <= 0) return;	
		$_w = ceil(100/$noWidth);
		//如果有列未指定宽度，则计算宽度
		foreach ($this->_columns as $col => &$val) {
			if ($val['width']) continue;
			$val['width'] = $_w ."%";
		}
	}

	private function renderColumn($key ,$column)
	{
		echo "<th data-options=\"field:'{$key}',{$column['options']}\" width=\"{$column['width']}\">{$column['name']}</th>";
	}

	public function render()
	{
		?>
		<table class="table" id="<?php echo $this->tableName; ?>">
			<?php
				if ($this->_freezon){
			?>
			<thead data-options="frozen:true">
	            <tr>
	                <?php foreach ($this->_freezon as $key) { 
	                	$this->renderColumn($key, $this->_columns[$key]);
	                } ?>
	            </tr>
	        </thead>
	        <?php } ?>
			<thead>
				<tr>
					<?php foreach ($this->_quee as $key) {
						$this->renderColumn($key, $this->_columns[$key]);
					}
					?>
				</tr>
			</thead>
		</table>
		<?php
	}





	public function getFreezonColumns()
	{
		$ret = array();
		foreach ($this->_freezon as $key) {
			$ret[$key]= $this->_columns[$key];
		}

		return $ret;
	}

	public function getNormalColumns()
	{
		$ret = array();
		foreach ($this->_quee as $key) {
			$ret[$key]= $this->_columns[$key];
		}

		return $ret;
	}

	public function getColumns()
	{
		return $this->_columns;
	}
	
}