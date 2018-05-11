<?php
/**
*
*/
class OperationController extends AdminBaseController
{
    public $layout = 'admin';

    /**
     * 创建权限
     */
    public function actionCreate()
    {
        $model = new AuthRoleForm();
        $parent_menu = bizSystemOperation::getAllMenu(1);

        if (isset($_POST['AuthRoleForm'])) { //echo '<pre>';var_dump($_POST);die();
            $model->attributes = $_POST['AuthRoleForm'];
            $result = bizSystemOperation::addOperation($model, $_POST['create_menu']);
        }

        $this->render('create', array(
            'model' => $model,
            'menu_model' => $model,
            'parent_menu' => $parent_menu,
            'backUrl' => $this->getBackUrl('operation/index'),
            'result' => $result,
        ));
    }

    /**
     * 编辑权限
     */
    public function actionUpdate($name) 
    {
        $model = bizSystemOperation::getOperation($name);
        $menu_model = bizSystemOperation::getMenuByName($name);
        $parent_menu = bizSystemOperation::getAllMenu(1);
        if($menu_model->is_parent == 1){
            $parent_menu = bizSystemOperation::getAllMenu(1, $name);
        }

        if (isset($_POST['AuthRoleForm'])) {
            $result = bizSystemOperation::editOperation($model, $menu_model, $_POST['AuthRoleForm']);

            $model->attributes = $_POST['AuthRoleForm'];
        }

        $this->render('update', array(
            'model' => $model,
            'menu_model' => $menu_model,
            'parent_menu' => $parent_menu,
            'backUrl' => $this->getBackUrl('operation/index'),
            'result' => $result,
        ));
    }

    /**
     * 删除权限
     */
    public function actionDelete()
    {
        $del_name = $_POST['del_name'];
        $result = bizSystemOperation::deleteOperation($del_name);
        echo $result;
    }
    
    /**
     * 权限列表
     */
    public function actionIndex()
    {
        $showItems = Common::getClassLabels('Authitem');

        $config = new GridConfig;
        $config->tableName = 'operate_table';
        $arr = [
            'table_operate'=>['name'=>'操作','options'=>"align:'center'",'width'=>'100px'], 
            'name'=>['name'=>$showItems['name'],'options'=>"align:'center'",'width'=>'200px'],
            'priority'=>['name'=>$showItems['priority'],'options'=>"align:'center'",'width'=>'150px'],    
            'description'=>['name'=>$showItems['description'],'options'=>"align:'center'",'width'=>'300px'],
        ];
        $config->setColumns($arr);

        $this->render('index', array(
            'config' => $config,
        ));
    }

    /**
     * 表格数据
     */
    public function actionData()
    {
        $name = isset($_POST['name']) ? addslashes($_POST['name']) : ''; //操作名
        $is_child = isset($_POST['is_child']) ? intval($_POST['is_child']) : 0;
        $query = array('name'=>$name, 'is_child'=>$is_child);

        $data = bizSystemOperation::getOperationList($query);

        echo json_encode($data);
    }
    
    

    /**
     * 批量设置排序
     */
    public function actionSavePriority() 
    {
        $priority_arr = $_POST['priority_json'] ? json_decode($_POST['priority_json'], true) : array();
        $prioritys = array();
        foreach ($priority_arr as $item) {
            $prioritys[$item[0]] = $item[1] ? $item[1] : 100;
        }
        $result = bizSystemOperation::editPriority($prioritys);
        echo $result;
    }

   
}
