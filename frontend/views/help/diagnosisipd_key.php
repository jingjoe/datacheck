<?php
use yii\helpers\Url;
$this->title = Yii::t('app', 'การบันทึกข้อมูลแฟ้ม DIAGNOSIS_IPD');
$this->params['breadcrumbs'][] = ['label' => 'กลับหน้าเดิม', 'url' => ['diagnosisipd/index']];

?>
<br>
<?= \yii2assets\pdfjs\PdfJs::widget([
    'width'=>'100%',
        'height'=> '600px',
  'url'=> Url::base().'/documents/key_diagnosis_ipd.pdf'
]); ?>
