<?php

namespace frontend\controllers;

use common\config\Conf;
use common\models\Task;
use common\models\User;
use common\services\TaskService;
use common\services\UserService;
use common\utils\ResponseUtil;
use common\utils\VerifyUtil;
use frontend\form\LoginForm;
use frontend\form\SignupForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\StringHelper;
use yii\web\ForbiddenHttpException;

/**
 * UserManageController implements the CRUD actions for User model.
 */
class UserManageController extends BaseController
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
                    'signup',
                    'login'
                ],
                'rules' => [
                    [
                        'actions' => ['signup', 'login'],
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
     * 用户列表首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户列表
     * @since 2018-01-30
     */
    public function actionList()
    {
        $data = [];

        $page = Yii::$app->request->get('page', 1);
        $pageSize = Yii::$app->request->get('pageSize', 15);
        $totalInit = Yii::$app->request->get('totalInit', 0);

        $args = [
            'status'    => Conf::USER_ENABLE,
            'companyID' => $this->companyID
        ];

        $total = null;
        if ($totalInit == 1) {
            $total = UserService::factory()->countUsers($args);
        }

        $args['limit'] = $pageSize;
        $args['offset'] = $pageSize * ($page - 1);
        $args['order'] = ['id' => SORT_DESC];
        $users = UserService::factory()->getUsers($args);

        /** @var User $user */
        foreach ((array)$users as $user) {
            $temp = [];
            $temp['id'] = $user->id;
            $temp['name'] = $user->fdName;
            $temp['login'] = $user->fdLogin;
            $temp['phone'] = $user->fdPhone ?: '无';
            $temp['email'] = $user->fdEmail ?: '无';
            $temp['portrait'] = $user->fdPortrait;
            $temp['create'] = $user->fdCreate;
            $temp['team'] = $user->team->fdName ?: '未加入';
            $temp['role'] = Yii::$app->params['role'][$user->fdRoleID];
            $temp['style'] = Yii::$app->params['colorTwo'][$user->fdRoleID];
            $temp['portrait'] = UserService::factory()->getUserPortrait($user);
            $data[] = $temp;
        }

        ResponseUtil::jsonCORS([
            'list'  => $data,
            'total' => $total
        ]);
    }
}
