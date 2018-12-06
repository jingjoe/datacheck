<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PrenatalController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pa.person_anc_id) AS cc_pid
                FROM person_anc pa
                INNER JOIN thalassaemia_result tr ON tr.thalassaemia_result_id=pa.thalassaemia_result_id
                LEFT OUTER JOIN person_anc_service pas ON pas.person_anc_id=pa.person_anc_id
                LEFT OUTER JOIN person_anc_lab al ON al.person_anc_service_id=pas.person_anc_service_id
                WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
                AND (pa.preg_no= '' OR pa.preg_no IS NULL
                OR pa.lmp ='' OR pa.lmp IS NULL
                OR pa.blood_vdrl1_result='' OR pa.blood_vdrl1_result IS NULL
                OR pa.blood_hiv1_result='' OR pa.blood_hiv1_result IS NULL
                OR (al.anc_lab_id='8' AND al.anc_lab_result='' OR al.anc_lab_result IS NULL )
                OR pa.thalassaemia_result_id='' OR pa.thalassaemia_result_id IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT pa.person_id,CONCAT(p.pname,p.fname,' ',p.lname) full_name ,
        pas.anc_service_date,pa.anc_register_staff,pa.last_update ,
        CONCAT(if(pa.preg_no= '' OR pa.preg_no IS NULL ,'ไม่ระบุครรภ์ที่,','')
        ,if(pa.lmp ='' OR pa.lmp IS NULL  ,'ไม่ระบุวันแรกของการมีประจำเดือนครั้งสุดท้าย,','')
        ,if(pa.blood_vdrl1_result='' OR pa.blood_vdrl1_result IS NULL  ,'ไม่ระบุผลการตรวจ VDRL_RS,','')
        ,if(pa.blood_hiv1_result='' OR pa.blood_hiv1_result IS NULL  ,'ไม่ระบุผลการตรวจ HIV_RS,','')
        ,if(al.anc_lab_id='8' AND al.anc_lab_result='' OR al.anc_lab_result IS NULL  ,'ไม่ระบุผลการตรวจ HB_RS,','')
        ,if(pa.thalassaemia_result_id='' OR pa.thalassaemia_result_id IS NULL  ,'ไม่ระบุผลการตรวจ THALASSAEMIA,',''))as err
        FROM person_anc pa
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        INNER JOIN thalassaemia_result tr ON tr.thalassaemia_result_id=pa.thalassaemia_result_id
        LEFT OUTER JOIN person_anc_service pas ON pas.person_anc_id=pa.person_anc_id
        LEFT OUTER JOIN person_anc_lab al ON al.person_anc_service_id=pas.person_anc_service_id
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND (pa.preg_no= '' OR pa.preg_no IS NULL
        OR pa.lmp ='' OR pa.lmp IS NULL
        OR pa.blood_vdrl1_result='' OR pa.blood_vdrl1_result IS NULL
        OR pa.blood_hiv1_result='' OR pa.blood_hiv1_result IS NULL
        OR (al.anc_lab_id='8' AND al.anc_lab_result='' OR al.anc_lab_result IS NULL )
        OR pa.thalassaemia_result_id='' OR pa.thalassaemia_result_id IS NULL)
        GROUP BY pa.person_anc_id
        ORDER BY pa.anc_register_date DESC";
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
}
