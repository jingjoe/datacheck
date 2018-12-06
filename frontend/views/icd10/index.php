<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\Icd10Search */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'สืบค้นรหัสมาตรฐาน ICD-10';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="icd10-index">
     <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--    <p>
        <?= Html::a('Create Icd10', ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->
    <?php Pjax::begin(); ?>   
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
            'exportConfig' => [
                   GridView::CSV => ['label' => 'Export as CSV', 'filename' => 'Icd10check_'.date('Y-d-m')],
                   GridView::PDF => ['label' => 'Export as PDF', 'filename' => 'Icd10check_'.date('Y-d-m')],
                   GridView::EXCEL=> ['label' => 'Export as EXCEL', 'filename' => 'Icd10check_'.date('Y-d-m')],
                   GridView::TEXT=> ['label' => 'Export as TEXT', 'filename' => 'Icd10check_'.date('Y-d-m')],
                ],
        // set your toolbar
            'toolbar' =>  [
                ['content' => 
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'รีเซ็ต')])
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
            'icd10',
            'descriptions',
            'valid',
            'icd10who',
            'icd10tm',
            'icd10tmd',
            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>

<h3><b class="text-red">Note:</b></h3>
    <ol>
        <li>เหตุผล คือ  F=ยกเลิกใช้ T=ใช้</li>
        <li>ICD-10-WHO คือ Y=เป็นรหัสวินิจฉัยโรคของ ICD-10-WHO,N=ไม่เป็นรหัสวินิจฉัยโรคของ ICD-10-WHO</li>
        <li>ICD-10-TM คือ Y=เป็นรหัสวินิจฉัยโรคของ ICD-10-TM,N=ไม่เป็นรหัสวินิจฉัยโรคของ ICD-10-TM</li>
        <li>ICD-10-TMD คือ Y=เป็นรหัสวินิจฉัยโรคของ ICD-10 แพทย์แผนไทย,N=ไม่เป็นรหัสวินิจฉัยโรคของ ICD-10 แพทย์แผนไทย</li>
    </ol>

<?= \bluezed\scrollTop\ScrollTop::widget() ?>