<?php

namespace frontend\controllers;

use frontend\form\LoginForm;
use frontend\form\SignupForm;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $layout = 'main-member';

    /**
     * 登录页
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'main-login';

        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['default/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 注册
     * @return mixed
     * @since 2016-02-27
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($member = $model->signup()) {
                if ($model->login()) {
                    Yii::$app->session->setFlash('success', 'zcshop 欢迎你~~');
                    $this->redirect(['member/index']);
                } else {
                    $url = Url::toRoute(['member/login']);
                    $msg = '注册成功了，点击<a href="' . $url . '">这里</a>登录';
                    Yii::$app->session->setFlash('success', $msg);
                }
            } else {
                Yii::$app->session->setFlash('error', '很抱歉，注册失败了~~');
            }
        }

        $this->layout = 'main_login';
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * 退出登录
     * @return mixed
     * @since 2016-02-28
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['member/login']);
    }
}
