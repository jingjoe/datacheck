<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบข้อมูลการลงบันทึกบริการ หมวด 11 ค่าทำหัตถการและวิสัญญีแต่ไม่ลงหัตถการหรือบริการไม่เชื่อมกับหัตถการ เป็นรายบุคคล Error S41 : ใส่ class ใน Opservices เป็นหัตถการ ต้องใส่รหัสหัตถการที่ Opservices';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม', 'url' => ['/ssop/index']];
$this->params['breadcrumbs'][] = ['label' => 'Error S41 : ใส่ class ใน Opservices เป็นหัตถการ ต้องใส่รหัสหัตถการที่ Opservices', 'url' => ['/ssop/rep06','y' => $y,'m' => $m]];
$this->params['breadcrumbs'][] = $this->title;
?>
<br><br>
<div class="box box-success">
    <div class="box-header with-border">
          <h3 class="box-title"><?= Html::encode($this->title) ?> ข้อมูล ปี  <?= $y ?> เดือน <?= $m ?></h3>
                <h6><p><font color="red">เงื่อนไขการตรวจสอบข้อมูล อ้างอิง โครงสร้างและรูปแบบของข้อมูลผู้ป่วยนอก สิทธิประกันสังคม Version 0.93 (OPD-SS 20171123)</font> <font color="green">*แฟ้ม billtran ถ้า BillItems มีค่าใช้จ่ายหมวด E แฟ้ม opservice ต้องมี class IN ต้องมี icd9 ออกมาด้วย </font></p></h6>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
          </div>
    </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('การตรวจสอบและการบันทึกข้อมูล SSOP', ['/help/s41'], ['class'=>'btn  btn-warning btn-flat']) ?>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Error_S41_Detail07_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Error_S41_Detail07_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Error_S41_Detail07_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Error_S41_Detail07_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rep07detail','dcode' => $dcode,'y' => $y,'m' => $m], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'attribute' => 'pttype_name',
                    'header' => 'สิทธิการรักษา'
                ],
                [
                    'attribute' => 'vstdate',
                    'header' => 'วันที่'
                ],
                [
                    'attribute' => 'vsttime',
                    'header' => 'เวลา'
                ],
                [
                    'attribute' => 'nondrug_name',
                    'header' => 'ค่าบริการ'
                ],
                [
                    'attribute' => 'last_modified',
                    'header' => 'วันที่อับเดท'
                ],
            ]
        ]);
        ?>
    </div>
      </div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>