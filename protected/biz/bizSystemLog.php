<?php
/**
* 操作日志
*/
class bizSystemLog
{
    /**
     * 获取操作日志列表
     */
	public static function getLogList($pageInfo) 
	{
        $page = $pageInfo['page'];
        $rows = $pageInfo['rows'];
        $sort = $pageInfo['sort'];
        $order = $pageInfo['order'];

		$model = new Log();
		$criteria = new CDbCriteria();
        $criteria->with = 'adminUser';
		
        $result["total"] = $model->count($criteria);

        $criteria->offset = ($page - 1) * $rows;
        $criteria->limit = $rows;
        $criteria->order = 't.'.$sort.' '.$order;
        

        $data = $model->findAll($criteria);
        $items = array();
        if($data){
            foreach ($data as $value) {
                $itemData = $value->attributes;
                $itemData['table_operate'] = '
                    <div class="operation">
                        <a title="详情" class="view_detail">
                            <span class="icon-tip">icon</span>
                        </a>
                    </div>';
                $itemData['oldValue'] = json_decode($itemData['oldValue'], true);
                $itemData['newValue'] = json_decode($itemData['newValue'], true);
                $itemData['last_login_ip'] = $value->adminUser->last_login_ip;
                $itemData['created_by'] = $value->adminUser->nickname;
                $itemData['created_at'] = date("Y-m-d H:i:s", $itemData['created_at']);
                array_push($items, $itemData);
            }
        }

        $result['rows'] = $items;
        return $result;
    }

    /**
     * 业务日志
     *  @param string $busName 业务名
     *  @param string $operation 操作名
     *  @param string $comment 描述
     *  @param string $comment 数据
     */
    public static function operationLog($busName, $operation, $comment="", $dataArray=array())
    {
        $log = new Log();
        $log->attributes = $dataArray;
        $log->business_name = $busName;
        $log->operation_type = $operation;
        $log->created_by = Yii::app()->user->userid;
        $log->created_at = time();
        $log->comment = $comment;
        $log->insert();
    }


}
