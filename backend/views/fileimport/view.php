<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model backend\models\Fileimport */

$this->title = $model->file;
$this->params['breadcrumbs'][] = ['label' => 'ไฟล์ DrugCatalogue', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileimport-view">
    <p>
        <?= Html::a('<i class="glyphicon glyphicon-edit"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('<i class="glyphicon glyphicon-trash"></i> ลบ', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'คุณแน่ใจหรือว่าต้องการลบรายการนี้หรือไม่ ?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'headerRowOptions' => ['style' => 'background-color:#cccccc'],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
            'footer'=>false
        ],
        'responsive' => true,
        'hover' => true,
        'exportConfig' => [
               GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'DrugCatalogue_Approved_'.date('Y-d-m')],
               GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'DrugCatalogue_Approved_'.date('Y-d-m')],
               GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'DrugCatalogue_Approved_'.date('Y-d-m')],
               GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'DrugCatalogue_Approved_'.date('Y-d-m')],
            ],
    // set your toolbar
        'toolbar' =>  [
            ['content' => 
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['view','id' => $model->id], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
            ],
            '{toggleData}',
            '{export}',
        ],
    // set export properties
        'export' => [
            'fontAwesome' => true
        ],
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'beforeGrid' => '',
            'afterGrid' => '',
        ],
     'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'HOSPDRUGCODE',
            'value' => function($model){
                return $model[0][0];
            }
        ],
        [
            'label' => 'PRODUCTCAT',
            'value' => function($model){
                return $model[0][1];
            }
        ],
        [
            'label' => 'TMTID',
            'value' => function($model){
                return $model[0][2];
            }
        ],
        [
            'label' => 'SPECPREP',
            'value' => function($model){
                return $model[0][3];
            }
        ],
        [
            'label' => 'GENERICNAME',
            'value' => function($model){
                return $model[0][4];
            }
        ],
        [
            'label' => 'TRADENAME',
            'value' => function($model){
                return $model[0][5];
            }
        ],
        [
            'label' => 'DFSCODE',
            'value' => function($model){
                return $model[0][6];
            }
        ],
        [
            'label' => 'DOSAGEFORM',
            'value' => function($model){
                return $model[0][7];
            }
        ],
        [
            'label' => 'STRENGTH',
            'value' => function($model){
                return $model[0][8];
            }
        ],
        [
            'label' => 'CONTENT',
            'value' => function($model){
                return $model[0][9];
            }
        ],  
        [
            'label' => 'UNITPRICE',
            'value' => function($model){
                return $model[0][10];
            }
        ],
        [
            'label' => 'DISTRIBUTOR',
            'value' => function($model){
                return $model[0][11];
            }
        ],
        [
            'label' => 'MANUFACTURER',
            'value' => function($model){
                return $model[0][12];
            }
        ],
        [
            'label' => 'ISED',
            'value' => function($model){
                return $model[0][13];
            }
        ],
        [
            'label' => 'NDC24',
            'value' => function($model){
                return $model[0][14];
            }
        ],
        [
            'label' => 'PACKSIZE',
            'value' => function($model){
                return $model[0][15];
            }
        ],
        [
            'label' => 'PACKPRICE',
            'value' => function($model){
                return $model[0][16];
            }
        ],
        [
            'label' => 'UPDATEFLAG',
            'value' => function($model){
                return $model[0][17];
            }
        ],
        [
            'label' => 'DATECHANGE',
            'value' => function($model){
                return $model[0][18];
            }
        ],
        [
            'label' => 'DATEUPDATE',
            'value' => function($model){
                return $model[0][19];
            }
        ],
        [
            'label' => 'DATEEFFECTIVE',
            'value' => function($model){
                return $model[0][20];
            }
        ],
        [
            'label' => 'ISED_APPROVED',
            'value' => function($model){
                return $model[0][21];
            }
        ],
        [
            'label' => 'NDC24_APPROVED',
            'value' => function($model){
                return $model[0][22];
            }
        ],
        [
            'label' => 'DATE_APPROVED',
            'value' => function($model){
                return $model[0][23];
            }
        ],
        [
            'label' => 'ISED_STATUS',
            'value' => function($model){
                return $model[0][24];
            }
        ]
    ]
])?>

</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>