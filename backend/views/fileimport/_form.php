<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

// Add upload
use yii\helpers\Url;
use kartik\widgets\TypeaheadBasic;
use kartik\widgets\FileInput;
use yii\helpers\VarDumper;


/* @var $this yii\web\View */
/* @var $model backend\models\Fileimport */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fileimport-form">


    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
                'options' => ['accept' => '.xls,.xlsx'],
                'pluginOptions' => [
                    'initialPreview' => empty($model->file) ? [] : [
                        Yii::getAlias('@web') . '/drugcat/' . $model->file,
                            ],
                    'allowedFileExtensions'=>['xls','xlsx'],
                    'showPreview' => false,
                    'showCaption' => true,
                    'showRemove' => true,
                    'showUpload' => false
                ]
            ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<i class="glyphicon glyphicon-upload"></i> อับโหลด' : '<i class="glyphicon glyphicon-repeat"></i> แก้ไข', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
