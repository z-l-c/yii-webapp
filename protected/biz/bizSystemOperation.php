<?php
/**
* 系统操作
*/
class bizSystemOperation
{
    const E_SUCCESS = 1; //操作成功

	/**
	 * 添加操作
	 * @param AuthRoleForm  $option_model    操作实例
	 */
	public static function addOperation($option_model, $create_menu) 
	{
        if($option_model->is_parent==0 && !$option_model->parent) {
            return "请选择父操作";
        }

        if (Authitem::ifExsits($option_model->name)) {
            return "该名称已存在";
        }

        $transaction = Common::getTransaction();
        try {
            $auth = Yii::app()->authManager;
            $auth->createOperation($option_model->name, $option_model->description);

            if($option_model->is_parent == 0){
                $auth->addItemChild($option_model->parent, $option_model->name);
            }

            //添加菜单
            if($create_menu) {
                self::addMenu($option_model->attributes);
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollback();

            return "创建失败";
        }

        //日志
        bizSystemLog::operationLog("权限管理", "新增", "", array(
            'table_name' => "Authitem", 
            'oldValue' => "", 
            'newValue' => Common::dataJson($option_model),
        ));
        return true;
    }

    /**
     * 编辑操作
     * @param Authitem  $option_model    操作实例
     * @param Menu      $menu_model      菜单实例
     * @param array     $data            post数据
     */
    public static function editOperation($option_model, $menu_model, $data) 
    {
        if($menu_model && $menu_model->is_parent==0 && !$menu_model->parent) {
            return "请选择父操作";
        }

        if ($data['name'] != $option_model->name && Authitem::ifExsits($data['name'])) {
            return "该名称已存在";
        }

        $transaction = Common::getTransaction();
        try {
            $oldValue = array_merge($option_model->attributes, $menu_model->attributes);

            $auth = Yii::app()->authManager;
            $authItem = new CAuthItem($auth, $data['name'], CAuthItem::TYPE_OPERATION, $data['description']);
            $auth->saveAuthItem($authItem, $option_model->name);

            //菜单表
            if($menu_model){
                $menu_model->attributes = $data;
                $menu_model->icon = $menu_model->icon[0];
                $menu_model->save();
            }

            $transaction->commit();
        } catch (Exception $e){
            $transaction->rollback();

            return "保存失败";
        }
       
        //日志
        bizSystemLog::operationLog("权限管理", "编辑", "", array(
            'table_name' => "Authitem", 
            'oldValue' => json_encode($oldValue), 
            'newValue' => json_encode($data),
        ));
        return true;
    }

	/**
	 * 获取系统操作列表
	 */
	public static function getOperationList($query) 
	{

		$model = new Authitem();
		$criteria = new CDbCriteria();
        $criteria->select = 't.*, ac.parent as parent';

        if($query['is_child']){
            $criteria->join = 'left outer join authitemchild ac on t.name=ac.child';
            $criteria->addSearchCondition('ac.parent', $query['name']);
        }else{
            $criteria->join = 'left outer join authitemchild ac on t.name=ac.parent';
            $criteria->addCondition("ac.parent!='' and ac.parent is not null");
            $criteria->group = 't.name';
        }
        
		$criteria->compare('t.type', 0);
        
        $criteria->order = 'priority asc';

        $result["total"] = $model->count($criteria);
        $data = $model->findAll($criteria);

        $items = array();
        if($data){
            foreach ($data as $value) {
                $itemData = $value->attributes;
                $itemData['table_operate'] = '
                    <div class="operation">
                        <a title="编辑"  href="'.Yii::app()->createUrl('operation/update', array('name'=> $value->name)).'">
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
     * 获取所有操作
     * @param  string $type [description]
     * @return [type]       [description]
     */
    public static function getOperationArray($type = 'array')
    {
        $items = Authitem::model()->with('menu')->findAll(array(
            'condition' => "t.type = 0", 
            'order' => "t.priority asc, t.name asc",
        ));

        if ($type == 'array') {
            foreach ($items as $item) {
                if($item->menu->is_parent != 1){
                    $_result[$item->menu->parent][] = $item->name;
                }
            }
        } elseif ($type == 'json') {
            foreach ($items as $item) {
                $temp = array();
                $temp['name'] = $item->name;
                $_result[] = $temp;
            }
            $_result = json_encode($_result);
        }
        return $_result;
    }

    public static function getOperation($operation_name) 
    {
        $model = Authitem::model()->findByAttributes(array('name' => $operation_name, 'type' => 0));
        return $model;
    }

    /**
    * 添加菜单
    * @param   array  $attributes 参数
    * @return  object model
    */
    public static function addMenu($attributes)
    {
        $menu = new Menu();
        $menu->attributes = $attributes;
        $menu->icon = $attributes['icon'][0];
        $menu->priority = 100;
        $menu->save();

        return $menu;
    }

    /**
    * 获取符合条件的菜单
    * @param   int  $is_parent 是否父菜单
    * @return  object Array
    */
    public static function getAllMenu($is_parent = 0, $except_name = '')
    {
        $condition = array('order'=>'priority asc');
        if($is_parent){
            $condition['condition'] = 'is_parent=1';
            if(trim($except_name) != ''){
                $condition['condition'] .= " and name<>'".$except_name."'";
            }
        }

        $menu = Menu::model()->findAll($condition);
        
        return $menu;
    }

    /**
    * 根据名称获取菜单
    * @param   string  $name 菜单名称
    * @return  object model
    */
    public static function getMenuByName($name)
    {
        $model = Menu::model()->findByPk($name);
        return $model;
    }	

    /**
     * 保存排序
     * @param  array    $prioritys 优先级集合 操作名 => 优先级
     * @return boolean             成功或失败
     */
    public static function editPriority($prioritys = array()) 
    {
        foreach ($prioritys as $operation_name => $priority) {
            $transaction = Common::getTransaction();
            try {
                Authitem::model()->updateAll(array('priority'=>intval($priority)),'name=:name', array(':name'=>$operation_name));
                Menu::model()->updateAll(array('priority'=>intval($priority)),'name=:name', array(':name'=>$operation_name));

                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
            }
            
        }
        return self::E_SUCCESS;
    }

    /**
     * 删除操作
     * @param  string    $operation_name   操作名
     */    
    public static function deleteOperation($operation_name) 
    {
        $authItem = self::getOperation($operation_name);
        $transaction = Common::getTransaction();
        try {

            $auth = Yii::app()->authManager;
            $auth->removeAuthItem($operation_name);
                
            
            Menu::model()->deleteByPk($operation_name);
            $child_menu = Menu::model()->findAllByAttributes(array('parent' => $operation_name, 'is_parent' => 0));
            if($child_menu) {
                foreach ($child_menu as $value) {
                    $auth->removeAuthItem($value->name);
                    Menu::model()->deleteByPk($value->name);
                }
            }
            
            $transaction->commit();
        } catch(Exception $e) {
            $transaction->rollback();

            return "删除失败";
        }

        //日志
        bizSystemLog::operationLog("权限管理", "删除", "", array(
            'table_name' => "Authitem", 
            'oldValue' => Common::dataJson($authItem), 
            'newValue' => "",
        ));
        return self::E_SUCCESS;
    }
}
