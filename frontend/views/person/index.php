<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\Pagination;

/* @var $this yii\web\View */
$this->title = 'ตรวจสอบข้อมูล 43 แฟ้ม : แฟ้ม PERSON';
$this->params['breadcrumbs'][] = 'แฟ้ม PERSON';
?>
<br>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>Warning!</strong> ไม่ต้องแก้ไข PID : 20820 และ CID : 0114186138451 ใช้สำหรับจัดยานอกเวลา (ให้พิจารณาลบออก จากทะเบียน PERSON)!
</div>

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
              <h3 class="box-title">ตรวจสอบข้อมูลแฟ้ม PERSON ข้อมูลจากตาราง(PERSON) ข้อมูล ณ.วันที่  <?= Yii::$app->formatter->asDate('now', 'php:Y-m-d'); ?></h3>
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
                            $data1[] = $chart1[$i]['cc_hn'];
                        }
                        $js_cc1 = implode(",", $data1);

                        $this->registerJs("
                                        var obj_div=$('#chart1');
                                        gen_donut(obj_div,'CID ผู้มารับบริการยังไม่มีใน PERSON',$js_cc1);
                                     ");
                        ?>
                        <div id="chart1" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail1'], ['class' => 'badge bg-purple']) ?>                      
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
                                        gen_donut(obj_div,'TYPEAREA มีค่าว่างหรือไม่เท่ากับ 4',$js_cc2);
                                     ");
                        ?>
                        <div id="chart2" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail2'], ['class' => 'badge bg-purple']) ?>                      
                            </div>
                        </div>
                    </div>
                <!-- col3--->    
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data3 = [];
                        for ($i = 0; $i < count($chart3); $i++) {
                            $data3[] = $chart3[$i]['cc_pid'];
                        }
                        $js_cc3 = implode(",", $data3);

                        $this->registerJs("
                                        var obj_div=$('#chart3');
                                        gen_donut(obj_div,'เลข 13 หลักไม่ถูกต้องทั้งหมดตัด GEN',$js_cc3);
                                     ");
                        ?>
                        <div id="chart3" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail3'], ['class' => 'badge bg-purple']) ?>                      
                            </div>
                        </div>
                    </div>
                </div>
            <!--row2 -->
                <div class="row">
                <!-- col1---> 
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data4 = [];
                        for ($i = 0; $i < count($chart4); $i++) {
                            $data4[] = $chart4[$i]['cc_pid'];
                        }
                        $js_cc4 = implode(",", $data4);

                        $this->registerJs("
                                        var obj_div=$('#chart4');
                                        gen_donut(obj_div,'ตรวจสอบข้อมูลทั่วไป',$js_cc4);
                                     ");
                        ?>
                        <div id="chart4" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail4'], ['class' => 'badge bg-purple']) ?>                      
                            </div>
                        </div>
                    </div>
                <!-- col2--->    
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data5 = [];
                        for ($i = 0; $i < count($chart5); $i++) {
                            $data5[] = $chart5[$i]['cc_pid'];
                        }
                        $js_cc5 = implode(",", $data5);

                        $this->registerJs("
                                        var obj_div=$('#chart5');
                                        gen_donut(obj_div,'ตรวจสอบเลข 13 หลัก GEN',$js_cc5);
                                     ");
                        ?>
                        <div id="chart5" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail5'], ['class' => 'badge bg-purple']) ?>                      
                            </div>
                        </div>
                    </div>
                <!-- col3--->  
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data6 = [];
                        for ($i = 0; $i < count($chart6); $i++) {
                            $data6[] = $chart6[$i]['cc_pid'];
                        }
                        $js_cc6 = implode(",", $data6);

                        $this->registerJs("
                                        var obj_div=$('#chart6');
                                        gen_donut(obj_div,'ตรวจสอบเลข 13 หลักซ้ำซ้อน>1',$js_cc6);
                                     ");
                        ?>
                        <div id="chart6" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/person/detail6'], ['class' => 'badge bg-purple']) ?>                      
                            </div>
                        </div>
                </div>
                </div>

                </div>
              <br>
            </div>
          </div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>
