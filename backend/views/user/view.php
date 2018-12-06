<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = 'ชื่อผู้ใช้งาน : ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'ผู้ใช้งานระบบ', 'url' => ['index']];

?>
<div class="user-view">
    <p>
        <?= Html::a('<i class="glyphicon glyphicon-edit"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn btn-primary btn-flat']) ?>
        <?=
        Html::a('<i class="glyphicon glyphicon-trash"></i> ลบ', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger btn-flat',
            'data' => [
                'confirm' => 'คุณแน่ใจหรือว่าต้องการลบรายการนี้หรือไม่ ?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>


       <div class="box">
        <div class="box-header with-border">
            <h3><?= Html::encode($this->title) ?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-angle-double-down"></i></button>
            </div>
        </div>
        <div class="box-body">
             <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'full_name',
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email:email',
            'user_role.role_desc',
            'user_status.status_desc',
            'created_at',
            'updated_at',
        ],
    ]) ?>
        </div>
    </div>
</div>
<?= \bluezed\scrollTop\ScrollTop::widget() ?>