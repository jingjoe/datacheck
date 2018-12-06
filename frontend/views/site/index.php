<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

$this->title = 'ระบบตรวจสอบข้อมูล 43 แฟ้มบนเว็บแอพพลิเคชั่น (data-check)';
?>
 
<div style='display: none'>
    <?=
    Highcharts::widget([
        'scripts' => [
            'highcharts-more'
        ]
    ]);
    ?>
</div>
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3><?php echo number_format($opd_p,0,".",",") ?></h3>

              <p>จำนวนผู้ป่วยนอก (คน)</p>
            </div>
            <div class="icon">
              <i class="fa fa-user-plus"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3><?php echo number_format($opd_v,0,".",",") ?></h3>

              <p>จำนวนผู้ป่วยนอก (ครั้ง)</p>
            </div>
            <div class="icon">
              <i class="fa  fa-user-md"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3><?php echo number_format($ipd_a,0,".",",") ?></h3>

              <p>จำนวนผู้ป่วยใน (คน)</p>
            </div>
            <div class="icon">
              <i class="fa fa-hotel"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3><?php echo number_format($ipd_d,0,".",",") ?></h3>

              <p>จำนวนผู้ป่วยใน (วัน)</p>
            </div>
            <div class="icon">
              <i class="fa fa-clock-o"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
</div>


<!-- box1 -->
<div class="row">   
    <div class="col-lg-12">
        <div class="box box-solid box-success">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> จำนวนผู้ป่วยนอกมารับบริการ (คน/ครั้ง)ปีงบประมาณ <?= $b_year ?></h3>
                 <!-- tools box -->
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- /. tools -->
            </div>
<!-- /. Combining chart types colum and spline -->          
            <div class="box-body">
                 <div id="container-opd"></div>
                    <?php

                    $categ = [];
                    for ($i = 0; $i < count($m_opd); $i++) {
                        $categ[] = $m_opd[$i]['m'];
                    }
                    $js_categ = implode("','", $categ);

                    $data_cc = [];
                    for ($i = 0; $i < count($m_opd); $i++) {
                        $data_cc[] = $m_opd[$i]['cc'];
                    }
                    $js_cc = implode(",", $data_cc);

                    $data_vv = [];
                    for ($i = 0; $i < count($m_opd); $i++) {
                        $data_vv[] = $m_opd[$i]['vv'];
                    }
                    $js_vv = implode(",", $data_vv);

                    $this->registerJs(" $(function () {
                                        $('#container-opd').highcharts({
                                            chart: {
                                               height: 400,
                                               width: 1067
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
                                                    text: ''
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
                                                type: 'column',
                                                name: 'จำนวนคน',
                                                data: [$js_cc]
                                            }, {
                                                type: 'column',
                                                name: 'จำนวนครั้ง',
                                                data: [$js_vv]
                                            },{
                                                type: 'spline',
                                                name: 'Person',
                                                 data: [$js_cc],
                                                marker: {
                                                    lineWidth: 2,
                                                    lineColor: Highcharts.getOptions().colors[3],
                                                    fillColor: 'white'
                                                }
                                            },{
                                                type: 'spline',
                                                name: 'Visit',
                                                 data: [$js_vv],
                                                marker: {
                                                    lineWidth: 2,
                                                    lineColor: Highcharts.getOptions().colors[3],
                                                    fillColor: 'white'
                                                }
                                            }],


                                        });
                                    });
                         ");
                    ?>

            </div>   
        </div>
    </div>
</div>

<!-- box2 -->
<div class="row">   
    <div class="col-lg-12">
        <div class="box box-solid box-success">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> จำนวนผู้ป่วยในมารับบริการ (คน/วันนอน)ปีงบประมาณ <?= $b_year ?></h3>
                 <!-- tools box -->
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
                <!-- /. tools -->
            </div>
<!-- /. Combining chart types colum and spline -->     
            <div class="box-body">
                <div id="container-ipd"></div>
                    <?php

                    $categ = [];
                    for ($i = 0; $i < count($m_ipd); $i++) {
                        $categ[] = $m_ipd[$i]['m'];
                    }
                    $js_categ = implode("','", $categ);

                    $data_cc = [];
                    for ($i = 0; $i < count($m_ipd); $i++) {
                        $data_cc[] = $m_ipd[$i]['cc'];
                    }
                    $js_cc = implode(",", $data_cc);

                    $data_dd = [];
                    for ($i = 0; $i < count($m_ipd); $i++) {
                        $data_dd[] = $m_ipd[$i]['dd'];
                    }
                    $js_dd = implode(",", $data_dd);

                    $this->registerJs(" $(function () {
                                        $('#container-ipd').highcharts({
                                            chart: {
                                               height: 400,
                                               width: 1067
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
                                                    text: ''
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
                                                type: 'column',
                                                name: 'จำนวนคน',
                                                data: [$js_cc]
                                            }, {
                                                type: 'column',
                                                name: 'จำนวนวันนอน',
                                                data: [$js_dd]
                                            },{
                                                type: 'spline',
                                                name: 'Admit',
                                                 data: [$js_cc],
                                                marker: {
                                                    lineWidth: 2,
                                                    lineColor: Highcharts.getOptions().colors[3],
                                                    fillColor: 'white'
                                                }
                                            },{
                                                type: 'spline',
                                                name: 'Los',
                                                 data: [$js_dd],
                                                marker: {
                                                    lineWidth: 2,
                                                    lineColor: Highcharts.getOptions().colors[3],
                                                    fillColor: 'white'
                                                }
                                            }],
                                        });
                                    });
                         ");
                    ?>

            </div>   
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="pull-right hidden-xs">
            <?php $cc_t = Yii::$app->db->createCommand("SELECT end_process FROM tmp_visit_opd LIMIT 1")->queryScalar(); ?>
            ประมวลผลล่าสุด <?= $cc_t; ?> น.
        </div>
    </div>
</div>
