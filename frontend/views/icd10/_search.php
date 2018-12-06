<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\Icd10Search */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="icd10-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'icd10') ?>

    <?= $form->field($model, 'descriptions') ?>

    <?= $form->field($model, 'valid') ?>

    <?= $form->field($model, 'icd10who') ?>

    <?php // echo $form->field($model, 'icd10tm') ?>

    <?php // echo $form->field($model, 'icd10tmd') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
