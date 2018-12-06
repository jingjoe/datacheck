<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบข้อมูลไม่ลงผลวินิจฉัยผู้ป่วยใน DIAGNOSIS_IPD แบบแสดงรายละเอียด';
$this->params['breadcrumbs'][] = ['label' => 'รายงานไม่ลงผลวินิจฉัยผู้ป่วยผู้ป่วยใน DIAGNOSIS_IPD', 'url' => ['/reportcheck/rep2']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="panel panel-default">
      <div class="box-header with-border">
            <div class="panel-heading"> <h3 class="panel-title"><span class="glyphicon glyphicon glyphicon-signal"></span> <?= $this->title; ?>  ข้อมูลวันที่ <?=$date1 ?> ถึง <?=$date2 ?></h3> </div>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
            </div>
        </div>   
   <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'headerRowOptions' => ['style' => 'background-color:#cccccc'],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
                'footer'=>false
            ],
            'responsive' => true,
            'hover' => true,
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'ReportCheck2_Detail1_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'ReportCheck2_Detail1_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'ReportCheck2_Detail1_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'ReportCheck2_Detail1_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rep2detail','id'=>$id,'date1' => $date1, 'date2' => $date2], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                [
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'attribute' => 'ward_name',
                    'header' => 'ห่อผู้ป่วย'
                ],
                [
                    'attribute' => 'an',
                    'header' => 'AN'
                ],
                [
                    'attribute' => 'full_name',
                    'header' => 'ชื่อ-นามสกุล'
                ],
                [
                    'attribute' => 'datetime',
                    'header' => 'วันเวลาให้บริการ'
                ],
                [
                    'attribute' => 'prediag',
                    'header' => 'อาการ'
                ],
                [
                    'attribute' => 'doc_name',
                    'header' => 'ผู้ให้บริการ'
                ],
            ]
        ]);
        ?>
    </div>
<br> 

<?= \bluezed\scrollTop\ScrollTop::widget() ?>