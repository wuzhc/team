<?php

namespace frontend\controllers;


use common\config\Conf;
use common\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * 基础控制器
 * Class BaseController
 * @package frontend\controllers
 * @author wuzhc <wuzhc2016@163.com>
 * @since 2018-01-15
 */
class BaseController extends Controller
{
    protected $companyID;

    /**
     * @throws ForbiddenHttpException
     */
    public function init()
    {
        if (!Yii::$app->user->isGuest) {
            $this->companyID = Yii::$app->user->identity->fdCompanyID;
            if (empty($this->companyID)) {
                throw new ForbiddenHttpException('禁止访问');
            }
        }
    }

    /**
     * 检测指定用户可操作权限
     * @param $userID
     * @return null|User
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    protected function checkUserAccess($userID)
    {
        $user = User::findOne(['id' => $userID, 'fdStatus' => Conf::USER_ENABLE]);
        if (!$user) {
            throw new NotFoundHttpException('用户不存在或已删除');
        }
        if ($user->fdCompanyID != $this->companyID) {
            throw new ForbiddenHttpException('这不是你公司的成员，禁止此次操作');
        }
        return $user;
    }

    /**
     * 跳转地址并弹窗提示信息
     * @param array  $url 跳转URL
     * @param string $msg 提示信息
     * @param int    $seconds 弹窗消失时间
     * @since 2018-01-15
     */
    public function redirectMsgBox(array $url, $msg = '弹窗提示', $seconds = 2000)
    {
        if (!is_array($url)) {
            Yii::$app->end('Url must be array');
        }

        $url = Url::to($url);
        $query = parse_url($url, PHP_URL_QUERY);
        if (!$query) {
            $url .= '?showmsg=1';
        } elseif (false === strpos($query, 'showmsg')) {
            $url .= '&showmsg=1';
        }

        Yii::$app->session->set('showbox', json_encode(array(
            'msg' => $msg,
            'seconds' => $seconds
        )));

        $this->redirect($url);
    }
}