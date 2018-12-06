<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Fileimport */

$this->title = 'อับโหลดไฟล์ DrugCatalogue';
$this->params['breadcrumbs'][] = ['label' => 'ไฟล์ DrugCatalogue', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileimport-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
