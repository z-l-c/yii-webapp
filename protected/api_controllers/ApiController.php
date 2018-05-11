<?php
class ApiController extends Controller
{
	public function actionIndex()
	{	
		$post_data=file_get_contents('php://input');
		$post_data=json_decode($post_data,true);
		
		$cmd = $post_data['cmd'];  //接口指令
		$ts = $post_data['ts'];    //提交时间
		$params = $post_data['params'];   //接口逻辑参数
		
		$className = ApiBase::getCmdClass($cmd);
		if(is_array($className)){
			$result = $className;
		}else{
			$apiClass = new $className($cmd, $ts, $params);
			$result = $apiClass->handleCmd();
		}
		
		echo json_encode($result);
	}

	public function actionError()
	{
		if ($error=Yii::app()->errorHandler->error) {
            echo json_encode(array(
            	'resultCode'=>'403',
            	'resultMsg'=>'Bad Request',
            	'data'=>null,
            ));
        }
	}
}