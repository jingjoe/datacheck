<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use kartik\widgets\TimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\SysSetTime */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="sys-set-time-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-4 col-xs-12">
            <?php
            echo '<label class="control-label">วันที่</label>';
            echo DatePicker::widget([
                'model' => $model,
                'attribute' => 'date',
                'language' => 'th',
                'options' => ['placeholder' => 'ปี-เดือน-วัน'],
                'layout' => '{picker}{input}',
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                ]
            ]);
            ?>
        </div>
        <div class="col-md-8 col-xs-12">
            <?=
            $form->field($model, 'time')->widget(TimePicker::classname(), [
                'pluginOptions' => [
                    'showSeconds' => true,
                    'showMeridian' => false,
                    'minuteStep' => 1,
                    'secondStep' => 5,
                ]
            ])
            ?>
        </div>
    </div>
    <?= $form->field($model, 'days')->textInput();?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn  btn-success btn-flat' : 'btn  btn-primary btn-flat']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
