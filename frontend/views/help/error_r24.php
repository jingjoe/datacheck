
<?php
use yii\helpers\Url;
$this->title = Yii::t('app', 'การตรวจสอบและแก้ไข ERROR ข้อมูล SSOP สิทธิประกันสังคม');
$this->params['breadcrumbs'][] = ['label' => 'ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม', 'url' => ['/ssop/index']];

?>
<br>
<?= \yii2assets\pdfjs\PdfJs::widget([
    'width'=>'100%',
        'height'=> '600px',
  'url'=> Url::base().'/ssop/error_r24.pdf'
]); ?>
