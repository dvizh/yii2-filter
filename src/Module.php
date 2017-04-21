<?php
namespace dvizh\filter;

use yii;

class Module extends \yii\base\Module
{
    public $relationFieldName = null;
    public $relationFieldValues = [];
    public $relationFieldValuesCallback = '';
    public $types = ['radio' => 'Radio', 'checkbox' => 'Checkbox', 'select' => 'Select', 'range' => 'Промежуток'];
    public $adminRoles = ['superadmin', 'admin'];

    public function init()
    {
        if(is_callable($this->relationFieldValues)) {
            $values = $this->relationFieldValues;
            $this->relationFieldValues = $values();
        }
        
        parent::init();
    }
}
