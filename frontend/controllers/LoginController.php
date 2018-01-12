<?php
/**
 * Created by PhpStorm.
 * User: wuzc
 * Date: 18-1-12
 * Time: 下午7:44
 */

namespace frontend\controllers;


use frontend\form\LoginForm;
use Yii;
use yii\web\Controller;

class LoginController extends Controller
{
    /**
     * 登录页
     * @return string
     */
    public function actionIndex()
    {
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
}