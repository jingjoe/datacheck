<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\FileimportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ไฟล์ DrugCatalogue';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fileimport-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-upload"></i> อับโหลดไฟล์', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'headerRowOptions' => ['style' => 'background-color:#cccccc'],
        'panel' => [
            'type' => GridView::TYPE_DEFAULT,
            'after' => 'วันที่ประมวลผล '.date('Y-m-d H:i:s').' น.',
            'footer'=>false
        ],
        'responsive' => true,
        'hover' => true,

    // set your toolbar
        'toolbar' =>  [
            ['content' =>
                Html::a('<i class="glyphicon glyphicon-trash"></i>', ['fileimport/truncate'], ['data-pjax' => 0, 'class' => 'btn btn-danger', 'title' => Yii::t('app', 'ลบข้อมูล')]). ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
            ],
            '{toggleData}',
            '{export}',
        ],
    // set export properties
        'export' => false,
        'pjax' => true,
        'pjaxSettings' => [
            'neverTimeout' => true,
            'beforeGrid' => '',
            'afterGrid' => '',
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            'file:ntext',
            //'token_upload',
            //'create_date',
            [
            'attribute'=>'create_date',
            'value' => function ($model, $index, $widget) {
                return Yii::$app->formatter->asDate($model->create_date);
            },
            'filterType' => GridView::FILTER_DATE,
            'filterWidgetOptions' => [
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'autoclose' => true,
                    'todayHighlight' => true,
                ]
            ],
            'width' => '200px',
            'hAlign' => 'center',
            ],
            //'modify_date',
            //['class' => 'yii\grid\ActionColumn'], 
            [
                'attribute' => 'Send Data',
               //'label' => 'Send Data',
                'format' => 'raw',
                'value' => function($data) {
                    return
                        Html::a(' Send Data', ['fileimport/excel', 'type' => 'files', 'id' => $data->id], ['class' => 'fa  fa-database']);
                }
            ],
            [
                    'class'=>'\kartik\grid\DataColumn',
                    'attribute'=>'statusname',
                    'format' => 'raw',
                    'header' => 'สถานะการส่ง',
                   // 'data' => ArrayHelper::map(Drugcatalogue::find()->all(),'file_id','status'),
                    'value'=>function($model,$url){
                        if($model['statusname'] == 'success')
                        {
                            //return 'Yes';
                              return '<i class="fa fa-check-circle"> นำเข้าข้อมูลเรียบร้อยแล้ว</i>';
                        }
                        else
                        {
                           return '<i class="fa fa-times-circle"> ยังไม่นำเข้าข้อมูล</i>';
                        }
                    }
            ],
            [
                'class' => '\kartik\grid\ActionColumn',
                'header' => 'Action', 
                'deleteOptions' => ['label' => '<i class="glyphicon glyphicon-remove"></i>']
            ],
                    
        ],
    ]); ?>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>