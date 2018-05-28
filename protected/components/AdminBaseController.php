<?php
class AdminBaseController extends Controller
{
    public $allMenus = array();  //所有菜单
    public $crumbs = array();    //面包屑名称
    
    public function filters()
    {
        return array(
                'accessControl',
        );
    }
    
    public function accessRules()
    {
        return array(
                array('allow',
                        'actions'=>array('login', 'logout'),
                        'users'=>array('*'),
                ),
                array('allow',
                        'users'=>array('@'),
                ),
                array('deny',
                        'users'=>array('*'),
                ),
        );
    }
    
    public function actions()
    {
        return array (
                // captcha action renders the CAPTCHA image displayed on the contact page
                'captcha'=>array(
                    'class'=>'CCaptchaAction',
                    'height'=>38,
                    'width'=>83,
                    'minLength'=>4,
                    'maxLength'=>4,
                    'backColor'=>0xFFFFFF,
                    'transparent'=>false,
                    'testLimit'=>999,
                ),
                // page action renders "static" pages stored under 'protected/views/site/pages'
                // They can be accessed via: index.php?r=site/page&view=FileName
                'page' => array (
                        'class' => 'CViewAction'
                )
        );
    }




    /**
     * 获得面包屑
     * @return [type] [description]
     */
    private function getBreadCrumbs()
    {
        $controller_name = $this->getId();
        $action_name = $this->getAction()->getId();
        $ca = Yii::app()->createUrl($controller_name.'/'.$action_name);
        $default_ca = Yii::app()->createUrl($controller_name.'/index');

        $match = false;
        foreach ($this->allMenus as $menu) {
            foreach ($menu->sub_menus as $sub_menu) {
                if($sub_menu->url == $ca){
                    $this->crumbs['parent'] = $menu->name;
                    $this->crumbs['child'] = $sub_menu->name;
                    $this->pageTitle = $sub_menu->name;
                    $match = true;
                    break;
                }
            }
            if($match){
                break;
            }
        }

        if(!$match){
            foreach ($this->allMenus as $menu) {
                foreach ($menu->sub_menus as $sub_menu) {
                    if($sub_menu->url == $default_ca){
                        $this->crumbs['parent'] = $menu->name;
                        $this->crumbs['child'] = $sub_menu->name;
                        $this->pageTitle = $sub_menu->name;
                        $match = true;
                        break;
                    }
                }
                if($match){
                    break;
                }
            }
        }
    }

    //执行action之前
    public function beforeAction($action)
    {
        parent::beforeAction($action);

        $controller_id = $this->getId();
        $action_id = $action->id;

        if($controller_id == 'site' && ($action_id == 'login' || $action_id == 'logout')){
            return true;
        }
        
        //获取所有菜单
        $this->allMenus = MenuItem::getValideMenus();
        //获取当前菜单名称
        $this->getBreadCrumbs();
       
        return true;
    }



    /**
     * 返回路径
     * @return string        列表Url
     */
    public function getBackUrl($action, $params=array())
    {
        return Yii::app()->createUrl($action, $params);
    }

    /**
     *
     * 图片路径
     * @param string  $img  图片名
     * @return string 图片完整路径
     */
    public function imgUrl($img) 
    {
        return Yii::app()->baseUrl."/images/admin/".$img;
    }

    /**
     * 设置缓存
     * 每个用户一个缓存文件，键名为：cache_ + 用户ID，内容：json字符串
     * @param userId  用户ID
     * @param key     json键名
     * @param value   json值
     */
    public function setCache($userId, $key, $value)
    {
        $file_cache = Yii::app()->file_cache->get('cache_'.$userId);
        if($file_cache){
            $cache_arr = json_decode($file_cache, true);
            $cache_arr[$key] = $value;
        } else {
            $cache_arr = array();
        }

        Yii::app()->file_cache->set('cache_'.$userId, json_encode($cache_arr), 30 * 24 * 3600);
    }

    /**
     * 获得缓存
     * @param userId  用户ID
     * @param key  json键名
     */
    public function getCache($userId, $key)
    {
        $file_cache = Yii::app()->file_cache->get('cache_'.$userId);
        if($file_cache){
            $cache_arr = json_decode($file_cache, true);

            return $cache_arr[$key];
        }
        
        return null;
    }


}
