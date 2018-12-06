<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Icd10 */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="icd10-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'icd10')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'descriptions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'valid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icd10who')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icd10tm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icd10tmd')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
