<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\Pagination;
/* @var $this yii\web\View */
$this->title = 'ตรวจสอบข้อมูล 43 แฟ้ม : แฟ้ม EPI';
$this->params['breadcrumbs'][] = 'แฟ้ม EPI';
?>
<br>
<div style='display: none'>
    <?=
    Highcharts::widget([
        'scripts' => [
            'highcharts-more',
            //'themes/grid',
            //'modules/exporting',
            'modules/solid-gauge',
        ]
    ]);
    ?>
</div>
<?php
//$webroot = Yii::$app->request->BaseUrl;
$this->registerJsFile('@web/js/chart-donut.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<!-- MAP & BOX PANE -->
    <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">ตรวจสอบข้อมูลแฟ้ม EPI ข้อมูล ณ.วันที่ <?= $date_start ?> ถึง <?= $date_end ?></h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
          </div>
        </div>
        <div class="box-body no-padding">
            <!-- row1 -->
                <div class="row">
                <!-- col1--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data1 = [];
                    for ($i = 0; $i < count($chart1); $i++) {
                        $data1[] = $chart1[$i]['cc_pid'];
                    }
                    $js_cc1 = implode(",", $data1);

                    $this->registerJs("
                                var obj_div=$('#chart1');
                                gen_donut(obj_div,'ข้อมูลการให้บริการวัคซีน บัญชี3 ไม่สมบูรณ์',$js_cc1);
                             ");
                    ?>
                    <div id="chart1" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/epi/detail1'], ['class' => 'badge bg-purple']) ?>                      
                        </div>
                    </div>
                </div>
            <!-- col2--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data2 = [];
                    for ($i = 0; $i < count($chart2); $i++) {
                        $data2[] = $chart2[$i]['cc_pid'];
                    }
                    $js_cc2 = implode(",", $data2);

                    $this->registerJs("
                                var obj_div=$('#chart2');
                                gen_donut(obj_div,'ข้อมูลการให้บริการวัคซีน บัญชี4 ไม่สมบูรณ์',$js_cc2);
                             ");
                    ?>
                    <div id="chart2" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/epi/detail2'], ['class' => 'badge bg-purple']) ?>                      
                        </div>
                    </div>
                </div>
            <!-- col3--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data3 = [];
                    for ($i = 0; $i < count($chart3); $i++) {
                        $data3[] = $chart3[$i]['cc_hn'];
                    }
                    $js_cc3 = implode(",", $data3);

                    $this->registerJs("
                                var obj_div=$('#chart3');
                                gen_donut(obj_div,'ข้อมูลการให้บริการวัคซีน OPD ไม่สมบูรณ์',$js_cc3);
                             ");
                    ?>
                    <div id="chart3" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/epi/detail3'], ['class' => 'badge bg-purple']) ?>                      
                        </div>
                    </div>
                </div>
            
                </div>
            
            <!-- row2 -->
                <div class="row">
                <!-- col1--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data4 = [];
                    for ($i = 0; $i < count($chart4); $i++) {
                        $data4[] = $chart4[$i]['cc_hn'];
                    }
                    $js_cc4 = implode(",", $data4);

                    $this->registerJs("
                                var obj_div=$('#chart4');
                                gen_donut(obj_div,'ข้อมูลการให้บริการวัคซีน ER ไม่สมบูรณ์',$js_cc4);
                             ");
                    ?>
                    <div id="chart4" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/epi/detail4'], ['class' => 'badge bg-purple']) ?>                      
                        </div>
                    </div>
                </div>
                <!-- col2--->

                <!-- col3--->

                </div>
        </div>       
  <br>      

        
    </div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>