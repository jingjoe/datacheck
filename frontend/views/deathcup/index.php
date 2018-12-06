<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

// add pop up windows form
use yii\bootstrap\Modal;
use yii\helpers\Url;


$this->title = 'ข้อมูลการตาย จากทะเบียนราษฎร์';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="deathcup-index">

<?php Pjax::begin(); ?>    
        <?=GridView::widget([
            'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
            'headerRowOptions' => ['style' => 'background-color:#cccccc'],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
                'footer'=>false
            ],
            'responsive' => true,
            'hover' => true,
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Deathcup2560_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Deathcup2560_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Deathcup2560_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Deathcup2560_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
                ],
                '{toggleData}',
                '{export}',
            ],
        // set export properties
            'export' => [
                'fontAwesome' => true
            ],
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'beforeGrid' => '',
                'afterGrid' => '',
            ],
            'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'hospcode',
            'cid',
            'fname',
            'lname',
            //'sex',
            //'age',
            'death_d',
            'death_m',
            'death_y',
            'cause_death',
            'death_place',
            //'address_death',
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>