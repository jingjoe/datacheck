<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบการให้ำรหัสหัตถการ rehab_code ผู้ป่วย IPD ซ้ำซ้อนเป็นรายบุคคล (กายภาพบำบัด)';
$this->params['breadcrumbs'][] = ['label' => 'แฟ้ม REHABILITATION', 'url' => ['/rehabilitation/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"> <?= Html::encode($this->title) ?> ข้อมูล ณ.วันที่ <?= $date_start ?> ถึง <?= $date_end ?></h3>
        <h6><p><font color="red">เงื่อนไขการตรวจสอบ [COUNT(i.f43_rehab_code)>1 , GROUP BY pmi.an,pmi.vstdate,i.f43_rehab_code] นับซ้ำรหัสหัตถการกายภาพ rehab_code ผู้ป่วย IPD ใน 1 Admit ของวันรับบริการเดียวกัน</font></p></h6>
        
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
        </div>
    </div>
    <!-- date select -->
        <div class="box-tools pull-left">
                <?php $form = ActiveForm::begin(['layout' => 'inline']); ?>
        <div class="form-group">
            <?php
            echo DatePicker::widget([
                'name' => 'date_start',
                'value' => $date_start,
                'language' => 'th',
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'todayHighlight' => true
                ]
            ]);
            ?>

        </div>
        <div class="form-group">
            <label class="control-label"> ถึง </label>
            <?php
            echo DatePicker::widget([
                'name' => 'date_end',
                'value' => $date_end,
                'language' => 'th',
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'changeMonth' => true,
                    'changeYear' => true,
                    'todayHighlight' => true
                ]
            ]);
            ?>
        </div>
        <div class="form-group">
            <?= Html::submitButton('ประมวลผล', ['class' => 'btn btn-info btn-flat']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        </div>
    <!-- documents -->
    <div class="box-tools pull-right">
        <div class="btn-group">
            <?= Html::a('แฟ้ม REHABILITATION', ['/help/rehabilitation'], ['class'=>'btn  btn-success btn-flat']) ?>
            <?= Html::a('การบันทึกข้อมูล', ['/help/rehabilitationkey'], ['class'=>'btn  btn-warning btn-flat']) ?>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'F43_Rehabilitation_Detail4_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'F43_Rehabilitation_Detail4_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'F43_Rehabilitation_Detail4_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'F43_Rehabilitation_Detail4_'.date('Y-d-m')],
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
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'attribute' => 'hn',
                    'header' => 'HN'
                ],
                [
                    'attribute' => 'cid',
                    'header' => 'CID'
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
                    'attribute' => 'vstdate',
                    'header' => 'วันรับบริการ'
                ],
                [
                    'attribute' => 'doc_name',
                    'header' => 'ผู้ให้บริการ'
                ],
                [
                    'attribute' => 'err',
                    'header' => 'หัตถการกายภาพ'
                ],
            ]
        ]);
        ?>
    </div>
    <br> 
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>