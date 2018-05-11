<?php

class LogController extends AdminBaseController
{
    public $layout='admin';
    
    public function actionIndex()
    {
        $labels = Common::getClassLabels('Log');

        $config = new GridConfig;
        $config->tableName = 'log_table';
        $config->cacheKey = 'log_table_rows';
        $arr = [
            'table_operate'=>['name'=>'操作','options'=>"align:'center'",'width'=>'50px'], 
            'business_name'=>['name'=>$labels['business_name'],'options'=>"align:'center'",'width'=>'150px'],
            'operation_type'=>['name'=>$labels['operation_type'],'options'=>"align:'center'",'width'=>'150px'],
            'last_login_ip'=>['name'=>'登录IP','options'=>"align:'center'",'width'=>'150px'],
            'created_at'=>['name'=>$labels['created_at'],'options'=>"align:'center',sortable:'true'",'width'=>'200px'],
            'created_by'=>['name'=>$labels['created_by'],'options'=>"align:'center'",'width'=>'120px']
        ];
        $config->setColumns($arr);

        $rows = intval($this->getCache(Yii::app()->user->userid, $config->cacheKey));

        $this->render('index', array(
            'config' => $config,
            'rows' => $rows,
            'labels' => $labels,
        ));
    }

    /**
     * 表格数据
     */
    public function actionData()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $cache_key = isset($_POST['cache_key']) ? addslashes($_POST['cache_key']) : 'log_table_rows';
        $this->setCache(Yii::app()->user->userid, $cache_key, $rows);
        
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'created_at';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

        $data = bizSystemLog::getLogList(array('page'=>$page, 'rows'=>$rows, 'sort'=>$sort, 'order'=>$order));

        echo json_encode($data);
    }

}
