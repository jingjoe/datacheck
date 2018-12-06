<?php

use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
$this->title = 'สถิติการเข้าใช้งานระบบ';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div style='display: none'>
    <?= Highcharts::widget([
        'scripts' => [
            'highcharts-more',
            'themes/grid',
            //'modules/exporting',
            'modules/solid-gauge',
        ]
    ]);
    ?>
</div>

 <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">สถิติการเข้าใช้งานระบบ ข้อมูล ณ.วันที่  <?= Yii::$app->formatter->asDate('now', 'php:Y-m-d'); ?></h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
            </div>
          </div>
    <div class="panel-body">
        <div id="container-column"></div>
        <?php

        $categ = [];
        for ($i = 0; $i < count($chart); $i++) {
            $categ[] = $chart[$i]['username'];
        }
        $js_categ = implode("','", $categ);

        $visit = [];
        for ($i = 0; $i < count($chart); $i++) {
            $visit[] = $chart[$i]['visit'];
        }
        $visit = implode(",", $visit);

        $this->registerJs(" $(function () {
                            $('#container-column').highcharts({
                                chart: {
                                    type: 'column'
                                },
                                title: {
                                    text: '',
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
                                        text: 'ชื่อผู้ใช้'
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
                                    name: 'จำนวนการใช้งาน',
                                    data: [$visit]
                                }]
                            });
                        });
             ");
        ?>
    </div>
</div>

