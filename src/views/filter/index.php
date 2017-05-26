<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Фильтры и опции';
$this->params['breadcrumbs'][] = $this->title;

\dvizh\filter\assets\Asset::register($this);
?>
<div class="filter-index">
    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="tabs row">
        <div class="col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li <?php if($tab == 'filters') { ?>class="active"<?php } ?>><a href="<?=Url::toRoute(['/filter/filter/index', 'tab' => 'filters']);?>">Фильтры</a></li>
                <li <?php if($tab == 'options') { ?>class="active"<?php } ?>><a href="<?=Url::toRoute(['/filter/filter/index', 'tab' => 'options']);?>">Опции</a></li>
            </ul>
        </div>
    </div>

    <div class="info-block">
        <?php if($tab == 'filters') { ?>
            <p class="bg-info">По фильтрам покупатели выбирают подходящий по характеристикам товар.</p>
        <?php } else { ?>
            <p class="bg-info">Опции - это характеристики, которые участвуют в формировании модификации и могут влиять на цену товара при выборе определенной комбинации этих характеристик.</p>
        <?php } ?>
    </div>

    <?= \kartik\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'slug',
            [
                'attribute' => 'category',
                'label' => 'Категория',
                'content' => function($model) {
                    $return = [];
                    foreach($model->relation_field_value as $category) {
                        $fieldValues = Yii::$app->getModule('filter')->relationFieldValues;
                        if(isset($fieldValues[$category])) {
                            $return[] = $fieldValues[$category];
                        }
                    }
                    
                    return implode(', ', $return);
                },
                'filter' => false,
            ],
            [
                'attribute' => 'type',
                'content' => function($model) {
                    if($model->type == 'checkbox') {
                        return 'Много вариантов';
                    } elseif($model->type == 'radio') {
                        return 'Один вариант';
                    }
                },
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'type',
                    yii::$app->getModule('filter')->types,
                    ['class' => 'form-control', 'prompt' => 'Тип']
                )
            ],
            'description',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
        ],
    ]); ?>

</div>
