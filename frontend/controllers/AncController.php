<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AncController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT p.person_id)AS cc_pid
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        LEFT OUTER JOIN person_anc_other_precare po ON po.person_anc_id=pa.person_anc_id
        LEFT OUTER JOIN ovst_seq o ON o.vn=pas.vn
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND (pas.anc_service_date is null or pas.anc_service_date= '' 
        OR pa.preg_no is null or pa.preg_no =''
        OR pas.pa_week is null or pas.pa_week='')";
        
        $sql_chart2 = "SELECT COUNT(t.cc) AS tt 
        FROM (SELECT  COUNT(*) AS cc
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND pa.discharge<>'Y'
        GROUP BY pas.person_anc_id,pas.anc_service_date
        HAVING COUNT(pas.anc_service_date) > 1) AS t";
         
        $sql_chart3 = "SELECT COUNT(DISTINCT pa.person_id)AS cc_pid
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND pa.force_complete_export<>'Y' AND  pa.discharge<>'Y' ";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');

        $sql_detail1 = "SELECT p.person_id,p.cid
        ,CONCAT(p.pname,p.fname,' ',p.lname) full_name
        ,pas.anc_service_date AS date_serv,pa.last_update,
        CONCAT(if(pa.preg_no is null or pa.preg_no ='' ,'ไม่ระบุครรภ์ที่,','')
        ,if(pas.pa_week is null or pas.pa_week=''  ,'ไม่ระบุอายุครรภ์,','') )as err
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        LEFT OUTER JOIN person_anc_other_precare po ON po.person_anc_id=pa.person_anc_id
        LEFT OUTER JOIN ovst_seq o ON o.vn=pas.vn
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND (pas.anc_service_date is null or pas.anc_service_date= '' 
        OR pa.preg_no is null or pa.preg_no =''
        OR pas.pa_week is null or pas.pa_week='') 
        GROUP BY p.person_id ";
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

        $sql_detail2 = "SELECT p.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,
            GROUP_CONCAT(pas.anc_service_date,' , ',pas.anc_service_time) AS datetime ,COUNT(pas.person_anc_id) AS cc
            FROM person_anc_service pas
            LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
            LEFT OUTER JOIN person p ON p.person_id=pa.person_id
            WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
            AND pa.discharge<>'Y'
            GROUP BY pas.person_anc_id,pas.anc_service_date
            HAVING COUNT(pas.anc_service_date) > 1";
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

        $sql_detail3 = "SELECT p.person_id,p.cid ,CONCAT(p.pname,p.fname,' ',p.lname) full_name ,pas.anc_service_date AS date_serv,
            pa.last_update,pa.force_complete_export AS exp,pa.discharge,
            CONCAT(if(pa.force_complete_export<>'Y' ,'ไม่คลิกบังคับส่งข้อมูล 18 แฟ้ม,','') )as err
            FROM person_anc_service pas
            LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
            LEFT OUTER JOIN person p ON p.person_id=pa.person_id
            LEFT OUTER JOIN person_anc_other_precare po ON po.person_anc_id=pa.person_anc_id
            LEFT OUTER JOIN ovst_seq o ON o.vn=pas.vn
            WHERE pas.anc_service_date
            BETWEEN '$date_start' AND '$date_end' 
            AND pa.force_complete_export<>'Y' AND  pa.discharge<>'Y'
            GROUP BY p.person_id ";
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

}
