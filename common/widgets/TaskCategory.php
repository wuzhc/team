<?php

namespace common\widgets;


use common\services\TaskService;
use Yii;
use yii\base\Widget;

class TaskCategory extends Widget
{

    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public function run()
    {
        $me = Yii::$app->request->get('me', 0);
        $categoryID = Yii::$app->request->get('categoryID');
        $labelID = Yii::$app->request->get('labelID');
        $projectID = Yii::$app->request->get('projectID');

        return $this->render('taskCategory', [
            'me'         => $me,
            'labelID'    => $labelID,
            'projectID'  => $projectID,
            'categoryID' => $categoryID,
            'labels'     => TaskService::factory()->getTaskLabels($projectID),
            'categories' => TaskService::factory()->getTaskCategories($projectID)
        ]);
    }
}