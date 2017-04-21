<?php
namespace dvizh\filter\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\filter\models\FilterVariant;


class FilterVariantSearch extends FilterVariant
{
    public function rules()
    {
        return [
            [['id', 'filter_id'], 'integer'],
            [['value'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FilterVariant::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'filter_id' => $this->filter_id,
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
