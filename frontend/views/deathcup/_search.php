<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeathcupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
        ]);
?>
<div class="form-group">
    <?= $form->field($model, 'cid')->widget(\yii\widgets\MaskedInput::className(), ['mask' => '9-9999-99999-99-9',]) ?> 
</div>
<div class="form-group">
    <?= Html::submitButton('<i class="glyphicon glyphicon-search"></i> ค้นหา', ['class' => 'btn btn-primary']) ?>  </li> 
</div>        
<?php ActiveForm::end(); ?>

