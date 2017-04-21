<?php
namespace dvizh\filter\assets;

use yii\web\AssetBundle;

class FrontendAsset extends AssetBundle
{
    public $depends = [
        'dvizh\filter\assets\Asset'
    ];

    public $js = [
        'js/frontend.js',
    ];

    public $css = [
        'css/styles.css',
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/../web';
        parent::init();
    }

}
