<?php
use kartik\tabs\TabsX;
use yii\helpers\Html;

$this->title = 'รายงานออนไลน์';
$this->params['breadcrumbs'][] = 'รายงานออนไลน์';

/* @var $this yii\web\View */
?>
<br>
<div class="row">
    <!-- /.box1 -->
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> รายงานทางสถิติของโรงพยาบาล</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานจำนวนผู้มารับบริการแยกตามแผนก', ['/reportstats/rep1']); ?> <br>
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานจำนวนครั้งผู้ป่วยรับบริการทั่วไปจำแนกสิทธิและค่าใช้จ่าย', ['/reportstats/rep2']); ?>  <br>
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานอันดับโรคผู้ป่วย OPD ตามรหัสโรค ICD-10', ['/reportstats/rep3']); ?>  <br>
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานอันดับโรคผู้ป่วย IPD ตามรหัสโรค ICD-10', ['/reportstats/rep4']); ?>  <br>

            </div>
        </div>
    </div>
    <!-- /.box2 -->    
    <div class="col-md-6">

        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> รายงานบนฐานโปรแกรม HOSxP</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานไม่ลงผลวินิจฉัยผู้ป่วยนอก DIAGNOSIS_OPD', ['/reportcheck/rep1']); ?>  <br>
                <?= Html::a('<i class="fa fa-database text-yellow"></i> รายงานไม่ลงผลวินิจฉัยผู้ป่วยใน DIAGNOSIS_IPD', ['/reportcheck/rep2']); ?>  <br>
            </div>
        </div> 
    </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>


