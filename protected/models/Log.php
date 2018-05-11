<?php

/**
 * This is the biz model class for table "log".
 *
 */
class Log extends LogData
{
	

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'adminUser' => array(self::BELONGS_TO, 'AdminUser', 'created_by'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'business_name' => '业务',
			'operation_type' => '动作',
			'table_name' => '表名',
			'oldValue' => '旧值',
			'newValue' => '新值',
			'comment' => '备注',
			'created_by' => '操作人',
			'created_at' => '操作时间',
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
		$criteria->compare('business_name',$this->business_name,true);
		$criteria->compare('operation_type',$this->operation_type,true);
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('oldValue',$this->oldValue,true);
		$criteria->compare('newValue',$this->newValue,true);
		$criteria->compare('comment',$this->comment,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('created_at',$this->created_at);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
		/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Log the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

}
