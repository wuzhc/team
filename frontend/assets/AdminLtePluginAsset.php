<?php

namespace frontend\assets;


use yii\web\AssetBundle;

class AdminLtePluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';
    public $js = [
        'bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js',
        // more plugin Js here
    ];
    public $css = [
        'bootstrap-wysihtml5/bootstrap3-wysihtml5.css',
        // more plugin CSS here
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];
}