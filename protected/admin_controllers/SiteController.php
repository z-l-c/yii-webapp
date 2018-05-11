<?php

class SiteController extends AdminBaseController
{
    public $layout='admin';

    /**
     * Index action.
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error=Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->pageTitle = $this->crumbs['parent'] = "Error";
                $this->render('error', $error);
            }
        }
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new AdminUser();

        if (isset($_POST['AdminUser'])) {
            $model->attributes = $_POST['AdminUser'];
            $isAuto = $_POST['isAuto'];

            $result = bizAdmin::login($model->loginname, $model->password, $isAuto);
            if ($result && !is_string($result)) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        $this->renderPartial('login', array(
            'model' => $model,
            'result' => $result,
        ));
    }
    
    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        bizAdmin::logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    /**
     * reset pwd
     */
    public function actionResetPwd()
    {
        $this->pageTitle = $this->crumbs['parent'] = "修改密码";

        if (isset($_POST['AdminUser'])) {
            $user_id = Yii::app()->user->userid;
            $result = bizAdmin::resetPwd($user_id, $_POST['AdminUser']);
        }

        $this->render('resetpwd', array(
            'backUrl' => $this->getBackUrl('site/index'),
            'result' => $result,
        ));
    }

}
