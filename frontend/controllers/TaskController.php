<?php

namespace frontend\controllers;


use yii\web\Controller;

class TaskController extends Controller
{
    public $layout = 'main-member';

    /**
     * 首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}