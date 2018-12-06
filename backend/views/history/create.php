<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\History */

$this->title = 'ประวัติการปรับปรุงโปรแกรม';
$this->params['breadcrumbs'][] = ['label' => 'บันทึกการปรับปรุงโปรแกรม', 'url' => ['index']];

?>
<div class="history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
