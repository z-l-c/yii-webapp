<?php

/**
 * This is the model class for table "admin_user".
 *
 * The followings are the available columns in table 'admin_user':
 * @property integer $id
 * @property string $admin_number
 * @property string $loginname
 * @property string $nickname
 * @property string $password
 * @property integer $created_at
 * @property integer $last_login_at
 * @property string $last_login_ip
 * @property string $last_login_source
 * @property integer $is_disabled
 */
class AdminUserData extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'admin_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('created_at, last_login_at, is_disabled', 'numerical', 'integerOnly'=>true),
			array('admin_number, loginname, nickname, last_login_ip, last_login_source', 'length', 'max'=>45),
			array('password', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, admin_number, loginname, nickname, password, created_at, last_login_at, last_login_ip, last_login_source, is_disabled', 'safe', 'on'=>'search'),
		);
	}

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
			'admin_number' => 'Admin Number',
			'loginname' => 'Loginname',
			'nickname' => 'Nickname',
			'password' => 'Password',
			'created_at' => 'Created At',
			'last_login_at' => 'Last Login At',
			'last_login_ip' => 'Last Login Ip',
			'last_login_source' => 'Last Login Source',
			'is_disabled' => 'Is Disabled',
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
}
