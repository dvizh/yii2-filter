<?php
namespace dvizh\filter;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
	return true;
	    
	if (php_sapi_name() == "cli")
		return true;
	    
        $app->get('assetManager')->bundles['yii2mod\slider\IonSliderAsset'] =
                [
                    'css' => [
                        'css/normalize.css',
                        'css/ion.rangeSlider.css',
                        'css/ion.rangeSlider.skinNice.css'
                     ]
                ];
    }
}
