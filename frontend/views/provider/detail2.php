<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจข้อมูลผู้ให้บริการของสถานพยาบาล เป็นรายบุคคลทั้งหมด';
$this->params['breadcrumbs'][] = ['label' => 'แฟ้ม PROVIDER', 'url' => ['/provider/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
               <h6><p><font color="red">เงื่อนไขการตรวจข้อมูลผู้ให้บริการของสถานพยาบาล เป็นรายบุคคล อ้างอิงแฟ้ม PROVIDER  (รหัสประเภทบุคลากร
                                        01=แพทย์ 02=ทันตแพทย์ 03=พยาบาลวิชาชีพ (ที่ทำหน้าที่ตรวจรักษา)04=เจ้าพนักงานสาธารณสุขชุมชน 05=นักวิชาการสาธารณสุข
                                        06=เจ้าพนักงานทันตสาธารณสุข 07=อสม.(ผู้ให้บริการในชุมชน)08=บุคลากรแพทย์แผนไทย แพทย์พื้นบ้าน แพทย์ทางเลือก (ที่มีวุฒิการศึกษาหรือผ่านการอบรมตามเกณฑ์) 09=อื่นๆ)
                                        ,(รหัสสภาวิชาชีพ 01=แพทยสภา,02=สภาการพยาบาล,03=สภาเภสัชกรรม,04=ทันตแพทยสภา,
                                        05=สภากายภาพบำบัด,06=สภาเทคนิคการแพทย์,07=สัตวแพทยสภา)</font></p></h6>
                <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
              </div>
            </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('แฟ้ม PROVIDER', ['/help/provider'], ['class'=>'btn  btn-success btn-flat']) ?>
            <?= Html::a('การบันทึกข้อมูล', ['/help/providerkey'], ['class'=>'btn  btn-warning btn-flat']) ?>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'F43_Provider_Detail2_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'F43_Provider_Detail2_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'F43_Provider_Detail2_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'F43_Provider_Detail2_'.date('Y-d-m')],
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
            'attribute' => 'code',
            'header' => 'รหัส'
        ],
        [
            'attribute' => 'doc_name',
            'header' => 'ชื่อ-นามสกุล'
        ],
        [
            'attribute' => 'birth',
            'header' => 'วันเกิด'
        ],
        [
            'attribute' => 'startdate',
            'header' => 'เริ่มงาน'
        ],
        [
            'attribute' => 'update_datetime',
            'header' => 'วันที่อับเดท'
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