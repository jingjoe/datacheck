<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReportController extends Controller{

    public function actionIndex(){
        return $this->render('index');
    }
   
}
