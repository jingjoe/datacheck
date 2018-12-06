<?php
use yii\helpers\Html;

$this->title = 'ประวัติการปรับปรุงโปรแกรม';
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-book margin-r-5"></i> ประวัติการปรับปรุงโปรแกรม</h3>
    </div>
<!-- /.box-header -->
    <div class="box-body">
        <?php foreach ($data_view as $data) { ?>
        <strong><i class="fa fa-bell"></i> <?php echo $data['datetime']; ?></strong> <br>
             <p class="text-muted"><i class="fa fa-angle-double-right"></i> <?php echo $data['change']; ?> </p>
        <hr>
        <?php } ?>  
    </div>

</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>