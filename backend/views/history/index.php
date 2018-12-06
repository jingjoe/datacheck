<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\HistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ประวัติการปรับปรุง';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="history-index">

    <p>
        <?= Html::a('เพิ่มประวัติ', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </p>
    <div class="box">
        <div class="box-header with-border">
            <h3><?= Html::encode($this->title) ?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
            </div>
        </div>
        <div class="box-body">
            <?php Pjax::begin(); ?>    
            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                // 'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'datetime',
                    'change',
                    ['class' => 'yii\grid\ActionColumn',
                        'template' => '{view}<span class="glyphicon glyphicon-option-vertical"></span>{update}<span class="glyphicon glyphicon-option-vertical"></span>{delete}'
                    ],
                ],
            ]);
            ?>
<?php Pjax::end(); ?></div>
    </div>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>