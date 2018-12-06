<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบรายการยาในโปรแกรม HOSxP ที่ตรงกับ รายการยาจากไฟล์ Drugcatalogue';
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
                        'action' => ['detail2'],
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
            'beforeHeader'=>[
                [
                    'columns'=>[
                        ['content'=>'', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
                        ['content'=>'จากโปรแกรม HOSxP', 'options'=>['colspan'=>5, 'class'=>'text-center warning']], 
                        ['content'=>'จากฐาน Drugcatalogue', 'options'=>['colspan'=>7, 'class'=>'text-center default']], 
                    ],
                    'options'=>['class'=>'skip-export'] // remove this row from export
                ]
            ],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
                'footer'=>false
            ],
            'responsive' => true,
            'hover' => true,
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Drugcat and Drugitems_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Drugcat and Drugitems_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Drugcat and Drugitems_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Drugcat and Drugitems_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['detail2'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'class' => 'kartik\grid\SerialColumn',
                    'contentOptions' => ['class'=>'text-center warning']  
                ],
                [
                    'attribute' => 'icode',
                    'header' => 'ICODE',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'drug',
                    'header' => 'ชื่อยา',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'units',
                    'header' => 'หน่วยนับ',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'did',
                    'header' => 'รหัสยา 24 หลัก',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'istatus',
                    'header' => 'สถานะ',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'tmtid',
                    'header' => 'รหัส TMT',
                    'contentOptions' => ['class'=>'text default']
                ],
                [
                    'attribute' => 'genericname',
                    'header' => 'ชื่อสามัญ',
                    'contentOptions' => ['class'=>'text default']
                ],
                [
                    'attribute' => 'ised',
                    'header' => 'ยาใน/นอกบัญชี',
                    'contentOptions' => ['class'=>'text default']
                ],
                [
                    'attribute' => 'updateflag',
                    'header' => 'สถานะการปรับปรุง',
                    'contentOptions' => ['class'=>'text default']
                ],
                 [
                    'attribute' => 'datechange',
                    'header' => 'วันที่ปรับปรุง',
                    'contentOptions' => ['class'=>'text default']
                ],
                 [
                    'attribute' => 'dateupdate',
                    'header' => 'วันที่อับเดท',
                    'contentOptions' => ['class'=>'text default']
                ],
            ]
        ]);
        ?>
    </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>