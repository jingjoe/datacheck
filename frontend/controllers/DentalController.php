<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DentalController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT dc.vn) AS cc_vn 
                    FROM dental_care dc
                    LEFT OUTER JOIN vn_stat v ON v.vn=dc.vn
                    WHERE dc.vn IN (SELECT vn FROM dtmain WHERE vstdate  BETWEEN '$date_start' AND DATE(NOW()) ) 
                    AND (dc.dental_care_type_id='' OR dc.dental_care_type_id IS NULL
                    OR dc.dental_care_service_place_type_id=''OR dc.dental_care_service_place_type_id IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'chart1' => $chart1]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        
        $sql_detail1 = "SELECT pt.hn,v.vstdate,concat(pt.pname,pt.fname,'',pt.lname) as full_name,d.name  AS doc_name,
                CONCAT(if(dc.dental_care_type_id='' OR dc.dental_care_type_id IS NULL ,'ไม่ระบุประเภทผู้ได้รับบริการ,','')
                ,if(dc.dental_care_service_place_type_id='' OR dc.dental_care_service_place_type_id IS NULL ,'ไม่ระบุบริการใน-นอกสถานบริการ,',''))as err
                FROM dental_care dc
                LEFT OUTER JOIN vn_stat v ON v.vn=dc.vn
                LEFT OUTER JOIN patient pt ON pt.hn=v.hn
                LEFT OUTER JOIN doctor d  ON d.code=dc.doctor
                WHERE dc.vn IN (SELECT vn FROM dtmain WHERE vstdate  BETWEEN '2016-10-01' AND DATE(NOW()) ) 
                AND (dc.dental_care_type_id='' OR dc.dental_care_type_id IS NULL
                OR dc.dental_care_service_place_type_id='' OR dc.dental_care_service_place_type_id IS NULL)
                ORDER BY v.hn ,v.vstdate DESC";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }

}
