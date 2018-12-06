<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'รายการยาทั้งหมดจากไฟล์ Drugcatalogue';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบรหัสยา TMT', 'url' => ['/drugcat/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<?php foreach ($excel as $excelfile) { ?>
<?php } ?>   
<div class="panel panel-default">
    <div class="box-header">
        <h3 class="box-title"><i class="fa  fa-file-text"></i>  <?= $this->title; ?>
            ชื่อไฟล์ <?php if (empty( $excelfile['file_excel'] )) {
                            echo"-";
                        }
                        else {
                            echo $excelfile['file_excel'];
                        }
                  ?>

            วันนำเข้าข้อมูล <?php if (empty( $excelfile['date_import'] )) {
                            echo"-";
                        }
                        else {
                            echo $excelfile['date_import'];
                        }
                  ?>
    </div>
    <div class='box-tools'>
            <?php $form = ActiveForm::begin([
                        'layout' => 'horizontal',
                        'action' => ['detail1'],
                        'method' => 'get',
            ]);
            ?>
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" placeholder="ระบุชื่อสามัญ..">
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
                //'heading' => '<h3 class="panel-title"><i class="fa fa-file-excel-o"></i>ชื่อไฟล์ </h3>',
                'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
                'footer'=>false
            ],
            'responsive' => true,
            'hover' => true,
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'DrugcatalogueApproved_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'DrugcatalogueApproved_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'DrugcatalogueApproved_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'DrugcatalogueApproved_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['detail1'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'attribute' => 'hospdrugcode',
                    'header' => 'รหัสยาหน่วยบริการ'
                ],
                [
                    'attribute' => 'tmtid',
                    'header' => 'รหัส TMT'
                ],
                [
                    'attribute' => 'genericname',
                    'header' => 'ชื่อสามัญ'
                ],
                [
                    'attribute' => 'trandename',
                    'header' => 'ชื่อยาทางการค้า'
                ],
                [
                    'attribute' => 'strength',
                    'header' => 'ขนาดความแรงยา'
                ],
                [
                    'attribute' => 'content',
                    'header' => 'ขนาดบรรจุ'
                ],
                [
                    'attribute' => 'unitprice',
                    'header' => 'ราคาขายต่อหน่วย'
                ],
                [
                    'attribute' => 'manufacturer',
                    'header' => 'บริษัทผู้ผลิต'
                ],
                [
                    'attribute' => 'ised',
                    'header' => 'ยาใน/นอกบัญชี'
                ],
                [
                    'attribute' => 'ndc24',
                    'header' => 'รหัสยา 24 หลัก'
                ],
                [
                    'attribute' => 'updateflag',
                    'header' => 'สถานะการปรับปรุง'
                ],
                [
                    'attribute' => 'dateeffective',
                    'header' => 'วันที่การปรับปรุงมีผล'
                ],
                [
                    'attribute' => 'datechange',
                    'header' => 'วันที่ปรับปรุง'
                ],
                [
                    'attribute' => 'dateupdate',
                    'header' => 'วันที่อับเดท'
                ],
                [
                    'attribute' => 'date_approved',
                    'header' => 'วันตรวจสอบ'
                ],
                [
                    'attribute' => 'date_import',
                    'header' => 'วันนำเข้าข้อมูล'
                ],
            ]
        ]);
        ?>
    </div> 
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>