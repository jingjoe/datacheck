<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
$this->title = 'รายงานจำนวนผู้มารับบริการแยกตามแผนก';
$this->params['breadcrumbs'][] = ['label' => 'รายงานทางสถิติ', 'url' => ['/report/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class="panel panel-default">
    <div class="panel-heading"> <h3 class="panel-title"><span class="glyphicon glyphicon glyphicon-signal"></span> รายงานจำนวนผู้มารับบริการแยกตามแผนก ประจำวันที่ <?= Yii::$app->formatter->asDate('now', 'php:Y-m-d'); ?> </h3> </div>
    <div class="panel-body">
        <div class="row">
            <!-- col1--->
            <div class="col-md-12">
                <?php
                $opd = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from vn_stat WHERE vstdate = DATE(NOW()) AND vn_stat.vn NOT IN (SELECT vn FROM er_regist )and pt_subtype = 0")->queryScalar();
                $er = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT vn) from er_regist WHERE vstdate = DATE(NOW())")->queryScalar();
                $lab = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from lab_head WHERE order_date = DATE(NOW()) and confirm_report = 'Y'")->queryScalar();
                $lr = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT an) from an_stat WHERE ward IN('03','04') and dchdate is NULL")->queryScalar();
                $xray = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT v.vn) from xray_head x LEFT OUTER JOIN vn_stat v on v.vn=x.vn  WHERE v.vstdate = DATE(NOW())")->queryScalar();
                $ipd = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT an) from an_stat WHERE ward IN('01','02','06','07')and dchdate is NULL")->queryScalar();
                $dental = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from dtmain WHERE vstdate = DATE(NOW())")->queryScalar();
                $pcu = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from vn_stat WHERE vstdate = DATE(NOW()) AND pt_subtype = 1 AND spclty in ('12','13','14','16','21','22')")->queryScalar();
                $ttm = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from vn_stat WHERE vstdate = DATE(NOW()) and pt_subtype = 0 AND spclty = 18 ")->queryScalar();
                $pyc = Yii::$app->db2->createCommand("SELECT COUNT(DISTINCT hn) from vn_stat WHERE vstdate = DATE(NOW()) and pt_subtype = 0 AND spclty = 17")->queryScalar();

                echo Highcharts::widget([
                    'scripts' => [
                         'highcharts-more',
                        'themes/grid',
                        //'modules/exporting',
                        'modules/solid-gauge',
                    ],
                    'options' => [
                        'title' => [
                            'text' => 'จำนวนผู้ป่วยมารับบริการประจำวัน',
                        ],
                        'xAxis' => [
                            'categories' => ['OPD', 'ER', 'LAB', 'LR', 'X-RAY', 'IPD', 'DENTAL', 'PCU', 'TTM', 'PHYSICAL']
                        ],
                        'credits' => [
                            'enabled' => false
                        ],
                        'yAxis' => [
                            'title' => ['text' => 'จำนวนผูมารับบริการ (คน)']
                        ],
                        'colors' => ["#f45b5b", "#7798BF"],
                        'series' => [
                            [
                                'type' => 'column',
                                'name' => 'จำนวนผู้ป่วย',
                                'data' => [intval($opd), intval($er), intval($lab), intval($lr), intval($xray), intval($ipd), intval($dental), intval($pcu), intval($ttm), intval($pyc)],
                            ],
                        ],
                    ]
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>