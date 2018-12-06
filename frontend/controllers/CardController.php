<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CardController extends Controller{
    public $enableCsrfValidation = false;
    public function behaviors() {

        $role = 0;
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
        }
        
        $arr = [''];
        if ($role == 1 ) {
            $arr = ['index','detail1','detail2','detail3','detail4','detail5',
                    'detail6','detail7','detail8','detail9','detail10'];
        }
        if( $role == 2) {
             $arr = ['index','detail1','detail2','detail3','detail4','detail5',
                    'detail6','detail7','detail8','detail9','detail10'];
        }
        if( $role == 3) {
             $arr = ['index','detail1','detail2','detail3','detail4','detail5',
                    'detail6','detail7','detail8','detail9','detail10'];
        }

        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
                },
                'only' => ['detail1', 'detail2', 'detail3', 'detail4', 'detail5',
                           'detail6','detail7','detail8','detail9','detail10'],
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
    public function actionIndex() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_chart1 = "SELECT COUNT(p.person_id) AS cc_pid
        FROM person p
        LEFT OUTER JOIN vn_stat v ON v.cid=p.cid
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (p.pttype='' OR p.pttype IS NULL)";
        
        $sql_chart2 = "SELECT COUNT(p.hn) AS cc_hn
        FROM patient p
        LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (p.pttype='' OR p.pttype IS NULL)";
        
        $sql_chart3 = "SELECT COUNT(v.hn) AS cc_hn
        FROM vn_stat v
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (v.pttype='' OR v.pttype IS NULL)";
        
        $sql_chart4 = "SELECT COUNT(a.hn) AS cc_an
        FROM an_stat a
        WHERE a.dchdate  between '$date_start' and '$date_end'
        AND (a.pttype='' OR a.pttype IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db2->createCommand($sql_chart4)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT p.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,p.pttype,p.last_update,
        CONCAT(if(p.pttype='' OR p.pttype IS NULL ,'ไม่ระบุประเภทสิทธิการรักษา,',''))as err
        FROM person p
        LEFT OUTER JOIN vn_stat v ON v.cid=p.cid
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (p.pttype='' OR p.pttype IS NULL)
        ORDER BY v.vstdate DESC";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
    public function actionDetail2() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail2 = "SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,p.pttype,p.last_update,
        CONCAT(if(p.pttype='' OR p.pttype IS NULL ,'ไม่ระบุประเภทสิทธิการรักษา,',''))as err
        FROM patient p
        LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (p.pttype='' OR p.pttype IS NULL)
        ORDER BY v.vstdate DESC";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
    
    public function actionDetail3() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail3 = "SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,p.pttype,p.last_update,
        CONCAT(if(p.pttype='' OR p.pttype IS NULL ,'ไม่ระบุประเภทสิทธิการรักษา,',''))as err
        FROM patient p
        LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
        WHERE v.vstdate  between '$date_start' and '$date_end'
        AND (v.pttype='' OR v.pttype IS NULL)
        ORDER BY v.vstdate DESC";
        $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);

        return $this->render('detail3', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }
    public function actionDetail4() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail4 = "SELECT p.hn,a.an,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,a.dchdate,a.pttype,p.last_update,
        CONCAT(if(a.pttype='' OR a.pttype IS NULL ,'ไม่ระบุประเภทสิทธิการรักษา,',''))as err
        FROM patient p
        LEFT OUTER JOIN an_stat a ON a.hn=p.hn
        WHERE a.dchdate  between '$date_start' and '$date_end'
        AND (a.pttype='' OR a.pttype IS NULL)
        ORDER BY  a.dchdate DESC";

        $data4 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data4,
        ]);

        return $this->render('detail4', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4]);
    }
}

