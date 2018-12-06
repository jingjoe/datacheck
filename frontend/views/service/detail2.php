<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบไม่ซักประวัติหรือไม่ระบุอาการสำคัญ CC ของผู้ป่วยแต่ละ Visit';
$this->params['breadcrumbs'][] = ['label' => 'แฟ้ม SERVICE', 'url' => ['/service/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><?= $this->title; ?> ข้อมูล ณ.วันที่ <?= $date_start ?> ถึง <?= $date_end ?></h3>
                    <h6><p><font color="red">เงื่อนไขการตรวจสอบแฟ้ม SERVICE ไม่ระบุอาการสำคัญ CC ตรวจสอบจากตาราง opdscreen และ ตาราง opd_regist_sendlist</font></p></h6>
                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
              </div>
            </div>
              <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('แฟ้ม SERVICE', ['/help/service'], ['class'=>'btn  btn-success btn-flat']) ?>
            <?= Html::a('การบันทึกข้อมูล', ['/help/servicekey'], ['class'=>'btn  btn-warning btn-flat']) ?>
        </div>

    <!-- dialog sql -->
        <button type="button" class="btn btn-danger btn-flat" data-toggle="modal" data-target=".bs-example-modal-lg">ชุดคำสั่ง SQL</button>
        <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="gridSystemModalLabel">SQL : <?= $this->title; ?> </h4>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'F43_Service_Detail2_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'F43_Service_Detail2_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'F43_Service_Detail2_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'F43_Service_Detail2_'.date('Y-d-m')],
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
            'class' => 'yii\grid\SerialColumn'
        ],
        [
            'attribute' => 'deproom',
            'header' => 'แผนก'
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
            'attribute' => 'datetime',
            'header' => 'วันรับบริการ'
        ],
        [
            'attribute' => 'cc',
            'header' => 'อาการสำคัญ CC'
        ],
        [
            'attribute' => 'sendby',
            'header' => 'ผู้ส่งตรวจ'
        ],
        [
            'attribute' => 'depsend',
            'header' => 'ส่งจาก'
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