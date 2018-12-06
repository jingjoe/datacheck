<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบข้อมูลการลงนัดหมายผู้ป่วย วันที่นัดน้อยกว่าหรือเท่ากับวันที่มารับบริการ เป็นรายบุคคล Error  S14 : วันนัดครั้งต่อไป DTAppoint ไม่ถูกต้อง';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม', 'url' => ['/ssop/index']];
$this->params['breadcrumbs'][] = ['label' => 'Error  S14 : วันนัดครั้งต่อไป DTAppoint ไม่ถูกต้อง', 'url' => ['/ssop/rep04','y' => $y,'m' => $m]];
$this->params['breadcrumbs'][] = $this->title;
?>
<br><br>
<div class="box box-success">
    <div class="box-header with-border">
          <h3 class="box-title"><?= Html::encode($this->title) ?> ข้อมูล ปี  <?= $y ?> เดือน <?= $m ?></h3>
                <h6><p><font color="red">เงื่อนไขการตรวจสอบข้อมูล อ้างอิง โครงสร้างและรูปแบบของข้อมูลผู้ป่วยนอก สิทธิประกันสังคม Version 0.93 (OPD-SS 20171123) </font></p></h6>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
          </div>
    </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('การตรวจสอบและการบันทึกข้อมูล SSOP', ['/help/s14'], ['class'=>'btn  btn-warning btn-flat']) ?>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Error_S14_Detail05_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Error_S14_Detail05_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Error_S14_Detail05_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Error_S14_Detail05_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rep05detail','dcode' => $dcode,'y' => $y,'m' => $m], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'attribute' => 'pttype',
                    'header' => 'สิทธิการรักษา'
                ],
                [
                    'attribute' => 'vstdate',
                    'header' => 'วันที่รับบริการ'
                ],
                [
                    'attribute' => 'nextdate',
                    'header' => 'วันนัดครั้งต่อไป'
                ],
                [
                    'attribute' => 'dep',
                    'header' => 'แผนก'
                ],
                [
                    'attribute' => 'clinic',
                    'header' => 'คลินิก'
                ],
                [
                    'attribute' => 'end_process',
                    'header' => 'วันที่ประมวลผล'
                ],
            ]
        ]);
        ?>
    </div>
      </div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>