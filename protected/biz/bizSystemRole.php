<?php
/**
* 系统角色
*/
class bizSystemRole
{
    const E_SUCCESS = 1; //操作成功

	/**
	 * 添加角色
	 * @param AuthRoleForm  $role_model  角色对象
	 * @param array         $right_names 权限
	 */
	public static function addRole($role_model, $right_names) 
	{
        if (Authitem::ifExsits($role_model->name)) { 
            return "该名称已存在";
        }

        $transaction = Common::getTransaction();
        try {
            $auth = Yii::app()->authManager;
            $auth->createRole($role_model->name, $role_model->description);

            //给角色赋予权限
            self::bindingOperation($role_model->name, $right_names);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();

            return "创建失败";
        }

        //日志
        bizSystemLog::operationLog("角色管理", "新增", "", array(
            'table_name' => "Authitem", 
            'oldValue' => "", 
            'newValue' => Common::dataJson($role_model),
        ));
        return true;
    }

	/**
	 * 修改角色
	 * @param AuthRoleForm  $role_model   角色对象
	 * @param array         $right_names  权限
     * @param array         $role_data    post数据
     * @param array         $right_data   post数据 
	 */
	public static function editRole($role_model, $right_names, $role_data, $right_data) 
	{
        if ($role_data['name'] != $role_model->name && Authitem::ifExsits($role_data['name'])) { 
            return "该名称已存在";
        }

        $transaction = Common::getTransaction();
        try {
            $oldValue = $role_model->attributes;
            $oldValue['children'] = $right_names;

            $auth = Yii::app()->authManager;
            $authItem = new CAuthItem($auth, $role_data['name'], CAuthItem::TYPE_ROLE, $role_data['description']);
            $auth->saveAuthItem($authItem, $role_model->name);

            //给角色赋予权限
            self::bindingOperation($role_data['name'], $right_data);

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();

            return "保存失败";
        }

        $role_data['children'] = $right_data;

        //日志
        bizSystemLog::operationLog("角色管理", "编辑", "", array(
            'table_name' => "Authitem", 
            'oldValue' => json_encode($oldValue), 
            'newValue' => json_encode($role_data),
        ));
        return true;
    }

    /**
     * 获取角色列表
     */
    public static function getRoleList($query, $pageInfo)
    {
        $page = $pageInfo['page'];
        $rows = $pageInfo['rows'];
        $sort = $pageInfo['sort'];
        $order = $pageInfo['order'];

        $model = new Authitem();
        $criteria = new CDbCriteria();
        $criteria->compare('type', 2);
        $criteria->addSearchCondition('name', $query['name']);
        
        $criteria->offset = ($page - 1) * $rows;
        $criteria->limit = $rows;
        $criteria->order = $sort.' '.$order;
        
        $result["total"] = $model->count($criteria);
        $data = $model->findAll($criteria);

        $items = array();
        if($data){
            foreach ($data as $value) {
                $itemData = $value->attributes;
                $itemData['table_operate'] = '
                    <div class="operation">
                        <a title="编辑"  href="'.Yii::app()->createUrl('role/update', array('name'=> $value->name)).'">
                            <span class="icon-edit">icon</span>
                        </a>
                        <a class="del" title="删除" value="'.$value->name.'">
                            <span class="icon-remove">icon</span>
                        </a>
                    </div>';
                $itemData['priority'] = '<input type="text" class="form-control priority" style="width:50px;" value="'.$value->priority.'" name="'.$value->name.'">';
                array_push($items, $itemData);
            }
        }

        $result['rows'] = $items;

        return $result;
    }


    /**
     * 绑定角色的权限
     * @param  string $right_names 权限
     * @param  string $role_name   角色
     */
    public static function bindingOperation($role_name, $right_names=array()) 
    {
        if(is_array($right_names) && !empty($right_names)) {
            $auth = Yii::app()->authManager;
            $itemChilds = $auth->getItemChildren($role_name);
            //初始化
            foreach ($itemChilds as $name => $authItem) {
                $auth->removeItemChild($role_name, $name);
            }
            //给角色赋予权限
            foreach ($right_names as $right_name) {
                $auth->addItemChild($role_name, $right_name); 
            }
        }
    }

    /**
     * 根据角色名获取角色信息
     * @param  string   $name 角色名
     * @return Authitem       角色信息
     */
    public static function getRoleByName($name) 
    {
        $model = Authitem::model()->findByAttributes(array('name' => $name, 'type' => 2));
        return $model;
    }

    /**
     * 获取角色下拥有的权限
     * @param  string $role_name 角色名
     * @return array             权限集合
     */
    public static function getOperationByRole($role_name)
    {
        $auth = Yii::app()->authManager;
        $itemChilds = $auth->getItemChildren($role_name);
        
        $result = array();
        foreach ($itemChilds as $name => $authItem) {
            $result[] = $name;
        }
        return $result;
    }

    /**
     * 删除角色
     * @param  [type] $role_name [description]
     * @return [type]            [description]
     */
    public static function deleteRole($role_name) 
    {
        $authItem = self::getRoleByName($role_name);
        $transaction = Common::getTransaction();
        try{
            $auth = Yii::app()->authManager;
            $auth->removeAuthItem($role_name);

            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();

            return "删除失败";
        }

        $ops = self::getOperationByRole($authItem->name);
        $oldValue = $authItem->attributes;
        $oldValue['children'] = $ops;
        //日志
        bizSystemLog::operationLog("角色管理", "删除", "", array(
            'table_name' => "Authitem", 
            'oldValue' => json_encode($oldValue), 
            'newValue' => "",
        ));
        return self::E_SUCCESS;
    }

    /**
     * 保存排序
     * @param  array    $prioritys 优先级集合 角色名 => 优先级
     * @return boolean             成功或失败
     */
    public static function editPriority($prioritys = array()) 
    {
        foreach ($prioritys as $role_name => $priority) {
            Authitem::model()->updateAll(array('priority'=>intval($priority)),'name=:name', array(':name'=>$role_name));
        }
        return self::E_SUCCESS;
    }


    /**
     * 获取所有角色数据
     * @return [type]       [description]
     */
    public static function getRoleArray()
    {
        $_result = array();
        $items = Authitem::model()->findAll(array('condition'=>"type = 2", 'order' => "priority, name"));

        if($items) {
            foreach ($items as $item) {
                $_result[] = $item->name;
            }
        }
        
        return $_result;
    }

    
}
