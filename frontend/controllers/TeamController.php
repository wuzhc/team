<?php

namespace frontend\controllers;


use yii\web\Controller;

class TeamController extends Controller
{
    public $layout = 'main-member';

    public function actionIndex()
    {
        return $this->render('index');
    }
}