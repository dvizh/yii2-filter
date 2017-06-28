<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="filter-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6">
            <div class="relationModels">
                <?= $form->field($model, 'relation_field_value')->checkboxList(
                        Yii::$app->getModule('filter')->relationFieldValues) ?>
            </div>

            <?= $form->field($model, 'relation_field_name')->hiddenInput(['value' => Yii::$app->getModule('filter')->relationFieldName])->label(false); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'slug')->textInput(['label' => 'Идентификатор']) ?>
            <?= $form->field($model, 'sort')->textInput() ?>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'is_option')->dropdownList(['no' => 'Нет', 'yes' => 'Да']) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'is_filter')->dropdownList(['no' => 'Нет', 'yes' => 'Да']) ?></div>
            </div>
            <?= $form->field($model, 'type')->dropdownList(Yii::$app->getModule('filter')->types)->label('Тип фильтрации на сайте') ?>
            <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>
            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
.relationModels label {
    display: block;
}
</style>