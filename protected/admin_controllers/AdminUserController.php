<?php
/**
* 系统用户管理
*/
class AdminUserController extends AdminBaseController
{
    public $layout = 'admin';

    /**
     * 创建用户
     */
    public function actionCreate()
    {
        $model = new AdminUser();

        if (isset($_POST['AdminUser'])) {
            $model->attributes = $_POST['AdminUser'];
            $model->confirm_password = $_POST['AdminUser']['confirm_password'];

            $result = bizAdmin::addAdmin($model);
            if ($result && !is_string($result)) {
                //保存权限
                $right_names = $_POST['right_names'];
                bizAdmin::bindingAuths($right_names, $result->id);
            }
        }

        $role_array = bizSystemRole::getRoleArray();
        $operation = bizSystemOperation::getOperationArray();

        $this->render('create', array(
            'model' => $model,
            'role_array' => $role_array,
            'has_auths' => array(),
            'operation' => $operation,
            'backUrl' => $this->getBackUrl('adminUser/index'),
            'result' => $result,
        ));
    }

    /**
     * 编辑用户
     */
    public function actionUpdate($id)
    {
        $model = bizAdmin::getAdminInfo($id);
        $has_auths = bizAdmin::getAuthsByUser($model->id);

        if (isset($_POST['AdminUser'])) {
            $result = bizAdmin::editAdmin($model, $has_auths, $_POST['AdminUser'], $_POST['right_names']);

            $model->attributes = $_POST['AdminUser'];
            $has_auths = $_POST['right_names'];
        }

        $role_array = bizSystemRole::getRoleArray();
        $operation = bizSystemOperation::getOperationArray();

        $this->render('update', array(
            'model' => $model,
            'role_array' => $role_array,
            'has_auths' => $has_auths,
            'operation' => $operation,
            'backUrl' => $this->getBackUrl('adminUser/index'),
            'result' => $result,
        ));
    }

    /**
     * 用户列表
     */
    public function actionIndex()
    {
        $showItems = Common::getClassLabels('AdminUser');

        $config = new GridConfig;
        $config->tableName = 'user_table';
        $config->cacheKey = 'user_table_rows';
        $arr = [
            'table_operate'=>['name'=>'操作','freezon'=>true,'options'=>"align:'center'",'width'=>'100px'], 
            'nickname'=>['name'=>$showItems['nickname'],'freezon'=>true,'options'=>"align:'center'",'width'=>'120px'],
            'loginname'=>['name'=>$showItems['loginname'],'options'=>"align:'center'",'width'=>'120px'],
            'role_name'=>['name'=>'角色','options'=>"align:'center'",'width'=>'120px'],    
            'disabled'=>['name'=>$showItems['is_disabled'],'options'=>"align:'center'",'width'=>'100px'],    
            'last_login_at'=>['name'=>$showItems['last_login_at'],'options'=>"align:'center'",'width'=>'150px'],
            'last_login_ip'=>['name'=>$showItems['last_login_ip'],'options'=>"align:'center'",'width'=>'120px'],
            'last_login_source'=>['name'=>$showItems['last_login_source'],'options'=>"align:'center'",'width'=>'120px'],
        ];
        $config->setColumns($arr);

        $rows = intval($this->getCache(Yii::app()->user->userid, $config->cacheKey));

        $role_array = bizSystemRole::getRoleArray();
        $is_disabled_array = AdminUser::$isDisabledSet;

        $this->render('index', array(
            'config' => $config,
            'rows' => $rows,
            'role_array' => $role_array,
            'is_disabled_array' => $is_disabled_array,
        ));
    }


    /**
     * 表格数据
     */
    public function actionData()
    {
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;

        $cache_key = isset($_POST['cache_key']) ? addslashes($_POST['cache_key']) : 'user_table_rows';
        $this->setCache(Yii::app()->user->userid, $cache_key, $rows);

        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';

        $nickname = isset($_POST['nickname']) ? addslashes($_POST['nickname']) : '';  //昵称
        $role_name = isset($_POST['role_name']) ? addslashes($_POST['role_name']) : '';   //角色
        $is_disabled = isset($_POST['is_disabled']) ? intval($_POST['is_disabled']) : 0;  //状态
        $query = array('nickname'=>$nickname, 'role_name'=>$role_name, 'is_disabled'=>$is_disabled);

        $data = bizAdmin::getAdminList($query, array('page'=>$page, 'rows'=>$rows, 'sort'=>$sort, 'order'=>$order));

        echo json_encode($data);
    }

    /**
     * 启用禁用
     */
    public function actionAble()
    {
        $id = intval($_REQUEST['id']);
        $title = addslashes($_REQUEST['title']);

        $result = bizAdmin::ableAdminUser($id, $title);
        echo $result;
    }

}
