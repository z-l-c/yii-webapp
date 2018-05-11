<?php
/**
* 接口顶级类
*/
class ApiBase
{
	//允许的命令白名单
	private $allowCmds = array(
			'login','logout'
	);
	//需要加密验证的命令
	private $verifyCmds = array(
			'login'
	);
    //加密验证的密钥
	private $security = 'security';
    //提交时间和服务端接收到请求的最大时间间隔，单位：秒
    private $maxTime = 300;

    //错误码常量定义 
    const E_CMD_NOT_FOUND = 100;  //命令不存在 
    const E_INVALID_ACCESS = 101; //密钥验证未通过
    const E_FUC_NOT_FOUND = 102; //找不到方法
    const E_SUCCESS = 200;  //成功
    const E_VERIFY_SUCCESS = 201; //验证通过

    //错误信息常量定义
    static $errMsg = array(
        self::E_CMD_NOT_FOUND => '非法操作',
        self::E_INVALID_ACCESS => '参数验证失败',
        self::E_FUC_NOT_FOUND => '找不到方法',
        self::E_SUCCESS => '成功'
    );

    public $cmd = '';//命令名称
    public $ts = 0; //提交时间
    public $params = array();//接口参数
    
    /**
     * 构造函数
     * @param  string  $cmd     命令名称
     * @param  int     $ts      提交时间
     * @param  array   $params  接口参数
     * @return void
     */
    function __construct($cmd, $ts, $params)
    {
        $this->cmd = $cmd;
        $this->ts = $ts;
        $this->params = $params;
    }
    /**
     * 根据cmd获取处理的子类
     * 
     * @param  [type] command name   [description]
     * @return [type] class name     [description]
     */
    static function getCmdClass($cmd)
    {
        switch ($cmd) {
            case 'login':
                return "UserApi";break;//登录
            case 'logout':
                return "UserApi";break;
            default:
                return self::echoResult($cmd, self::E_CMD_NOT_FOUND, self::$errMsg[self::E_CMD_NOT_FOUND]);break;
        }
    }
    /**
     * 密钥验证
     * @return [bool]    是否验证成功
     */
    function verifyKey()
    {
    	$params = $this->params;
    	$verify = $params['verify'];
    	unset($params['verify']);

        //加密方式
    	$md5key = md5(implode('', $params).$this->security);

    	if($verify != $md5key){
    		return false;
    	}else{
    		return true;
    	}
    }

    /**
     * 提交时间验证
     * @return [bool]    是否验证成功
     */
    function verifyTimeStamp()
    {
        if(time() - $this->ts >= $this->maxTime){
            return false;
        }

        return true;
    }

    /**
     * [_verfiyBase description]
     * @param  [array] $params 输入的参数，必须包含rand,cmd
     * @return [type]         [description]
     */
    private function _verfiyBase()
    {
        if (!in_array($this->cmd,$this->allowCmds)) {
            return self::E_CMD_NOT_FOUND;
        }
        if (in_array($this->cmd,$this->verifyCmds)) {
            if (!isset($this->params['verify']) || !$this->verifyKey() || !$this->verifyTimeStamp()) {
                return self::E_INVALID_ACCESS;//验证失败
            }
        }
       
        return self::E_VERIFY_SUCCESS;
    }
    /*
    * 调用实际api类
    */
    function handleCmd()
    {
        $verifyBase = $this->_verfiyBase();
        //密钥验证未通过
        if ($verifyBase != self::E_VERIFY_SUCCESS) {
            return self::echoResult($this->cmd, $verifyBase, self::$errMsg[$verifyBase]);
        }
        //方法不存在
        if (!method_exists($this,$this->cmd)) {
            return self::echoResult($this->cmd, self::E_FUC_NOT_FOUND, self::$errMsg[self::E_FUC_NOT_FOUND]);
        }

        return call_user_func_array(array($this,$this->cmd),array('cmd'=>$this->cmd,'params'=>$this->params));
    }
    /**
     * 返回结果
     * @param  string  $cmd          命令
     * @param  int     $verifyBase   错误码
     * @param  string  $errMsg       错误信息
     * @param  array   $body         结果数组
     * @return array
     */
    static function echoResult($cmd, $verifyBase, $errMsg, $data=array())
    {
    	return array(
    			'cmd'=>$cmd,
    			'resultCode'=>$verifyBase,
    			'resultMsg'=>$errMsg,
                'data'=>$data
    	);
    }

}