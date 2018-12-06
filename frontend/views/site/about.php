<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'ติดต่อ';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <colgroup>
            <col class="col-md-1">
            <col class="col-md-10">
        </colgroup>
        <thead>
            <tr>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">
                    <?php echo Html::img('@web/images/line_id.png') ?> &nbsp;&nbsp; &nbsp;
                </th>
                <td>
                    <strong>ชื่อ-นามสกุล : วิเชียร นุ่นศรี</strong> <br>
                    <strong>ตำแหน่ง : นักวิชาการคอมพิวเตอร์</strong> <br>
                    <b> การศึกษา : </b> ปริญญาตรี สาขาเทคโนโลยีคอมพิวเตอร์ มหาวิทยาลัยเทคโนโลยีราชมงคลกรุงเทพ <br>
                    <b> ติดต่อ : <?= Html::a(' โรงพยาบาลปากพะยูน อำเภอปากพะยูน จังหวัดพัทลุง', 'http://www.pakphayunhospital.net/', ['target' => '_blank']) ?>  E-mail : <a href="mailto:#">wichian.joe@gmail.com </a> Phone : (AIS)087-2888200  <br>
                    <b> ความรู้ความสามารถ : </b> Web Application ,Web Server ,Linux Server ,Windows Server ,MySQL ,Network design ,Computer technical officer ,Graphic design   <br>
                </td>
            </tr>
            <tr>
                <th scope="row"></th>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>