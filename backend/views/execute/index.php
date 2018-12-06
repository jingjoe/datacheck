<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'สถานะการประมวลผล';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<?php
$script = <<< JS
$(document).ready(function() {
    setInterval(function(){ $("#refreshButton").click(); }, 1000);
});
JS;
$this->registerJs($script);
?>
<?php Pjax::begin(); ?>


<div class="box">
    <div class="box-header with-border">
        <h1>
            <?php
            date_default_timezone_set('Asia/Bangkok');
            $today_date = date("d-M-Y");
            $today_time = date("h:i:s: a");
            echo "<b>Today is </b> $today_date $today_time";
            ?>
        </h1>
        <?= Html::a("Refresh", ['execute/index'], ['class' => 'btn btn-primary', 'id' => 'refreshButton']) ?>
      
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
        </div>
    </div>
    <div class="box-body">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
            ],
            'responsive' => true,
            'hover' => true,
            'pjax' => true,
            'pjaxSettings' => [
                'neverTimeout' => true,
                'beforeGrid' => '',
                'afterGrid' => '',
            ],
            'columns' => [
                [
                    'class' => 'yii\grid\SerialColumn'
                ],
                [
                    'attribute' => 'ID',
                    'header' => 'ID'
                ],
                [
                    'attribute' => 'USER',
                    'header' => 'USER'
                ],
                [
                    'attribute' => 'HOST',
                    'header' => 'HOST'
                ],
                [
                    'attribute' => 'DB',
                    'header' => 'DB'
                ],
                [
                    'attribute' => 'COMMAND',
                    'header' => 'COMMAND'
                ],
                [
                    'attribute' => 'TIME',
                    'header' => 'TIME'
                ],
                [
                    'attribute' => 'STATE',
                    'header' => 'STATE'
                ],
                [
                    'attribute' => 'INFO',
                    'header' => 'INFO'
                ],
            ]
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>
