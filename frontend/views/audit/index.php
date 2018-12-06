<?php
use yii\helpers\Html;

$this->title = 'ตรวจสอบคุณภาพข้อมูล';
$this->params['breadcrumbs'][] = 'ตรวจสอบคุณภาพข้อมูลจากฐาน HOSxP';

/* @var $this yii\web\View */
?>
<br>
<div class="row">
    <!-- /.box1 -->
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> ตรวจสอบคุณภาพข้อมูล PERSON</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
                </div>
            </div>
            <div class="box-body">
                <?php foreach ($chk10 as $chk10) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> เลขที่บัตรประชาชนผิด MOD11 โดยที่สัญชาติเป็นไทย', ['/auditperson/rep0']); ?>
                <small class="badge  bg-info"><?php echo $chk10['result']; ?></small> <small class="badge  bg-info"><?php echo $chk10['target']; ?></small><br>
                
                <?php foreach ($chk11 as $chk11) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> เลขที่บัตรประชาชนไม่ครบ 13 หลักหรือผิดรูปแบบตัดเลข GEN ตามรูปแบบของ HIS ออก โดยที่สัญชาติเป็นไทย', ['/auditperson/rep1']); ?>
                <small class="badge  bg-info"><?php echo $chk11['result']; ?></small> <small class="badge  bg-info"><?php echo $chk11['target']; ?></small><br>
                <?php foreach ($chk12 as $chk12) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> เลขที่บัตรประชาชนเป็นเลข GEN ตามรูปแบบของ HIS โดยที่สัญชาติเป็นไทย', ['/auditperson/rep2']); ?>
                <small class="badge  bg-info"><?php echo $chk12['result']; ?></small> <small class="badge  bg-info"><?php echo $chk12['target']; ?></small><br>

                <?php foreach ($chk13 as $chk13) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> ผู้ป่วยขึ้นทะเบียนที่ Patient แต่ยังไม่ถูกขึ้นทะเบียนที่ Person', ['/auditperson/rep3']); ?>
                <small class="badge  bg-info"><?php echo $chk13['result']; ?></small> <small class="badge  bg-info"><?php echo $chk13['target']; ?></small><br>

                <?php foreach ($chk14 as $chk14) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> สัญชาติ ไม่ใช่ไทย แต่ไม่ระบุความเป็นต่างด้าว', ['/auditperson/rep4']); ?>
                <small class="badge  bg-info"><?php echo $chk14['result']; ?></small> <small class="badge  bg-info"><?php echo $chk14['target']; ?></small><br>

                <?php foreach ($chk15 as $chk15) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> ประชากร Person ที่ขึ้นทะเบียนเป็นคนนอกเขต Typearea=4 ไม่บันทึกข้อมูลที่อยู่นอกเขต', ['/auditperson/rep5']); ?>
                <small class="badge  bg-info"><?php echo $chk15['result']; ?></small> <small class="badge  bg-info"><?php echo $chk15['target']; ?></small><br>
            </div>
        </div>
    </div>
    <!-- /.box2 -->
    <div class="col-md-6">
       <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-bar-chart"></i> คุณภาพการให้รหัส ICD ตาม สนย.กำหนด</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
                </div>
            </div>
            <div class="box-body">

                <?php foreach ($chk01 as $chk01) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> รหัส ICD ที่ใช้ได้กับผู้ป่วย หญิง เท่านั้น', ['/auditicd/rep1']); ?>
                <small class="badge  bg-info"><?php echo $chk01['result']; ?></small> <small class="badge  bg-info"><?php echo $chk01['target']; ?></small><br>

                <?php foreach ($chk02 as $chk02) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> ห้ามใช้รหัส V,W,X,Y เป็นรหัสโรคหลัก', ['/auditicd/rep2']); ?>
                <small class="badge  bg-info"><?php echo $chk02['result']; ?></small> <small class="badge  bg-info"><?php echo $chk02['target']; ?></small><br>

                <?php foreach ($chk03 as $chk03) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> การให้รหัส S และ T ในผู้ป่วยรายใดต้องให้รหัสสาเหตุภายนอกร่วมด้วยเสมอ', ['/auditicd/rep3']); ?>
                <small class="badge  bg-info"><?php echo $chk03['result']; ?></small> <small class="badge  bg-info"><?php echo $chk03['target']; ?></small><br>

                <?php foreach ($chk04 as $chk04) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> การให้รหัส ผู้ป่วยนอก ที่มารับวัคซีนป้องกันโรค ไม่ต้องใส่รหัสการตรวจร่างกายการตรวจสุขภาพ', ['/auditicd/rep4']); ?>
                <small class="badge  bg-info"><?php echo $chk04['result']; ?></small> <small class="badge  bg-info"><?php echo $chk04['target']; ?></small><br>

                <?php foreach ($chk05 as $chk05) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> ห้ามใช้รหัส T31.0-T31.9 ซึ่งเป็นรหัสบอกเปอร์เซ็นต์การเกิดแผลไหม้เป็นรหัสโรคหลัก', ['/auditicd/rep5']); ?>
                <small class="badge  bg-info"><?php echo $chk05['result']; ?></small> <small class="badge  bg-info"><?php echo $chk05['target']; ?></small><br>

                <?php foreach ($chk06 as $chk06) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> การให้รหัส V00-Y34 ต้องให้รหัสรวม 5 ตัวอักษรเสมอ', ['/auditicd/rep6']); ?>
                <small class="badge  bg-info"><?php echo $chk06['result']; ?></small> <small class="badge  bg-info"><?php echo $chk06['target']; ?></small><br>

                <?php foreach ($chk07 as $chk07) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> การให้รหัส Z47.0-Z47.9 และ Z48.0 – Z48.9 ต้องไม่ใช้ร่วมกับรหัสกลุ่ม S หรือ T ในการรักษาครั้งนี้', ['/auditicd/rep7']); ?>
                <small class="badge  bg-info"><?php echo $chk07['result']; ?></small> <small class="badge  bg-info"><?php echo $chk07['target']; ?></small><br>

                <?php foreach ($chk08 as $chk08) { ?>
                <?php } ?>
                <?= Html::a('<i class="fa  fa-database text-yellow"></i> รหัส ICD ที่เป็นรหัสแสดงความด้อยคุณภาพของสถานพยาบาล', ['/auditicd/rep8']); ?>
                <small class="badge  bg-info"><?php echo $chk08['result']; ?></small> <small class="badge  bg-info"><?php echo $chk08['target']; ?></small><br>
            </div>
        </div>
    </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>
