<?php
namespace dvizh\filter\assets;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

    public $css = [
        'css/backend.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
