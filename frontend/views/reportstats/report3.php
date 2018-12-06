<?php

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
$this->title = 'รายงานอันดับโรคผู้ป่วย OPD ตามรหัสโรค ICD-10';
$this->params['breadcrumbs'][] = ['label' => 'รายงานทางสถิติ', 'url' => ['/report/index']];
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
        <label class="control-label"> ลำดับโรค </label>
        <?php
            $list = [
                '5' => '5',
                '10' => '10',
                '15' => '15',
                '20' => '20',
                '25' => '25',
                '30' => '30'];
            echo Html::dropDownList('se',$se,$list,['class' => 'form-control']);
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton('ประมวลผล', ['class' => 'btn btn-warning btn-flat']) ?>
    </div><!-- /.input group -->
    <?php ActiveForm::end(); ?>
</div>
<br>

<div class="panel panel-default">
    <div class="panel-heading"> <h3 class="panel-title"><span class="glyphicon glyphicon glyphicon-signal"></span> รายงาน <?=$se ?> อันดับโรคผู้ป่วย OPD ตามรหัสโรค ICD-10 ข้อมูลวันที่ <?=$date1 ?> ถึง <?=$date2 ?></h3> </div>
    <div class="panel-body">
        <div id="container-line"></div>
        <?php

        $categ = [];
        for ($i = 0; $i < count($chart); $i++) {
            $categ[] = $chart[$i]['pdx'];
        }
        $js_categ = implode("','", $categ);

        $data_pdx = [];
        for ($i = 0; $i < count($chart); $i++) {
            $data_pdx[] = $chart[$i]['pdx_count'];
        }
        $js_pdx = implode(",", $data_pdx);

        $data_hn = [];
        for ($i = 0; $i < count($chart); $i++) {
            $data_hn[] = $chart[$i]['hn_count'];
        }
        $js_hn = implode(",", $data_hn);

        $data_vn = [];
        for ($i = 0; $i < count($chart); $i++) {
            $data_vn[] = $chart[$i]['visit_count'];
        }
        $js_vn = implode(",", $data_vn);

        $this->registerJs(" $(function () {
                            $('#container-line').highcharts({
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: 'รายงานอันดับโรคผู้ป่วย OPD ตามรหัสโรค ICD-10',
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
                                        text: 'จำนวน(ราย)'
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
                                    name: 'จำนวนวินิจฉัย',
                                    data: [$js_pdx]
                                }, {
                                    name: 'จำนวนราย',
                                    data: [$js_hn]
                                }, {
                                    name: 'จำนวนครั้ง',
                                    data: [$js_vn]
                                }]
                            });
                        });
             ");
        ?>
<br>
         <div class="box-tools pull-right">
             <!-- dialog sql -->
            <button type="button" class="btn bg-navy btn-flat" data-toggle="modal" data-target=".bs-example-modal-lg">ชุดคำสั่ง SQL</button>
                <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title" id="gridSystemModalLabel">SQL : รายงาน <?=$se ?> อันดับโรคผู้ป่วย OPD ตามรหัสโรค ICD-10</h4>
                          </div>
                         <div class="modal-body">
                             <?= $sql ?>
                         </div>
                         <div class="modal-footer">
                               <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
                               <button type="button" class="btn btn-primary btn-flat">Save changes</button>
                         </div>
                        </div>
                    </div>
                </div>
         </div>
<br><br> 
     <?=GridView::widget([
            'dataProvider' => $dataProvider,
            'showPageSummary'=>true,
            'headerRowOptions' => ['style' => 'background-color:#cccccc'],
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
                'footer'=>false
            ],
            'responsive' => true,
            'hover' => true,
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'ReportStats3_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'ReportStats3_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'ReportStats3_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'ReportStats3_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rep3'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'pdx',
                    'header' => 'รหัสโรค',
                    'pageSummary'=>'รวมทั้งหมด' 
                ],
                [
                    'attribute' => 'icdname',
                    'header' => 'ชื่อโรค'
                ],
                [
                    'attribute' => 'pdx_count',
                    'header' => 'จำนวนวินิจฉัย',
                    'hAlign'=>'left',
                    'format'=>['decimal', 0],
                    'pageSummary'=>true
                ],
                [
                    'attribute' => 'hn_count',
                    'header' => 'จำนวนราย',
                    'hAlign'=>'left',
                    'format'=>['decimal', 0],
                    'pageSummary'=>true
                ],
                [
                    'attribute' => 'visit_count',
                    'header' => 'จำนวนครั้ง',
                    'hAlign'=>'left',
                    'format'=>['decimal', 0],
                    'pageSummary'=>true
                ]
            ],
        ]);
        ?>
    </div>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>