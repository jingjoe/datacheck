<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ตั้งเวลาประมวลผล';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sys-set-time-index">

   

    <p>
        <?= Html::a('ตั้งเวลาประมวลผล', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </p>
  <div class="box">
        <div class="box-header with-border">
            <h3><?= Html::encode($this->title) ?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
            </div>
        </div>
        <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            'time',
            'days',
            ['class' => 'yii\grid\ActionColumn', 
                'template' => '{view}<span class="glyphicon glyphicon-option-vertical"></span>{update}<span class="glyphicon glyphicon-option-vertical"></span>{delete}'
            ],
        ],
    ]); ?>

</div>
        </div>
    </div>
 
   <?= \bluezed\scrollTop\ScrollTop::widget() ?>