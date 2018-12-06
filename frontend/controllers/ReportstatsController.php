<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReportstatsController extends Controller{
    public $enableCsrfValidation = false;
    public function behaviors() {

        $role = 0;
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
        }
        
        $arr = [''];
        if ($role == 1 ) {
            $arr = ['rep5detail','rep6detail'];
        }
        if( $role == 2) {
             $arr = ['rep5detail','rep6detail'];
        }
        if( $role == 3) {
             $arr = ['rep5detail','rep6detail'];
        }

        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
                },
                'only' => ['rep5detail','rep6detail'],
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
        return $this->render('report1');
    }

    public function actionRep2() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date1 =  $sql_date['date'];
        $date2 = date('Y-m-d');

        $sql = "SELECT p.pttype_spp_id,ps.pttype_spp_name AS pttype
                ,COUNT(DISTINCT hn)AS Person
                ,COUNT(v.vn)AS visit
                ,SUM(v.income)AS sum_price
                ,SUM(CASE WHEN p.pttype_spp_id IN(3,4) THEN 1 ELSE 0 END) AS UC
                ,SUM(CASE WHEN p.pttype_spp_id IN(2) THEN 1 ELSE 0 END) AS SSS
                ,SUM(CASE WHEN p.pttype_spp_id IN(1) THEN 1 ELSE 0 END) AS OFC
                ,SUM(CASE WHEN p.pttype_spp_id IN(5) THEN 1 ELSE 0 END) AS FWF
                ,SUM(CASE WHEN p.pttype_spp_id IN(6,7) THEN 1 ELSE 0 END) AS AUX
                FROM vn_stat v
                INNER JOIN pttype p ON p.pttype=v.pttype
                INNER JOIN provis_instype pi ON pi.code=p.nhso_code
                INNER JOIN pttype_spp ps ON ps.pttype_spp_id=p.pttype_spp_id 
                INNER JOIN (SELECT code FROM icd101 WHERE ( code like 'a%' or code like 'b%' or code like 'c%'
                or code like 'd%' or code like 'e%' or code like 'f%' or code like 'g%' or code like 'h%' or code like 'i%'
                or code like 'j%' or code like 'k%' or code like 'l%' or code like 'm%' or code like 'n%' or code like 'o%'
                or code like 'p%' or code like 'q%' or code like 'r%' or code like 's%' or code like 't%' or code like 'u%'
                or code like 'v%' or code like 'w%' or code like 'x%' or code like 'y%'
                or code in ('z480','z012','z017','z016','z242','z235','z436','z434','z390') or code between 'z20' and 'z299')) pdx_code ON pdx_code.code=v.pdx
                WHERE v.vstdate BETWEEN  '$date1'and '$date2'
                GROUP BY ps.pttype_spp_name
                ORDER BY visit DESC ";


        $data = Yii::$app->db2->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('report2', ['dataProvider' => $dataProvider, 'chart' => $data,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
    }

    public function actionRep3() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date1 =  $sql_date['date'];
        $date2 = date('Y-m-d');

        $se = "5";
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
            $se = $_POST['se'];
        }

        $sql = "SELECT a.pdx,i.name AS icdname,COUNT(a.pdx) AS pdx_count,COUNT(DISTINCT a.hn) AS hn_count,COUNT(DISTINCT a.vn) AS visit_count
        FROM vn_stat a
        LEFT OUTER JOIN icd101 i ON i.code=a.main_pdx
        WHERE a.vstdate BETWEEN '$date1'and '$date2'
        AND a.pdx<>'' AND a.pdx IS NOT NULL
        AND a.pdx NOT LIKE 'z%'
        GROUP BY a.pdx,i.name
        ORDER BY pdx_count DESC
        LIMIT $se ";

        $data = Yii::$app->db2->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('report3', ['dataProvider' => $dataProvider, 'chart' => $data,'sql' => $sql,'date1' => $date1, 'date2' => $date2,'se' => $se]);
    }
    
    public function actionRep4() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date1 =  $sql_date['date'];
        $date2 = date('Y-m-d');

        $se = "5";
        if (Yii::$app->request->isPost) {
            $date1 = $_POST['date1'];
            $date2 = $_POST['date2'];
            $se = $_POST['se'];
        }

        $sql = "SELECT a.pdx,i.name AS icdname,COUNT(a.pdx) AS pdx_count,COUNT(DISTINCT a.hn) AS hn_count,COUNT(DISTINCT a.an) AS visit_count
        FROM an_stat a
        LEFT OUTER JOIN icd101 i ON i.code=a.pdx
        WHERE a.dchdate BETWEEN  '$date1'and '$date2'
        AND a.pdx<>'' AND a.pdx IS NOT NULL
        AND a.pdx NOT LIKE 'z%'
        GROUP BY a.pdx,i.name
        ORDER BY pdx_count DESC
        LIMIT $se ";

        $data = Yii::$app->db2->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('report4', ['dataProvider' => $dataProvider, 'chart' => $data,'sql' => $sql,'date1' => $date1, 'date2' => $date2,'se' => $se]);
    } 
}
