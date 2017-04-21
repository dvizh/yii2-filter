<?php
namespace dvizh\filter\models;

use yii;
use dvizh\filter\models\Filter;

class FieldRelationValue extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%filter_relation_value}}';
    }

    public function rules()
    {
        return [
            [['filter_id'], 'required'],
            [['filter_id', 'value'], 'integer'],
        ];
    }

    public function getFilters()
    {
        return $this->hasOne(Filter::className(), ['id' => 'filter_id']);
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filter_id' => 'Фильтр',
            'value' => 'Значение',
        ];
    }
}
