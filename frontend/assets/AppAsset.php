<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * 加载js文件
     * @param \yii\web\View $view
     * @param $view
     * @param string $jsFile 当前应用js文件，注意不要加baseUrl
     * e.g. public/js/upload-page.js 相当于 http://shop.cm/public/js/upload-page.js
     * @author wuzhc
     * @since 2018-01-15
     */
    public static function registerJsFile($view, $jsFile)
    {
        $view->registerJsFile(
            Yii::$app->urlManager->baseUrl . '/' . $jsFile,
            [AppAsset::className(), 'depends' => 'frontend\assets\AppAsset']
        );
    }
}
