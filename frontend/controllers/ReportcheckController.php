<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;



class ReportcheckController extends Controller{
    
    public $enableCsrfValidation = false;
    public function behaviors() {

        $role = 0;
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
        }
        
        $arr = [''];
        if ($role == 1 ) {
            $arr = ['index','rep1detail','rep2detail'];
        }
        if( $role == 2) {
             $arr = ['index','rep1detail','rep2detail'];
        }
        if( $role == 3) {
             $arr = ['index','rep1detail','rep2detail'];
        }

        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
                },
                'only' => ['rep1detail','rep2detail'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => $arr,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => $arr,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionRep1() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date1 =  $sql_date['date'];
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT spclty,dep,COUNT(DISTINCT vn) AS cc
                FROM tmp_nodiag_opd
                WHERE vstdate BETWEEN '$date1'and '$date2'
                GROUP BY dep
                ORDER BY cc DESC";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('report1', ['dataProvider' => $dataProvider, 'chart' => $data,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
    } 
    
    public function actionRep1detail($id=NULL,$date1=NULL,$date2=NULL) {
        
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }
        $sql = "SELECT dep,department,hn,full_name,CONCAT(vstdate,' ',vsttime) AS date_t,cc,doc_name
                FROM tmp_nodiag_opd
                WHERE vstdate BETWEEN '$date1'and '$date2' 
                AND spclty=$id 
                ORDER BY vstdate";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep1detail', [
            'dataProvider' => $dataProvider,
            'date1' => $date1,
            'date2' => $date2,
            'id' => $id]);       
    }
    
    public function actionRep2() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date1 =  $sql_date['date'];
        $date2 = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }

        $sql = "SELECT ward,ward_name,COUNT(DISTINCT an) AS cc
                FROM tmp_nodiag_ipd
                WHERE regdate BETWEEN '$date1'and '$date2'
                GROUP BY ward
                ORDER BY cc DESC";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('report2', ['dataProvider' => $dataProvider, 'chart' => $data,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
    } 
    
    public function actionRep2detail($id=NULL,$date1=NULL,$date2=NULL) {
        
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
        }
        $sql = "SELECT ward_name,hn,an,full_name,CONCAT(regdate,' ',regtime) AS datetime ,prediag,doc_name
                FROM tmp_nodiag_ipd
                WHERE regdate BETWEEN '$date1'and '$date2' 
                AND ward=$id
                ORDER BY regdate ";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep2detail', [
            'dataProvider' => $dataProvider,
            'date1' => $date1,
            'date2' => $date2,
            'id' => $id]);       
    }
}

