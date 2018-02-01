<?php

namespace frontend\controllers;

use common\config\Conf;
use common\models\User;
use common\services\UserService;
use common\utils\ResponseUtil;
use common\utils\VerifyUtil;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

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
            'status'    => [Conf::USER_ENABLE, Conf::USER_FREEZE],
            'companyID' => $this->companyID
        ];

        $total = null;
        if ($totalInit == 1) {
            $total = UserService::factory()->countUsers($args);
        }

        $args['limit'] = $pageSize;
        $args['offset'] = $pageSize * ($page - 1);
        $args['order'] = ['id' => SORT_ASC];
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
            $temp['roleID'] = $user->fdRoleID;
            $temp['status'] = $user->fdStatus;
            $temp['portrait'] = UserService::factory()->getUserPortrait($user);
            $data[] = $temp;
        }

        ResponseUtil::jsonCORS([
            'list'  => $data,
            'total' => $total
        ]);
    }

    /**
     * 导入成员
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionImport()
    {
        // 权限检测，只有超级管理员和普通管理员才能执行
        if (!Yii::$app->user->can('importUser')) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            if (!$data['accounts']) {
                ResponseUtil::jsonCORS(['status' => 0, 'msg' => '账号不能为空']);
            }

            $existAccounts = []; // 已存在的账号
            $newAccounts = []; // 新账号
            $accounts = array_unique(array_filter(explode(',', $data['accounts'])));

            foreach ($accounts as $account) {
                $account = trim($account);

                // 账号已经存在
                if (UserService::factory()->getUserObjByAccount($account)) {
                    $exists[] = $account;
                    continue;
                }

                $temp = [
                    'name'  => '',
                    'phone' => '',
                    'email' => '',
                    'login' => 't_' . VerifyUtil::getRandomCode(8, 3)
                ];

                if (VerifyUtil::checkPhone($account)) {
                    $temp['phone'] = $account;
                    $temp['name'] = $account;
                } elseif (VerifyUtil::checkEmail($account)) {
                    $temp['email'] = $account;
                    $temp['name'] = $account;
                } else {
                    $temp['login'] = $account;
                    $temp['name'] = $account;
                }

                $newAccounts[] = $temp;
            }

            // 成员入库
            if ($newAccounts) {
                UserService::factory()->batchCreateUser($this->companyID, $newAccounts);
                ResponseUtil::jsonCORS([
                    'status' => 0,
                    'exists' => $existAccounts
                ]);
            }

            ResponseUtil::jsonCORS([
                'status' => 1,
                'exists' => $existAccounts
            ]);
        }

        return $this->render('import');
    }

    /**
     * 删除用户
     * @param $userID
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-30
     */
    public function actionDelete($userID)
    {
        $user = $this->checkUserAccess($userID);
        $res = User::updateAll(['fdStatus' => Conf::USER_DISABLE], ['id' => $user->id]) ? Conf::SUCCESS : Conf::FAILED;
        ResponseUtil::jsonCORS(null, $res);
    }

    /**
     * 设置角色
     * @param int $userID 用户ID
     * @param int $roleID 角色ID
     * @throws ForbiddenHttpException
     */
    public function actionSetRole($userID, $roleID)
    {
        if (!Yii::$app->user->can('setRole')) {
            throw new ForbiddenHttpException('你无权限设置角色，联系下你的管理员吧');
        }

        if (!in_array($roleID, [Conf::ROLE_ADMIN, Conf::ROLE_MEMBER, Conf::ROLE_GUEST])) {
            throw new ForbiddenHttpException('非法参数请求');
        }

        $user = $this->checkUserAccess($userID);
        $res = User::updateAll(['fdRoleID' => $roleID], ['id' => $user->id]) ? Conf::SUCCESS : Conf::FAILED;
        ResponseUtil::jsonCORS(null, $res);
    }

}
