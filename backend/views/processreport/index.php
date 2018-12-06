
<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
$this->title = 'ประมวลผลข้อมูล';
$this->params['breadcrumbs'][] = $this->title;


$this->registerCss(".btn-xlarge {
        padding: 18px 28px;
        font-size: 20px; //change this to your desired size
        line-height: normal;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
    }");

?>
<div class="process-index">

    <center> 
        <div id="res" style="display: none">
            <img src="images/process.gif">
        </div>
    </center>
    <br>
    <div class="row">
        <div class="col-sm-3">                
            <button class="btn bg-navy btn-block btn-xlarge" id="btn_oppp"> 
                <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> ประมวลผล 43 แฟ้ม
            </button>
        </div>

        <div class="col-sm-3">
            <button class="btn bg-purple btn-block btn-xlarge" id="btn_rpt"> 
                <i class="fa fa-spinner fa-spin fa-1x fa-fw"></i> ประมวลผลรายงาน
            </button>
        </div>
    </div>
</div>


<?php

$route_exec = Yii::$app->urlManager->createUrl('processreport/exec');
$route_oppp = Yii::$app->urlManager->createUrl('processreport/oppp');


$script1 = <<< JS
        
 $('#btn_rpt').on('click', function () {
          $('#res').toggle();   
    $.ajax({
       url: "$route_exec",       
       success: function(data) {
           $('#res').toggle();               
            alert(data+' ประมวลผลสำเร็จ'); 
       }
    });
 });
               
JS;

$this->registerJs($script1);
?>

