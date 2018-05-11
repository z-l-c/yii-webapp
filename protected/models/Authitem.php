<?php

/**
 * This is the biz model class for table "authitem".
 *
 */
class Authitem extends AuthitemData
{
    public $parent;
    public $children;

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'authassignments' => array(self::HAS_MANY, 'Authassignment', 'itemname'),
            'authitemchildren' => array(self::HAS_MANY, 'Authitemchild', 'parent'),
            'authitemchildren1' => array(self::HAS_MANY, 'Authitemchild', 'child'),
            'menu' => array(self::HAS_ONE, 'Menu', 'name'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => '名称',
            'type' => '类型',
            'description' => '描述',
            'bizrule' => 'Bizrule',
            'data' => 'Data',
            'priority' => '排序',
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

        $criteria->compare('name', $this->name, true);
        $criteria->compare('type', $this->type);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('bizrule', $this->bizrule, true);
        $criteria->compare('data', $this->data, true);
        $criteria->compare('priority', $this->priority);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
        /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Authitem the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }


    public static function ifExsits($name)
    {
        return self::model()->exists('name = :name', array(':name' => $name));
    }
    
}
