<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\data\Pagination;
/* @var $this yii\web\View */
$this->title = 'ตรวจสอบข้อมูล 43 แฟ้ม : แฟ้ม POSTNATAL';
$this->params['breadcrumbs'][] = 'แฟ้ม POSTNATAL';
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
          <h3 class="box-title">ตรวจสอบข้อมูลแฟ้ม POSTNATAL ข้อมูล ณ.วันที่ <?= $date_start ?> ถึง <?= $date_end ?></h3>
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
                        $data1[] = $chart1[$i]['cc_anc'];
                    }
                    $js_cc1 = implode(",", $data1);

                    $this->registerJs("
                                var obj_div=$('#chart1');
                                gen_donut(obj_div,'ข้อมูลประวัติการดูแลมารดาหลังคลอด',$js_cc1);
                             ");
                    ?>
                    <div id="chart1" style="width: 300px; height: 200px; float: left"></div>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <?= Html::a('ดูรายละเอียด', ['/postnatal/detail1'], ['class' => 'badge bg-purple']) ?>                      
                        </div>
                    </div>
                </div>
                <!-- col2--->
        
                </div>
        </div>       
       <br> 

        
    </div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>