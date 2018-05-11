<?php
/**
* 系统用户
*/
class bizAdmin
{
	const E_SUCCESS = 1; //操作成功

	/**
	 * 后台登录
	 * @param  string $username 账号
	 * @param  string $password 密码
	 * @param  string $isAuto   自动登录
	 */
	public static function login($username, $password, $isAuto = null) 
	{
		$username = trim($username);
		$password = trim($password);
		if ($username == '') return "请输入账号";
		if ($password == '') return "请输入密码";

		$adminUser = AdminUser::model()->findByAttributes(array('loginname' => $username, 'is_disabled' => 0));
		if (!$adminUser) return "无效账号";
		if (md5($password) != $adminUser->password) return "密码不正确";

		$adminIdentity = new AdminIdentity($username, $password);
		$adminIdentity->setState('userid', $adminUser->id);
		$adminIdentity->setState('loginname', $adminUser->loginname);
		$adminIdentity->setState('nickname', $adminUser->nickname);
		$adminUser->last_login_at = time();
		$adminUser->last_login_ip = Yii::app()->request->userHostAddress;

		$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        if (strpos($agent, 'iphone') || strpos($agent, 'ipad')) {
        	$adminUser->last_login_source = "IOS";
        } elseif (strpos($agent, 'android')) {
        	$adminUser->last_login_source = "Android";
        } else {
        	$adminUser->last_login_source = "网页";
        }
        // $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        // $is_mac = (strpos($agent, 'mac os')) ? true : false;

		$adminUser->update();		

		//自动登录
		$duration = 0;
		if ($isAuto == 'on') {
			$duration = 3600 * 24 * 30;
		} 

		Yii::app()->user->login($adminIdentity, $duration);

		return true;
	}

	/**
	 * 后台退出
	 */
	public static function logout() 
	{
		Yii::app()->user->logout();
	}

	/**
	 * 修改密码
	 * @return [type] [description]
	 */
	public static function resetPwd($user_id, $pwd_data)
	{
		$user = self::getAdminInfo($user_id);
		if($user->password != md5($pwd_data['origin_password'])) {
			return '原密码错误';
		}

		if ($pwd_data['password'] != $pwd_data['confirm_password']) {
			return "两次密码输入不一致";
		}

		if($pwd_data['password']){
            $user->password = md5($pwd_data['password']);
            $user->update();
        }

        return self::E_SUCCESS;
	}



	/*****************************************************/



	/**
	 * 添加用户
	 * @param AdminUser  $admin_model  用户对象
	 */
	public static function addAdmin($admin_model) 
	{
		if (AdminUser::ifExsits($admin_model->loginname)) {
			return "登录名已存在";
		}
			
		if ($admin_model->password != $admin_model->confirm_password) {
			return "两次密码输入不一致";
		}

		$attributes = array();
		$attributes['loginname'] = $admin_model->loginname;
		$attributes['nickname'] = $admin_model->nickname;
		$attributes['password'] = $admin_model->password?md5($admin_model->password):md5($admin_model->loginname);
		$attributes['created_at'] = time();
		$attributes['is_disabled'] = 0;

		$model = new AdminUser();
		$model->attributes = $attributes;
		if (!$model->save()) {
			return "创建失败";
		}

		$model->admin_number = Common::generateNumber('AU', $model->id);
		$model->update();

		//日志
		bizSystemLog::operationLog("用户管理", "新增", "", array(
			'table_name' => "AdminUser", 
			'oldValue' => "", 
			'newValue' => Common::dataJson($model),
		));
		return $model;
	}

	/**
	 * 修改用户
	 * @param  [type] $admin_model 用户对象
	 * @param  [type] $auths       权限
	 * @param  [type] $admin_data  post数据
	 * @param  [type] $auth_data   post数据
	 * @return [type]              [description]
	 */
	public static function editAdmin($admin_model, $auths, $admin_data, $auth_data) 
	{
		if ($admin_data['password'] != $admin_data['confirm_password']) {
			return "两次密码输入不一致";
		}

		$transaction = Common::getTransaction();
		try {
			$oldValue = $admin_model->attributes;
            $oldValue['auths'] = $auths;

            $admin = self::getAdminInfo($admin_model->id);
            $admin->nickname = $admin_data['nickname'];
            if($admin_data['password']){
            	$admin->password = md5($admin_data['password']);
            }
            $admin->update();

            //保存权限
            self::bindingAuths($auth_data, $admin->id);

			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollback();

            return "编辑失败";
		}

		$newValue = $admin->attributes;
		$newValue['auths'] = $auth_data;

		//日志
		bizSystemLog::operationLog("用户管理", "编辑", "", array(
			'table_name' => "AdminUser", 
			'oldValue' => json_encode($oldValue),
			'newValue' => json_encode($newValue),
		));
		return true;
	}

