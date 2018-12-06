<?php
use kartik\grid\GridView;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;

$this->title = 'ตรวจสอบ สัญชาติ ไม่ใช่ไทย แต่ไม่ระบุความเป็นต่างด้าว';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบคุณภาพข้อมูล PERSON', 'url' => ['/audit/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"> <?= Html::encode($this->title) ?> ข้อมูล ณ.วันที่ <?= $date1 ?> ถึง <?= $date2 ?></h3>
        <h6><p><font color="red">เงื่อนไขการตรวจสอบAND nationality<>'99' AND (person_labor_type_id='' OR person_labor_type_id IS NULL)
                            AND death<>'Y'</font></p></h6>

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
                'name' => 'date1',
                'value' => $date1,
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
                'name' => 'date2',
                'value' => $date2,
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Auditperson_Detail4_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Auditperson_Detail4_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Auditperson_Detail4_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Auditperson_Detail4_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['auditperson/rep4'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'attribute' => 'person_id',
                    'header' => 'PID'
                ],
                [
                    'attribute' => 'cid',
                    'header' => 'CID'
                ],
                [
                    'attribute' => 'patient_hn',
                    'header' => 'HN'
                ],
                [
                    'attribute' => 'full_name',
                    'header' => 'ชื่อ-นามสกุล'
                ],
                [
                    'attribute' => 'nationality',
                    'header' => 'สัญชาติ'
                ],
                [
                    'attribute' => 'house_regist_type_id',
                    'header' => 'สถานะที่อยู่อาศัย'
                ],
                [
                    'attribute' => 'person_discharge_id',
                    'header' => 'สถานะภาพ'
                ],
                [
                    'attribute' => 'last_update',
                    'header' => 'ปรับปรุงข้อมูลล่าสุด'
                ],
                [
                    'attribute' => 'vstdate',
                    'header' => 'วันรับบริการ'
                ],
            ]
        ]);
        ?>
    </div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>
