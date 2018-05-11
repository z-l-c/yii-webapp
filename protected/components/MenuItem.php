<?php
/**
* 菜单
*/
class MenuItem
{
    /**
     * 菜单名称
     * @var string
     */
    public $name;

    /**
     * 权限名
     * @var string
     */
    public $right_name;

    /**
     * 链接
     * @var string
     */
    public $url;

    /**
     * 图标
     * @var string
     */
    public $icon;

    /**
     * 子菜单
     * @var array
     */
    public $sub_menus = array();

    /**
     * 获取当前登录用户拥有权限的菜单
     * @return array
     */
    public static function getValideMenus()
    {
        $allMenus = MenuItem::getGlobalMenu();
        $_result = array();
        //一级菜单循环
        if($allMenus){
            foreach ($allMenus as $_menu) {
                if ($_menu->right_name != '' && !Common::getAuth($_menu->right_name)) {
                    continue;
                }
                $_menu->getValideSubMenus();
                $_result[] = $_menu;
            }
        }
        return $_result;
    }

    /**
     * 获取菜单下用户拥有权限的子菜单
     * @return array
     */
    private function getValideSubMenus()
    {
        $_result = array();
        //二级菜单循环
        foreach ($this->sub_menus as $sub_menu) {
            if ($sub_menu->right_name != '' && !Common::getAuth($sub_menu->right_name)) {
                continue;
            }
            $_result[] = $sub_menu;
        }
        $this->sub_menus = $_result;
    }

    /**
    * 创建实例
    * @return object 
    */
    private static function newItem($name, $url, $icon, $right_name)
    {
        $item = new self;
        $item->name = $name;
        $item->url = $url;
        $item->icon = $icon;
        $item->right_name = $right_name;

        return $item;
    }

    /**
     * 基本菜单
     * @return array
     */
    public static function getGlobalMenu()
    {
        global $g_menus;
        if ($g_menus) {
            return $g_menus;
        }

        $menus = bizSystemOperation::getAllMenu();
        $parents = array();
        $children = array();

        if ($menus) {
            foreach ($menus as $value) {
                if ($value->is_parent == 1) {
                    $parents[] = $value;
                } else {
                    $children[$value->parent][] = $value;
                }
            }

            if(!empty($parents)){
                foreach ($parents as $p) {
                    $parent_menu = self::newItem($p->name, '#', $p->icon, $p->name);

                    if($children[$p->name]){
                        foreach ($children[$p->name] as $c) {
                            $child_menu = self::newItem($c->name, Yii::app()->createUrl($c->url), '', $c->name);
                            $parent_menu->sub_menus[] = $child_menu;
                        }
                    }

                    $g_menus[] = $parent_menu;
                }
            }
        }

        return $g_menus;
    }
}