	/**
	 * 获取系统用户列表
	 */
	public static function getAdminList($query, $pageInfo) 
	{
		$model = new AdminUser();

		$page = $pageInfo['page'];
		$rows = $pageInfo['rows'];
		$sort = $pageInfo['sort'];
		$order = $pageInfo['order'];

		//角色数组
		$role_array = bizSystemRole::getRoleArray();
		$join = 'left outer join authassignment auth on t.id=auth.userid';
		if(!empty($role_array)){
			$role_str = "''";
			foreach ($role_array as $value) {
				$role_str .= ",'".$value."'";
			}
			$join .= ' and auth.itemname in ('.$role_str.')';
		}

		$criteria = new CDbCriteria();
		$criteria->join = $join;
		$criteria->select = 't.*, auth.itemname as role_name';
		$criteria->addSearchCondition('t.nickname', $query['nickname']);
		if($query['role_name']){
			$criteria->addCondition('auth.itemname=:role');
			$criteria->params[':role'] = $query['role_name'];
		}
		$criteria->compare('t.is_disabled', $query['is_disabled']);

		$result["total"] = $model->count($criteria);

		$criteria->offset = ($page - 1) * $rows;
		$criteria->limit = $rows;
		$criteria->order = $sort.' '.$order;

		$data = $model->findAll($criteria);

		$items = array();
		if($data){
			foreach ($data as $value) {
				$itemData = $value->attributes;

				$itemData['table_operate'] = '
					<div class="operation">
						<a href="'.Yii::app()->createUrl('adminUser/update', array('id' => $value->id)).'" title="编辑">
							<span class="icon-edit" >icon</span>
						</a>'.($value->is_disabled==0?'
						<a class="able" title="禁用" value="'.$value->id.'" data="'.$value->is_disabled.'">
							<span class="icon-no">icon</span>
						</a>':'
						<a class="able" title="启用" value="'.$value->id.'" data="'.$value->is_disabled.'">
							<span class="icon-ok">icon</span>
						</a>').'
					</div>';

				$itemData['role_name'] = $value->role_name;
				$itemData['last_login_at'] = $value->last_login_at?date("Y-m-d H:i:s", $value->last_login_at):'';
				$itemData['disabled'] = AdminUser::$isDisabledSet[$value->is_disabled];
				array_push($items, $itemData);
			}
		}

		$result['rows'] = $items;

		return $result;
	}

	/**
	 * 禁用/启用用户
	 * @return 
	 */
	public static function ableAdminUser($id, $op)
	{
		if($op == '启用'){
			$able = 0;
		}else{
			$able = 1;
		}

		$result = AdminUser::model()->updateAll(array('is_disabled'=>$able), 'id = :uid', array(':uid'=>$id));

		return $result;
	}



	/**
	 * 获取系统用户信息
	 * @param  integer $id 系统用户id
	 */
	public static function getAdminInfo($id) 
	{
		$model = AdminUser::model()->findByPk($id);
		return $model;
	}


	/**
	 * 绑定用户权限
	 * @param  array   $right_names   角色
	 * @param  integer $user_id       系统用户id
	 */
	public static function bindingAuths($right_names, $user_id) 
	{
		if(is_array($right_names) && !empty($right_names)) {
			$auth = Yii::app()->authManager;
			//清除原有权限
			$oldValue = $auth->getAuthAssignments($user_id);
			foreach ($oldValue as $name) {
				$auth->revoke($name->itemname, $user_id);
			}
			//添加权限
			foreach ($right_names as $add) {
				if($auth->getAuthItem($add)) {
					$auth->assign($add, $user_id);
				}
			}
		}
	}

	/**
     * 获取用户所有权限
     * @param  integer $user_id 用户id
     * @return array
     */
    public static function getAuthsByUser($user_id)
    {
        $authAssignment = Authassignment::model()->findAll("userid = :userid", array(':userid' => $user_id));
        $result = array();
        foreach ($authAssignment as $item) {
            $result[] = $item->itemname;
        }
        return $result;
    }


}
