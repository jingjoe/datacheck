<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\Pagination;
/* @var $this yii\web\View */
$this->title = 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม';
$this->params['breadcrumbs'][] = 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม';
?>
<br>
    <div class='bg-success'>
        <?php $form = ActiveForm::begin([
            'layout' => 'inline'
            ]); ?>
            <div class="form-group">
                <label class="control-label"> ปี ปฏิทิน : </label>
                <?php
                    $list_y = [
                        '2562' => '2562',
                        '2561' => '2561'];
                    echo Html::dropDownList('y',$y,$list_y,['class' => 'form-control']);
                ?>
                <label class="control-label"> เดือน : </label>
                <?php
                    $list_m = [
                        '01' => 'มกราคม',
                        '02' => 'กุมภาพันธ์',
                        '03' => 'มีนาคม',
                        '04' => 'เมษายน',
                        '05' => 'พฤษภาคม',
                        '06' => 'มิถุนายน',
                        '07' => 'กรกฎาคม',
                        '08' => 'สิงหาคม',
                        '09' => 'กันยายน ',
                        '10' => 'ตุลาคม',
                        '11' => 'พฤศจิกายน',
                        '12' => 'ธันวาคม'];
                    echo Html::dropDownList('m',$m,$list_m,['class' => 'form-control']);
                ?>
            </div>

        
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-play"></i> ตกลง', ['class' => 'btn btn-warning btn-flat']) ?>
            </div><!-- /.input group -->
       <?php ActiveForm::end(); ?>
    </div>

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
          <h3 class="box-title">ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม ข้อมูลปี <?= $y ?> เดือน <?= $m ?></h3>
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
                        $data1[] = $chart1[$i]['cc'];
                    }
                    $js_cc1 = implode(",", $data1);

                    $this->registerJs("
                                var obj_div=$('#chart1');
                                gen_donut(obj_div,'Error R24 : ขาดวิธีการใช้ยา',$js_cc1);
                             ");
                    ?>
                    <div id="chart1" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/ssop/rep01','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
                        </div>
                    </div>
                </div>
                <!-- col2--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data2 = [];
                    for ($i = 0; $i < count($chart2); $i++) {
                        $data2[] = $chart2[$i]['cc'];
                    }
                    $js_cc2 = implode(",", $data2);

                    $this->registerJs("
                                var obj_div=$('#chart2');
                                gen_donut(obj_div,'Error S15 : เลขที่ใบประกอบวิชาชีพ SvPID ไม่ถูกต้อง',$js_cc2);
                             ");
                    ?>
                    <div id="chart2" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/ssop/rep02detail','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
                        </div>
                    </div>
                </div>
            <!-- col3--->
                <div class="col-md-4" style="text-align: center;">
                    <?php
                    $data3 = [];
                    for ($i = 0; $i < count($chart3); $i++) {
                        $data3[] = $chart3[$i]['cc'];
                    }
                    $js_cc3 = implode(",", $data3);

                    $this->registerJs("
                                var obj_div=$('#chart3');
                                gen_donut(obj_div,'Error C07 : รหัสสถานพยาบาลหลักไม่ถูกต้อง',$js_cc3);
                             ");
                    ?>
                    <div id="chart3" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/ssop/rep02','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
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
                            $data4[] = $chart4[$i]['cc'];
                        }
                        $js_cc4 = implode(",", $data4);

                        $this->registerJs("
                                        var obj_div=$('#chart4');
                                        gen_donut(obj_div,'Error S18 : รหัสการวินิจฉัยไม่ถูกต้อง',$js_cc4);
                                     ");
                        ?>
                        <div id="chart4" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/ssop/rep03','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
                            </div>
                        </div>
                    </div>
                 
                <!-- col2---> 
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data5 = [];
                        for ($i = 0; $i < count($chart5); $i++) {
                            $data5[] = $chart5[$i]['cc'];
                        }
                        $js_cc5 = implode(",", $data5);

                        $this->registerJs("
                                        var obj_div=$('#chart5');
                                        gen_donut(obj_div,'Error S14 : วันนัดครั้งต่อไป DTAppoint ไม่ถูกต้อง',$js_cc5);
                                     ");
                        ?>
                        <div id="chart5" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/ssop/rep04','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
                            </div>
                        </div>
                    </div>
                <!-- col3---> 
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data6 = [];
                        for ($i = 0; $i < count($chart6); $i++) {
                            $data6[] = $chart6[$i]['cc'];
                        }
                        $js_cc6 = implode(",", $data6);

                        $this->registerJs("
                                        var obj_div=$('#chart6');
                                        gen_donut(obj_div,'Error T32 : ผลรวมของ Paid + ClaimAmt...',$js_cc6);
                                     ");
                        ?>
                        <div id="chart6" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/ssop/rep05','y' => $y,'m' => $m], ['class' => 'badge bg-green']) ?>                      
                            </div>
                        </div>
                    </div>
                
                </div>
            
            <!--row3 -->
                <div class="row">
                <!-- col1---> 
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data7 = [];
                        for ($i = 0; $i < count($chart7); $i++) {
                            $data7[] = $chart7[$i]['cc'];
                        }
                        $js_cc7 = implode(",", $data7);

                        $this->registerJs("
                                        var obj_div=$('#chart7');
                                        gen_donut(obj_div,'Error S41 :Class ใน Opservices เป็นหัตถการ...',$js_cc7);
                                     ");
                        ?>
                        <div id="chart7" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/ssop/rep06','y' => $y,'m' => $m], ['class' => 'badge bg-yellow']) ?>                      
                            </div>
                        </div>
                    </div>
                 
                <!-- col2---> 
                    <div class="col-md-4" style="text-align: center;">
                        <?php
                        $data8 = [];
                        for ($i = 0; $i < count($chart8); $i++) {
                            $data8[] = $chart8[$i]['cc'];
                        }
                        $js_cc8 = implode(",", $data8);

                        $this->registerJs("
                                        var obj_div=$('#chart8');
                                        gen_donut(obj_div,'Error S19 : ICD9 ไม่ถูกต้องหรือไม่สัมพันธ์กับ Code...',$js_cc8);
                                     ");
                        ?>
                        <div id="chart8" style="width: 300px; height: 200px; float: left"></div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <?= Html::a('ดูรายละเอียด', ['/ssop/rep07','y' => $y,'m' => $m], ['class' => 'badge bg-yellow']) ?>                      
                            </div>
                        </div>
                    </div>

                <!-- col3---> 
                </div>
            
        </div>
<br>      
    </div> 
<div class="row">
    <div class="col-xs-12">
        <span class="pull-left-container">
            <small class="label bg-green"><i class="fa fa-check"></i> ผ่านการตรวจสอบ</small>
            <small class="label bg-yellow"><i class="fa  fa-close"></i> รอการตรวจสอบ</small>
        </span>
        <small class="pull-right"><i class="fa fa-clock-o"></i> ประมวลผลข้อมูลล่าสุด : <?php echo $date_process; ?> น.</small>
    </div>
    <!-- /.col -->
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>