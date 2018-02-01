<?php

namespace frontend\controllers;

use common\config\Conf;
use common\models\Project;
use common\services\LogService;
use common\services\MsgService;
use common\services\TaskService;
use common\utils\ResponseUtil;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class DefaultController extends BaseController
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
                'only'  => ['logout', 'signup', 'index', 'dynamic'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'dynamic'],
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 首页
     * @return mixed
     */
    public function actionIndex()
    {
        // 所有总数
        $records = TaskService::factory()->findTaskCriteria([
            'group'     => 'fdProjectID',
            'select'    => 'count(*) as total, fdProjectID as projectID',
            'companyID' => $this->companyID,
            'status'    => Conf::ENABLE
        ])->asArray()->all();

        $totalMap = [];
        foreach ($records as $record) {
            $totalMap[$record['projectID']] = $record['total'];
        }

        // 已完成总数
        $completeRecords = TaskService::factory()->findTaskCriteria([
            'group'     => 'fdProjectID',
            'select'    => 'count(*) as total, fdProjectID as projectID',
            'companyID' => $this->companyID,
            'status'    => Conf::ENABLE,
            'progress'  => Conf::TASK_FINISH
        ])->asArray()->all();

        $finishMap = [];
        foreach ($completeRecords as $record) {
            $finishMap[$record['projectID']] = $record['total'];
        }

        // 所有项目
        $projects = Project::find()->select(['id', 'fdName'])->where([
            'fdCompanyID' => $this->companyID,
            'fdStatus'    => Conf::ENABLE
        ])->all();

        return $this->render('index', [
            'projects'  => $projects,
            'totalMap'  => $totalMap,
            'finishMap' => $finishMap
        ]);
    }

    /**
     * 最新动态
     * TODO 需要实时更新最新动态
     */
    public function actionDynamic()
    {
        return $this->render('dynamic');
    }

    /**
     * 获取动态内容
     * @since 2018-01-25
     */
    public function actionGetDynamic()
    {
        ResponseUtil::jsonCORS([
            'rows' => LogService::factory()->getHandleLogs([
                'companyID' => $this->companyID
            ])
        ]);
    }

    /**
     * 获取消息通知
     * @since 2018-01-29
     */
    public function actionGetMsgHandle()
    {
        $args = [
            'isRead'     => Conf::MSG_UNREAD,
            'typeID'     => Conf::MSG_HANDLE,
            'receiverID' => Yii::$app->user->id,
        ];

        $total = MsgService::factory()->countMessages($args);
        $args['limit'] = 10;
        $data = MsgService::factory()->getMessages($args);

        ResponseUtil::json([
            'data' => $data,
            'total' => $total
        ]);
    }

    /**
     * 弹窗提示
     * @since 2018-01-15
     */
    public function actionShowBox()
    {
        $data = Yii::$app->session->get('showbox');
        $data = $data ? json_decode($data, true) : array();
        Yii::$app->session->set('showbox', null);
        ResponseUtil::jsonCORS($data);
    }
}
