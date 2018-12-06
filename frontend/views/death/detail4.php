<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบข้อมูลสาเหตุการตายที่ไม่ทราบสาเหตุ จากทะเบียนผู้เสียชีวิต(PATIENT)เป็นรายบุคคล';
$this->params['breadcrumbs'][] = ['label' => 'แฟ้ม DEATH', 'url' => ['/death/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title"><?= $this->title; ?> ณ.วันที่  <?= Yii::$app->formatter->asDate('now', 'php:Y-m-d'); ?></h3> 
              <h6><p><font color="red">เงื่อนไขการตรวจสอบข้อมูลสาเหตุการตายที่ไม่ทราบสาเหตุ (Ill Defined ICD10='Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709' และ 'R00' ถึง 'R99') เกณฑ์ไม่น้อยกว่า 25 %</font>
                  <?= Html::a('ดาวน์โหลด', ['/death/download']) ?></p></h6>
          <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
        </div>
      </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('แฟ้ม DEATH', ['/help/death'], ['class'=>'btn  btn-success btn-flat']) ?>
            <?= Html::a('การบันทึกข้อมูล', ['/help/deathkey'], ['class'=>'btn  btn-warning btn-flat']) ?>
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
            'beforeHeader'=>[
                [
                    'columns'=>[
                        ['content'=>'', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
                        ['content'=>'จากทะเบียนเสียชีวิต Patient โปรแกรม HOSxP', 'options'=>['colspan'=>8, 'class'=>'text-center warning']], 
                        ['content'=>'จากฐานทะเบียนราษฎร์ ปี 2560', 'options'=>['colspan'=>2, 'class'=>'text-center default']], 
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'F43_Death_Detail4_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'F43_Death_Detail4_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'F43_Death_Detail4_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'F43_Death_Detail4_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['detail4'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'attribute' => 'hn',
                    'header' => 'HN',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'cid',
                    'header' => 'CID',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'full_name',
                    'header' => 'ชื่อ-นามสกุล',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'discharge',
                    'header' => 'สถานะภาพ',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'death_date',
                    'header' => 'วันเสียชีวิต',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'dx',
                    'header' => 'วินิจฉัย',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'last_update',
                    'header' => 'วันอับเดท',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'err',
                    'header' => 'ERROR',
                    'contentOptions' => ['class'=>'text warning']
                ],
                [
                    'attribute' => 'date_d',
                    'header' => 'วันเสียชีวิต',
                    'contentOptions' => ['class'=>'text default']
                ],
                [
                    'attribute' => 'cause_death',
                    'header' => 'สาเหตุการตาย ท.ร.400',
                    'contentOptions' => ['class'=>'text default']
                ],
            ]
        ]);
        ?>
    </div>
<br> 
    </div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>