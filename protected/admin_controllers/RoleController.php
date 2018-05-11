<?php

class RoleController extends AdminBaseController
{
    public $layout='admin';
    
    /**
     * 创建角色
     */
    public function actionCreate()
    {
        $model = new AuthRoleForm();

        if (isset($_POST['AuthRoleForm'])) {
            $model->attributes = $_POST['AuthRoleForm'];
            $has_operation = $_POST['right_names'];

            $result = bizSystemRole::addRole($model, $has_operation);
        }

        $operation = bizSystemOperation::getOperationArray();

        $this->render('create', array(
            'model' => $model,
            'operation' => $operation,
            'has_operation' => $has_operation,
            'backUrl' => $this->getBackUrl('role/index'),
            'result' => $result,
        ));
    }

    /**
     * 编辑角色
     */
    public function actionUpdate($name)
    {
        $model = bizSystemRole::getRoleByName($name);
        $has_operation = bizSystemRole::getOperationByRole($name);

        if (isset($_POST['AuthRoleForm'])) {
            $result = bizSystemRole::editRole($model, $has_operation, $_POST['AuthRoleForm'], $_POST['right_names']);

            $model->attributes = $_POST['AuthRoleForm'];
            $has_operation = $_POST['right_names'];
        }

        $operation = bizSystemOperation::getOperationArray();

        $this->render('update', array(
            'model' => $model,
            'operation' => $operation,
            'has_operation' => $has_operation,
            'backUrl' => $this->getBackUrl('role/index'),
            'result' => $result,
        ));
    }
    
    /**
     * 角色列表
     */
    public function actionIndex()
    {
        $showItems = Common::getClassLabels('Authitem');

        $config = new GridConfig;
        $config->tableName = 'role_table';
        $config->cacheKey = 'role_table_rows';
        $arr = [
            'table_operate'=>['name'=>'操作','options'=>"align:'center'",'width'=>'100px'], 
            'name'=>['name'=>$showItems['name'],'options'=>"align:'center'",'width'=>'200px'],
            'priority'=>['name'=>$showItems['priority'],'options'=>"align:'center'",'width'=>'150px'],    
            'description'=>['name'=>$showItems['description'],'options'=>"align:'center'",'width'=>'300px'],
        ];
        $config->setColumns($arr);

        $rows = intval($this->getCache(Yii::app()->user->userid, $config->cacheKey));

        $this->render('index', array(
            'config' => $config,
            'rows' => $rows,
        ));
    }

    /**
     * 表格数据
     */
    public function actionData()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $cache_key = isset($_POST['cache_key']) ? addslashes($_POST['cache_key']) : 'role_table_rows';
        $this->setCache(Yii::app()->user->userid, $cache_key, $rows);

        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'priority';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'desc';

        $name = isset($_POST['name']) ? addslashes($_POST['name']) : ''; //角色名
        $query = array('name'=>$name);

        $data = bizSystemRole::getRoleList($query, array('page'=>$page, 'rows'=>$rows, 'sort'=>$sort, 'order'=>$order));

        echo json_encode($data);
    }

    /**
     * 删除角色
     */
    public function actionDelete()
    {
        $del_name = $_POST['del_name'];
        $result = bizSystemRole::deleteRole($del_name);
        echo $result;
    }

    /**
     * 批量设置优先级
     */
    public function actionSavePriority() 
    {
        $priority_arr = $_POST['priority_json'] ? json_decode($_POST['priority_json'], true) : array();
        $prioritys = array();
        foreach ($priority_arr as $item) {
            $prioritys[$item[0]] = $item[1] ? $item[1] : 100;
        }
        $result = bizSystemRole::editPriority($prioritys);
        echo $result;
    }

    /**
     * 角色拥有权限看板
     * @return [type] [description]
     */
    public function actionHasAuths()
    {
        $role_name = addslashes($_REQUEST['role']);
        $has_operation = bizSystemRole::getOperationByRole($role_name);
        $operation = bizSystemOperation::getOperationArray();

        $this->renderPartial('_auth', array(
            'operation' => $operation,
            'has_operation' => $has_operation,
        ));
    }

}
