<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\SysSetTime */

$this->title = 'แก้ไขวันที่เวลา : ' . ' ' . $model->date. ' '.'เวลา : ' . ' ' . $model->time;
$this->params['breadcrumbs'][] = ['label' => 'ตั้งวันที่เวลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'แก้ไขวันที่เวลา';
?>
<div class="sys-set-time-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
