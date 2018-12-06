<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\SysSetTime */

$this->title = 'ตั้งวันที่เวลา';
$this->params['breadcrumbs'][] = ['label' => 'ตั้งวันที่เวลา', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-set-time-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
