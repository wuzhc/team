<?php

namespace frontend\controllers;

use common\config\Conf;
use common\services\CommonService;
use common\services\UserService;
use common\utils\ResponseUtil;
use common\utils\VerifyUtil;
use Yii;
use common\models\Company;
use common\models\CompanySearch;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CompanyController implements the CRUD actions for Company model.
 */
class CompanyController extends BaseController
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
                'rules' => [
                    [
                        'allow'         => true,
                        'matchCallback' => function ($rule, $action) {
                            // 登录检测
                            if (Yii::$app->user->isGuest) {
                                return false;
                            }
                            // 超级管理员检测
                            if (($action->id != 'import-user') && !Yii::$app->user->can('super')) {
                                return false;
                            }
                            return true;
                        }
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Company models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Company model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Company model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Company();

        if ($model->load(Yii::$app->request->post())) {
            $model->fdStatus = Conf::ENABLE;
            $model->fdCreate = date('Y-m-d H:i:s');
            $model->fdUpdate = date('Y-m-d H:i:s');
            $model->fdCreatorID = Yii::$app->user->id;

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $msg = '';
                foreach ($model->getErrors() as $key => $errors) {
                    $msg = $errors[0];
                    break;
                }
                $this->redirectMsgBox(['create'], $msg);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Company model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->fdUpdate = date('Y-m-d H:i:s');

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                $msg = '';
                foreach ($model->getErrors() as $key => $errors) {
                    $msg = $errors[0];
                    break;
                }
                $this->redirectMsgBox(['update'], $msg);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Company model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $this->redirectMsgBox(['index'], '删除成功');
    }

    /**
     * Finds the Company model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Company the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Company::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 导入成员
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionImportUser()
    {
        // 权限检测，只有超级管理员和普通管理员才能执行
        if (!Yii::$app->user->can('importUser')) {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }

        if (Yii::$app->request->isAjax && ($data = Yii::$app->request->post())) {
            if (!$data['accounts']) {
                ResponseUtil::jsonCORS(['status' => 0, 'msg' => '账号不能为空']);
            }

            $exists = []; // 已存在的账号
            $news = []; // 新导入用户
            $accounts = array_unique(explode(',', $data['accounts']));

            foreach ($accounts as $account) {
                // 账号已经存在
                if (UserService::factory()->getUserObjByAccount($account)) {
                    $exists[] = $account;
                    continue;
                }

                $temp = [
                    'phone' => '',
                    'email' => '',
                    'login' => 't_' . VerifyUtil::getRandomCode(8, 3)
                ];

                if (VerifyUtil::checkPhone($account)) {
                    $temp['phone'] = $account;
                } elseif (VerifyUtil::checkEmail($account)) {
                    $temp['email'] = $account;
                } else {
                    $temp['login'] = $account;
                }

                $news[] = $temp;
            }

            // 成员入库
            if ($news) {
                $res = UserService::factory()->batchCreateUser($news);
                if (!$res) {
                    ResponseUtil::jsonCORS([
                        'status' => 0,
                        'exists' => $exists
                    ]);
                }
            }

            ResponseUtil::jsonCORS([
                'status' => 1,
                'exists' => $exists
            ]);
        }

        return $this->render('import-user');
    }
}
