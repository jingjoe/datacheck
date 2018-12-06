<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบไม่ลงผลวินิจฉัย IPD รายบุคคล';
$this->params['breadcrumbs'][] = ['label' => 'แฟ้ม DIAGNOSIS_IPD', 'url' => ['/diagnosisipd/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><?= Html::encode($this->title) ?> ณ.วันที่ <?= $date_start ?> ถึง <?= Yii::$app->formatter->asDate('now', 'php:Y-m-d'); ?></h3>
                <h6><p><font color="red">เงื่อนไขการตรวจสอบแฟ้ม DIAGNOSIS_IPD ไม่ลงผลวินิจฉัย IPD ใช้ตาราง l_icd10tm อ้างรหัสโรค ICD-10-TM 2016 เริ่มใช้ มกราคม 2560 </font>
                    <?= Html::a('ดาวน์โหลด', ['/diagnosisipd/download']) ?></p></h6>
                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
              </div>
            </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('แฟ้ม DIAGNOSIS_IPD', ['/help/diagnosisipd'], ['class'=>'btn  btn-success btn-flat']) ?>
            <?= Html::a('การบันทึกข้อมูล', ['/help/diagnosisipdkey'], ['class'=>'btn  btn-warning btn-flat']) ?>
        </div>

    <!-- dialog sql -->
        <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target=".bs-example-modal-lg">ชุดคำสั่ง SQL</button>
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">SQL : <?= Html::encode($this->title) ?> </h4>
                    </div>
                    <div class="modal-body"> <?= $sql ?> </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-flat">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<br><br>    
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'F43_Diagnosisipd_Detail1_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'F43_Diagnosisipd_Detail1_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'F43_Diagnosisipd_Detail1_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'F43_Diagnosisipd_Detail1_'.date('Y-d-m')],
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
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'attribute' => 'hn',
            'header' => 'HN'
        ],
        [
            'attribute' => 'full_name',
            'header' => 'ชื่อ-นามสกุล'
        ],
        [
            'attribute' => 'an',
            'header' => 'AN'
        ],
        [
            'attribute' => 'dchdate',
            'header' => 'วันจำหน่าย'
        ],
        [
            'attribute' => 'ward_name',
            'header' => 'หอผู้ป่วย'
        ],
        [
            'attribute' => 'pdx',
            'header' => 'Dx'
        ],
        [
            'attribute' => 'doc_name',
            'header' => 'ผู้ให้บริการ'
        ],
        [
            'attribute' => 'err',
            'header' => 'ERROR'
        ],
    ]
]);
  ?>
            </div>
              <br> 
          </div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>