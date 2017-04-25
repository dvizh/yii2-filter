<?php

namespace dvizh\filter\models;

use yii;

class FilterVariant extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'images' => [
                'class' => 'dvizh\gallery\behaviors\AttachImages',
                'mode' => 'single',
            ],
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
                'slugAttribute' => 'latin_value',
                'attribute' => 'value',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%filter_variant}}';
    }

    public function rules()
    {
        return [
            [['filter_id'], 'required'],
            [['filter_id', 'numeric_value'], 'integer'],
            [['value', 'latin_value'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filter_id' => 'Фильтр',
            'value' => 'Значение',
            'numeric_value' => 'Числовое значение',
        ];
    }

    public static function saveEdit($id, $name, $value)
    {
        $setting = FilterVariant::findOne($id);
        $setting->$name = $value;
        $setting->save();
    }

    public function beforeSave($insert)
    {
        if(empty($this->numeric_value)) {
            $this->numeric_value = (int)$this->value;
        }

        return parent::beforeSave($insert);
    }
}
