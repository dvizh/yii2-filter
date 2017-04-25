<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

if($model->isNewRecord) {
?>
    <div class="filter-variant-form-add">

        <?php $form = ActiveForm::begin(['action' => ['/filter/filter-variant/create']]); ?>

        <?= $form->field($model, 'filter_id')->hiddenInput()->label(false); ?>

        <div class="form-group field-filter-name required">
            <textarea placeholder="Каждый вариант с новой строки" required name="list" class="form-control" style="width: 400px; height: 160px;" placeholder=""></textarea>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Добавить список', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
} else {
?>
    <div class="filter-variant-form">

        <?php $form = ActiveForm::begin(['action' => ['/filter/filter-variant/update', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'filter_id')->hiddenInput()->label(false); ?>

        <?= $form->field($model, 'value')->textInput(['placeholder' => 'Каждый вариант с новой строки']); ?>

        <?=\dvizh\gallery\widgets\Gallery::widget(['model' => $model]); ?>
        
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php } ?>

