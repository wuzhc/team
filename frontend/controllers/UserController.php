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
use yii\web\NotFoundHttpException;

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
        if ($model->load(Yii::$app->request->post())) {
            $res = $model->login();
            if ($res === 1) {
                return $this->redirect(['default/index']);
            } else {
                $this->redirectMsgBox(['user/login'], Yii::$app->params['loginMsg'][$res]);
            }
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
                    $this->redirectMsgBox(['default/index'], '亲爱的' . $user->fdName . '，欢迎你使用Team');
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

    /**
     * 个人中心
     * @param null|int $userID 用户ID
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @since 2018-01-25
     */
    public function actionProfile($userID = null)
    {
        if ($userID) {
            $user = $this->_checkUserAccess($userID);
        } else {
            /** @var User $user */
            $user = Yii::$app->user->identity;
        }

        return $this->render('profile', [
            'user' => $user,
        ]);
    }

    /**
     * @since 2018-01-24
     */
    public function actionTasks()
    {
        if (Yii::$app->request->isAjax) {
            $params = Yii::$app->request->get();
            $progress = isset($params['progress']) ? $params['progress'] : null;

            if (!empty($params['userID'])) {
                $user = $this->_checkUserAccess($params['userID']);
                $userID = $user->id;
            } else {
                $userID = Yii::$app->user->id;
            }

            $args = [
                'creatorID' => $userID,
                'progress'  => $progress,
            ];

            // 总数
            $total = null;
            if (!empty($params['totalInit']) && $params['totalInit'] == 1) {
                $total = TaskService::factory()->countCompanyTasks($this->companyID, $args);
            }

            $args['limit'] = !empty($params['pageSize']) ? $params['pageSize'] : 10;
            $args['offset'] = !empty($params['page']) ? ($params['page'] - 1) * $args['limit'] : 0;
            $args['order'] = ['id' => SORT_DESC];
            $tasks = TaskService::factory()->getCompanyTasks($this->companyID, $args);

            // 列表数据
            $list = [];
            /** @var Task $task */
            foreach ((array)$tasks as $task) {
                $temp = [];
                $temp['id'] = $task->id;
                $temp['originName'] = $task->fdName;
                $temp['name'] = StringHelper::truncate($task->fdName, 28);
                $temp['create'] = $task->fdCreate;
                $temp['update'] = $task->fdUpdate;
                $temp['progress'] = $task->fdProgress;
                $temp['categoryID'] = $task->fdTaskCategoryID;
                $temp['creator'] = UserService::factory()->getUserName($task->fdCreatorID);
                $temp['level'] = Yii::$app->params['taskLevel'][$task->fdLevel];
                $list[] = $temp;
            }

            ResponseUtil::jsonCORS([
                'total' => $total,
                'list'  => $list,
            ]);
        }
    }

    /**
     * 个人任务数
     * @since 2018-01-25
     */
    public function actionStatTasks()
    {
        $total = TaskService::factory()->countCompanyTasks($this->companyID, [
            'status'    => Conf::ENABLE,
            'asArray'   => true,
            'creatorID' => Yii::$app->user->id,
            'select'    => ['count(id)']
        ]);

        $complete = TaskService::factory()->countCompanyTasks($this->companyID, [
            'status'    => Conf::ENABLE,
            'asArray'   => true,
            'progress'  => Conf::TASK_FINISH,
            'creatorID' => Yii::$app->user->id,
            'select'    => ['count(id)']
        ]);

        ResponseUtil::jsonCORS([
            'total'    => $total,
            'complete' => $complete
        ]);
    }

    /**
     * 普通成员认证
     * @since 2018-01-18
     */
    public function actionAuth()
    {
        $auth = Yii::$app->request->get('auth');
        $email = Yii::$app->request->get('email');

        if (!$auth || !$email) {
            $this->redirectMsgBox(['user/login'], '链接无效');
        }

        $user = UserService::factory()->getUserObjByAccount($email);
        if (!$user) {
            $this->redirectMsgBox(['user/login'], '链接无效');
        }

        if ($auth != Yii::$app->security->generatePasswordHash($user->fdLogin)) {
            $this->redirectMsgBox(['user/login'], '链接无效');
        }

        $user->fdStatus = Conf::USER_ENABLE;
        $user->fdVerify = date('Y-m-d H:i:s');
        $res = $user->update();

        if (!$res) {
            $this->redirectMsgBox(['user/login'], '认证失败');
        }

        // 验证成功，执行登录
        if (UserService::factory()->login($user)) {
            $this->redirectMsgBox(['default/index'], $user->fdName . ' 欢迎你...');
        } else {
            $this->redirectMsgBox(['user/login'], '验证成功，请登录...');
        }
    }

    /**
     * 检测用户可操作权限
     * @param $userID
     * @return null|User
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    private function _checkUserAccess($userID)
    {
        $user = User::findOne(['id' => $userID, 'fdStatus' => Conf::USER_ENABLE]);
        if (!$user) {
            throw new NotFoundHttpException('用户不存在或已删除');
        }
        if ($user->fdCompanyID != $this->companyID) {
            throw new ForbiddenHttpException('这不是你公司的成员，你无权查看他的信息');
        }
        return $user;
    }
}
