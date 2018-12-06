<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
$username = 'ผู้ใช้งานทั่วไป';
?>
<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                   <p>
                          <?php 
                                if(Yii::$app->user->isGuest)  {
                                    print("สวัสดี ". '(' . $username . ')');
                                } else {
                            
                                    print("สวัสดี". '(' . Html::encode(Yii::$app->user->identity->username) . ')');
                            }?>
                
                
                </p>

                <a href="#"><i class="fa fa-circle text-success"></i> ออนไลน์</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => '', 'options' => ['class' => 'header']],
                        ['label' => 'จัดการผู้ใช้งาน', 'icon' => 'fa fa-users', 'url' => ['user/index']],
                        ['label' => 'นำเข้า Drug Catalogue', 'icon' => 'fa  fa-send', 'url' => ['fileimport/index']],
                        ['label' => 'สถิติผู้ใช้งาน', 'icon' => 'fa fa-area-chart', 'url' => ['user/stat']],
                        ['label' => 'ประวัติการปรับปรุง', 'icon' => 'fa  fa-align-right', 'url' => ['history/index']],
                    ['label' => '', 'options' => ['class' => 'box-header']], 
                        ['label' => 'ตั้งเวลาประมวลผล', 'icon' => 'fa fa-clock-o', 'url' => ['syssettime/index']],
                        ['label' => 'ประมวลผลข้อมูล', 'icon' => 'fa fa-database', 'url' => ['processreport/index']],
                        ['label' => 'สถานะการประมวลผล', 'icon' => 'fa fa-warning', 'url' => ['execute/index']],
                    ['label' => '', 'options' => ['class' => 'box-header']], 
                        ['label' => 'กลับสู่หน้าบ้าน', 'icon' => 'fa fa-home','url' => Yii::$app->urlManagerFrontend->createUrl(['site/index']),'visible' => !Yii::$app->user->isGuest],
                        ['label' => 'เข้าสู่ระบบ', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                ],
            ]
        ) ?>

    </section>

</aside>
