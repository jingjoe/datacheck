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
        <?=\yii2mod\alert\Alert::widget()?>
        <?= $content ?>
    </section>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <?php
            $ver = file_get_contents(Yii::getAlias('@webroot/version/version.txt'));
            $ver = explode(',', $ver);
        ?>
        <strong>Version : <?= $ver[0] ?> </strong>
    </div>
     
                   
    <strong>Copyright &copy; 2016 <a href="http://www.pakphayunhospital.net" target="_blank">Pakphayun Hospital</a>.</strong> All rights reserved 
    
</footer>
