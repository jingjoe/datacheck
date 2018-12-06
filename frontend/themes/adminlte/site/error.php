<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $message;
?>
<section class="content">

    <div class="error-page">
        <h2 class="headline text-info"><i class="fa fa-warning text-yellow"></i></h2>

        <div class="error-content">
            <h3><?= $name ?></h3>
            <h3><p><font color="red"><?= nl2br(Html::encode($message)) ?></font><font color="blue">"ให้ใช้งานในส่วนนี้ !"</font></p></h3>

            <p>
               กรุณา <?= Html::a('เข้าสู่ระบบ', ['site/login']) ?> เพื่อใช้งาน หรือ <a href='<?= Yii::$app->homeUrl ?>'>กลับสู่หน้าหลัก</a> หรือติดต่อ <?= Html::a('ผู้ดูแลระบบ', ['site/about']) ?>
            </p>

            <form class='search-form'>
                <div class='input-group'>
                    <input type="text" name="search" class='form-control' placeholder="Search"/>

                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-primary"><i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section>
