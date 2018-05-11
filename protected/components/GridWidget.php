<?php
/**
* 表格通用widget
* 
*/
class GridWidget extends CWidget
{
	public $config;
	public function run(){
		$this->config->render();
	}
}

