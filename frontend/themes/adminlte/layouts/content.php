<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;
?>

<div class="content-wrapper">
    <section class="content-header">
        <?php if (isset($this->blocks['content-header'])) { ?>
            <h1><?= $this->blocks['content-header'] ?></h1>
        <?php } else { ?>
        <?php } ?>

        <?=
        Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
    </section>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
            <?php $cc = Yii::$app->db->createCommand("SELECT COUNT(id) FROM session_frontend_user")->queryScalar(); ?>
        <strong>ผู้เยี่ยมชม <?= $cc;?> ครั้ง </strong>
    </div>
        <?php
            $ver = file_get_contents(Yii::getAlias('@webroot/version/version.txt'));
            $ver = explode(',', $ver);
        ?>
                   
    <strong>Copyright &copy; 2016 <a href="http://www.pakphayunhospital.net" target="_blank">Pakphayun Hospital</a>.</strong> All rights
    reserved <b>Version : <?= $ver[0] ?> </b>
    
</footer>

