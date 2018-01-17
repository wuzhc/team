<?php

namespace frontend\controllers;

use frontend\form\LoginForm;
use frontend\form\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends BaseController
{
    public $layout = 'main-member';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => [
                    'logout',
                    'signup'
                ],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 登录页
     * @return string
     * @since 2018-01-14
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
     * @since 2018-01-16
     */
    public function actionSignup()
    {
        $this->layout = 'main-login';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if ($model->login()) {
                    $this->redirectMsgBox(['default/index'], $user->fdName . ' 欢迎你...');
                } else {
                    $this->redirectMsgBox(['user/login'], '注册成功，请登录...');
                }
            } else {
                $this->redirectMsgBox(['user/signup'], '注册失败了...');
            }
        }

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
        return $this->redirect(['/user/login']);
    }
}
