<?php

/**
 * Created by PhpStorm.
 * User: yy_prince
 * Date: 16/4/27
 * Time: 下午2:30
 */
class Common
{
    /**
     * 生成自定义编号
     * @param  string $type_abbreviations 类型缩写
     * @param  integer $id                 单据id
     * @return string 单号
     * 类型缩写＋日期＋6位自增序号
     */
    public static function generateNumber($type_abbreviations, $id) 
    {
        return strtoupper(trim($type_abbreviations)).date('Ymd').str_pad($id, 6, 0, STR_PAD_LEFT);
    }

    /**
     * 生成随机字符串
     * @param integer    字符串长度
     * @return string    返回字符串
     */
    public static function generateStr($len = 4)
    {
        $str = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWZYZ";
        $ret = "";
        for ($i = 0; $i < $len; $i++) { 
            $ret .= $str{mt_rand(0, 61)};
        }

        return $ret;
    }

    /**
     * 获取model参数json
     * @param  Class $model 数据model
     * @return string        返回json
     */
    public static function dataJson($model) 
    {
        return json_encode($model->attributes);
    }

    /**
     * 解析json数据
     * @param  string        json字符串
     * @return Array         返回解析数组
     */
    public static function jsonDecode($data)
    {
        $data = MAGIC_QUOTES_GPC?stripslashes($data):$data;
        $arr = json_decode($data, true);

        return $arr;
    }

	/**
	* 获取事务对象
	*/
	public static function getTransaction()
	{
		return Yii::app()->db->beginTransaction();
	}

    /**
     * 检查权限
     * @param  string $authitem 权限名
     * @param  string $userid   用户id
     * @return boolean           是否有权限
     */
    public static function getAuth($authitem, $userid = '') 
    {
        $userid = $userid ? $userid : Yii::app()->user->userid;
        $auth = Yii::app()->authManager;
        return $auth->checkAccess($authitem, $userid);
    }


    /**
     * 获取字段标签
     * @return array
     */
    public static function getClassLabels($className)
    {
        $model = new $className;
        $labels = $model->attributeLabels();

        return $labels;
    }

}
