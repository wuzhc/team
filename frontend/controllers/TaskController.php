<?php
/**
 * Created by PhpStorm.
 * User: wuzc
 * Date: 18-1-11
 * Time: 下午10:47
 */

namespace frontend\controllers;


use yii\web\Controller;

class TaskController extends Controller
{
    public $layout = 'main-member';

    public function actionIndex()
    {
        return $this->render('index');
    }
}