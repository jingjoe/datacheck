<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class WomenController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pw.person_id) AS cc_pid
            FROM person_women_service   pws
            LEFT OUTER JOIN person_women pw ON pw.person_women_id=pws.person_women_id
            LEFT OUTER JOIN person p ON p.person_id=pw.person_id
            WHERE pws.service_date BETWEEN '$date_start' AND '$date_end'
            AND (pws.women_birth_control_id='' OR pws.women_birth_control_id IS NULL)";

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
        
        $sql_detail1 = "SELECT p.person_id,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,pws.service_date,d.name AS doc_name,pw.last_update,
            CONCAT(if(pws.women_birth_control_id='' OR pws.women_birth_control_id IS NULL ,'ไม่ระบุรหัสวิธีการคุมกำเนิดปัจจุบัน,',''))as err
            FROM person_women_service   pws
            LEFT OUTER JOIN person_women pw ON pw.person_women_id=pws.person_women_id
            LEFT OUTER JOIN person p ON p.person_id=pw.person_id
            LEFT OUTER JOIN doctor d ON d.code=pws.doctor_code
            WHERE pws.service_date BETWEEN '$date_start' AND '$date_end'
            AND (pws.women_birth_control_id='' OR pws.women_birth_control_id IS NULL)
            GROUP BY  pw.person_id
            ORDER BY  pws.service_date DESC";
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
