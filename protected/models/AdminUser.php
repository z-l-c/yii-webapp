 <?php

/**
 * This is the biz model class for table "admin_user".
 *
 */
class AdminUser extends AdminUserData
{
    public $confirm_password;
    public $role_name;

    public static $isDisabledSet = array('0' => "启用", '1' => "禁用");

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'admin_number' => '用户编码',
            'loginname' => '登录名',
            'nickname' => '昵称',
            'password' => '密码',
            'created_at' => '创建时间',
            'last_login_at' => '最近登录时间',
            'last_login_ip' => '最近登录IP',
            'last_login_source' => '最近登录终端',
            'is_disabled' => '是否启用',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('admin_number',$this->admin_number,true);
        $criteria->compare('loginname',$this->loginname,true);
        $criteria->compare('nickname',$this->nickname,true);
        $criteria->compare('password',$this->password,true);
        $criteria->compare('created_at',$this->created_at);
        $criteria->compare('last_login_at',$this->last_login_at);
        $criteria->compare('last_login_ip',$this->last_login_ip,true);
        $criteria->compare('last_login_source',$this->last_login_source,true);
        $criteria->compare('is_disabled',$this->is_disabled);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
        /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AdminUser the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public static function ifExsits($loginname)
    {
        return self::model()->exists('loginname = :loginname', array(':loginname' => $loginname));
    }

} 