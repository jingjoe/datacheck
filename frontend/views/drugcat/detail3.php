<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'รายการยาในโปรแกรม HOSxP ที่ยังไม่ถูกเพิ่มไว้ใน Drugcatalogue';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบรหัสยา TMT', 'url' => ['/drugcat/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="panel panel-default">
    <div class="box-header">
        <h3 class="box-title"><i class="fa  fa-file-text"></i>  <?= $this->title; ?></h3>
    </div>
    <div class='box-tools'>
            <?php $form = ActiveForm::begin([
                        'layout' => 'horizontal',
                        'action' => ['detail3'],
                        'method' => 'get',
            ]);
            ?>
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="ระบุชื่อยาใน รพ..">
                <span class="input-group-btn">
                    <button class="btn btn-info btn-flat" type="submit">ค้นหา<i class="fa fa-fw fa-search"></i></button>
                </span>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
<br>
    <div class="box-body no-padding">
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Drugitems on HOSxP_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Drugitems on HOSxP_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Drugitems on HOSxP_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Drugitems on HOSxP_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['detail3'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'class' => 'kartik\grid\SerialColumn'
                ],
                [
                    'attribute' => 'icode',
                    'header' => 'ICODE'
                ],
                [
                    'attribute' => 'drug',
                    'header' => 'ชื่อยา'
                ],
                [
                    'attribute' => 'units',
                    'header' => 'หน่วยนับ'
                ],
                [
                    'attribute' => 'did',
                    'header' => 'รหัสยา 24 หลัก'
                ],
                [
                    'attribute' => 'istatus',
                    'header' => 'สถานะ'
                ],
                [
                    'attribute' => 'lastupdatestdprice',
                    'header' => 'วันที่อับดท'
                ]
            ]
        ]);
        ?>
    </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>