<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$time = time();
$username = 'ผู้ใช้งานทั่วไป';
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

     
                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                   <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="user-image" alt="User Image"/>
                         <span class="hidden-xs"> 
                            <?php 
                                if(Yii::$app->user->isGuest)  {
                                    print("ยินดีต้อนรับ ". '(' . $username . ')');
                                } else {
                            
                                    print("ยินดีต้อนรับ". '(' . Html::encode(Yii::$app->user->identity->username) . ')');
                            }?>
                     
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle"
                                 alt="User Image"/>

                              <p>
                                    <?php 
                                if(Yii::$app->user->isGuest)  {
                                    print("". '(' . $username . ')');
                                } else {
                            
                                    print("". '(' . Html::encode(Yii::$app->user->identity->username) . ')');
                            }?>
                                <small><?= Yii::$app->formatter->asDateTime($time, 'php:Y-m-d H:i:s'); ?></small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
<!--                                <a href="#" class="btn btn-default btn-flat">ประวัติใช้งาน</a>-->
                            </div>
                            <div class="pull-right">
                                <?= Html::a('<i class="fa fa-sign-out"></i> ออกจากระบบ',['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']) ?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
