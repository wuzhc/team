<?php

namespace frontend\controllers;


use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

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
        } elseif (false === strpos($query, 'showbox')) {
            $url .= '&showmsg=1';
        }

        Yii::$app->session->set('showbox', json_encode(array(
            'msg' => $msg,
            'seconds' => $seconds
        )));

        $this->redirect($url);
    }
}