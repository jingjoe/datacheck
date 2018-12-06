<?php

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
$this->title = 'รายงานไม่ลงวิธีการใช้ยาป่วยนอกแยกตามแพทย์ผู้สั่งยา Error R24 : ขาดวิธีการใช้ยา';
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม', 'url' => ['/ssop/index']];
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

<div class="panel panel-default">
    <div class="panel-heading"> <h3 class="panel-title"><span class="glyphicon glyphicon glyphicon-signal"></span> <?= $this->title; ?>  ข้อมูลปี <?= $y ?> เดือน <?= $m ?></h3> </div>
    <div class="panel-body">
        <div id="container-line"></div>
        <?php

        $categ = [];
        for ($i = 0; $i < count($chart); $i++) {
            $categ[] = $chart[$i]['doctor_name'];
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
                                    text: 'รายงานไม่ลงวิธีการใช้ยาป่วยนอกแยกตามแพทย์ผู้สั่งยา Error R24 : ขาดวิธีการใช้ยา',
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
                                        text: 'จำนวน(ยา)'
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
                                    name: 'ผู้สั่งยา',
                                    data: [$js_cc]
                                }]
                            });
                        });
             ");
        ?>
<br>
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
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Error_R24_Rep01_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Error_R24_Rep01_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Error_R24_Rep01_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Error_R24_Rep01_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['rep01','y' => $y,'m' => $m], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
                    'class' => 'kartik\grid\SerialColumn'
                ],
                [
                    'label' => 'ผู้สั่งยา',
                    'format' => 'raw',
                    'value' => function($model) use($m,$y){
                        return Html::a(Html::encode($model['doctor_name']), ['/ssop/rep01detail', 'dcode' => $model['doctor_code'], 'y' => $y , 'm' => $m]);
                    },
                    'pageSummary'=>'รวมทั้งหมด' 
                ],
                [
                    'attribute'=>'cc',
                    'header' => 'จำนวน (ยา)',
                    'hAlign'=>'left',
                    'format'=>['decimal', 0],
                    'pageSummary'=>true
                ],
            ]
        ]);
        ?>
     </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>