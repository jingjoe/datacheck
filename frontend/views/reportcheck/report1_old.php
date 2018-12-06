<?php

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

use fedemotta\datatables\DataTables;

/* @var $this yii\web\View */
$this->title = 'รายงานไม่ลงวินิจฉัยผู้ป่วยนอก DIAGNOSIS_OPD';
$this->params['breadcrumbs'][] = ['label' => 'รายงานสำหรับสนับสนุนการบริหารจัดการทางด้านสาธารณสุข จากฐาน HOSxP', 'url' => ['/report/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>

<div style='display: none'>
    <?=
    Highcharts::widget([
        'scripts' => [
            'highcharts-more',
            'themes/grid',
            //'modules/exporting',
            'modules/solid-gauge',
        ]
    ]);
    ?>
</div>
<div class='bg-success'>
    <?php $form = ActiveForm::begin(['layout' => 'inline']); ?>
    <div class="form-group">
        <label class="control-label"> เลือกวันที่ </label>
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
        <?= Html::submitButton('ประมวลผล', ['class' => 'btn btn-warning btn-flat']) ?>
    </div><!-- /.input group -->
    <?php ActiveForm::end(); ?>
</div>
<br>

<div class="panel panel-default">
    <div class="panel-heading"> <h3 class="panel-title"><span class="glyphicon glyphicon glyphicon-signal"></span> รายงานไม่ลงวินิจฉัยผู้ป่วยนอกแยกรายแผนก DIAGNOSIS_OPD ข้อมูลวันที่ <?=$date1 ?> ถึง <?=$date2 ?></h3> </div>
    <div class="panel-body">
        <div id="container-line"></div>
        <?php

        $categ = [];
        for ($i = 0; $i < count($chart); $i++) {
            $categ[] = $chart[$i]['dep'];
        }
        $js_categ = implode("','", $categ);

        $data_cc = [];
        for ($i = 0; $i < count($chart); $i++) {
            $data_cc[] = $chart[$i]['cc'];
        }
        $js_cc = implode(",", $data_cc);

        $this->registerJs(" $(function () {
                            $('#container-line').highcharts({
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'รายงานไม่ลงวินิจฉัยผู้ป่วยนอก DIAGNOSIS_OPD',
                                    x: -20 //center
                                },
                                subtitle: {
                                    text: '',
                                    x: -20
                                },
                                xAxis: {
                                      categories: ['$js_categ'],
                                },
                                yAxis: {
                                    title: {
                                        text: 'จำนวน(ครั้ง)'
                                    },
                                    plotLines: [{
                                        value: 0,
                                        width: 1,
                                        color: '#808080'
                                    }]
                                },
                                tooltip: {
                                    valueSuffix: ''
                                },
                                legend: {
                                    layout: 'vertical',
                                    align: 'right',
                                    verticalAlign: 'middle',
                                    borderWidth: 0
                                },
                                credits: {
                                    enabled: false
                                },
                                series: [{
                                    name: 'แผนก',
                                    data: [$js_cc]
                                }]
                            });
                        });
             ");
        ?>
<br>
        <div class="box-tools pull-right">
            <!-- dialog sql -->
            <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target=".bs-example-modal-lg">sql script</button>
            <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="gridSystemModalLabel">SQL : รายงานไม่ลงวินิจฉัยผู้ป่วยนอก DIAGNOSIS_OPD</h4>
                        </div>
                        <div class="modal-body">
                            <?= $sql ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<br><br>

<?= DataTables::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'clientOptions' => [
        "lengthMenu" => [[20, -1], [20, Yii::t('app', "All")]],
        "info" => true,
        "responsive" => true,
        "dom" => '<"top"lf>rt<"bottom"ip><"clear">',
        "tableTools" => [
            "aButtons" => [
                [
                    "sExtends" => "copy",
                    "sButtonText" => Yii::t('app', "Copy to clipboard")
                ], 
                [
                    "sExtends" => "csv",
                    "sButtonText" => Yii::t('app', "Save to CSV")
                ], 
                [
                    "sExtends" => "xls",
                    "oSelectorOpts" => ["page" => 'current']
                ], 
                [
                    "sExtends" => "pdf",
                    "sButtonText" => Yii::t('app', "Save to PDF")
                ], 
                [
                    "sExtends" => "print",
                    "sButtonText" => Yii::t('app', "Print")
                ],
            ]
        ]
    ],
    'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'dep',
                    'header' => 'แผนก'
                ],
                [
                    'label' => 'จำนวน (ครั้ง)',
                    'format' => 'raw',
                    'value' => function($model) use($date1,$date2){
                        return Html::a(Html::encode($model['cc']), ['/reportcheck/rep1detail', 'id' => $model['spclty'], 'date1' => $date1, 'date2' => $date2 ]);
                    }
                    
                ]
            ],

]);?>
     </div>
    </div>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>