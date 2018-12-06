
<?php
use yii\helpers\Url;
$this->title = Yii::t('app', 'โครงสร้างมาตรฐานข้อมูลด้านสุขภาพ 43 แฟ้ม');
$this->params['breadcrumbs'][] = ['label' => 'กลับหน้าเดิม', 'url' => ['labor/index']];

?>
<br>
<?= \yii2assets\pdfjs\PdfJs::widget([
    'width'=>'100%',
        'height'=> '600px',
  'url'=> Url::base().'/43f/labor.pdf'
]); ?>
