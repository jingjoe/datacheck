<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$time = time();
$username = 'ผู้ใช้งานทั่วไป';
$image = Html::img('images/datacheck.png');
?>

<header class="main-header">
<?= Html::a($image.Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle bg-yellow" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
        <!-- Accept -->
                <li class="dropdown notifications-menu">
                    <?php $member = Yii::$app->db->createCommand("SELECT COUNT(id) AS user_total FROM user WHERE role<>'99' AND status='2' ")->queryScalar(); ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-user"></i>
                      <span class="label label-success"><?php echo $member;?></span>
                    </a>
                </li>
        <!-- UnAccept -->
                <li class="dropdown notifications-menu">
                    <?php $member_un = Yii::$app->db->createCommand("SELECT COUNT(id) AS user_total FROM user WHERE  status<>'2'")->queryScalar(); ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-user-times"></i>
                      <span class="label label-warning"><?php echo $member_un;?></span>
                    </a>
                </li>
        <!-- MemberAll -->             
                <li class="dropdown tasks-menu">
                    <?php $member_all = Yii::$app->db->createCommand("SELECT COUNT(id) AS user_total FROM user ")->queryScalar(); ?>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                      <i class="fa fa-users"></i>
                      <span class="label label-info"><?php echo $member_all;?></span>
                    </a>
                </li>
  
                <li class="dropdown userl user-menu bg-yellow">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= $directoryAsset ?>/img/user_datacheck.jpg" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"> 
                            <?php 
                                if(Yii::$app->user->isGuest)  {
                                    print('[' . $username . ']');
                                } else {
                            
                                    print('[' . Html::encode(Yii::$app->user->identity->username) . ']');
                            }?>
                     
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= $directoryAsset ?>/img/user_datacheck.jpg" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?php 
                                    if(Yii::$app->user->isGuest)  {
                                        print($username);
                                } else {
                            
                                        print(Html::encode(Yii::$app->user->identity->username));
                                }?>
                                <small><?= Yii::$app->formatter->asDateTime($time, 'php:Y-m-d H:i:s'); ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?=Html::a( '<i class="fa fa-search"></i> มีอะไรใหม่', ['/historyview/index'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat'])?>
                            </div>
                            <div class="pull-right">
                                <?=Html::a( '<i class="fa fa-sign-out"></i> ออกจากระบบ', ['/site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat'])?>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>