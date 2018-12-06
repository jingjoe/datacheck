<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\History */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="history-form">

    <?php $form = ActiveForm::begin(); ?>
            <?php
            echo '<label class="control-label">วันที่บันทึก</label>';
            echo DateTimePicker::widget([
                'model' => $model,
                'attribute' => 'datetime',
                'language' => 'th',
                'options' => ['placeholder' => 'ปี-เดือน-วัน'],
                'layout' => '{picker}{input}',
                'pluginOptions' => [
                    'todayHighlight' => true,
                    'todayBtn' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'autoclose' => true,
                ]
            ]);
            ?> 
            <?= $form->field($model, 'change')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'detail')->textInput(['maxlength' => true]) ?>
          


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ?  'btn  btn-success btn-flat' : 'btn  btn-primary btn-flat']) ?>
    </div>


    <?php ActiveForm::end(); ?>

</div>
