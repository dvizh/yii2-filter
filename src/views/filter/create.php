<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Создание фильтра';
$this->params['breadcrumbs'][] = ['label' => 'Фильтры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="filter-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
