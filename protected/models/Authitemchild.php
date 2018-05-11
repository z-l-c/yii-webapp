<?php

/**
 * This is the biz model class for table "authitemchild".
 *
 */
class Authitemchild extends AuthitemchildData
{
    

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'parent0' => array(self::BELONGS_TO, 'Authitem', 'parent'),
            'child0' => array(self::BELONGS_TO, 'Authitem', 'child'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'parent' => 'Parent',
            'child' => 'Child',
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

        $criteria->compare('parent', $this->parent, true);
        $criteria->compare('child', $this->child, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
    
        /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Authitemchild the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
