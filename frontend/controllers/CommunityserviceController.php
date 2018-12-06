<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class CommunityserviceController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT cs.vn) AS cc_vn
        FROM ovst_community_service cs
        INNER JOIN ovst_community_service_type ct ON ct.ovst_community_service_type_id=cs.ovst_community_service_type_id
        LEFT OUTER JOIN vn_stat v On v.vn=cs.vn
        LEFT OUTER JOIN person p ON p.cid=v.cid
        INNER JOIN doctor doc ON doc.code=cs.doctor
        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
        AND cs.ovst_community_service_type_id='' OR cs.ovst_community_service_type_id IS NULL";

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
        
        $sql_detail1 = "SELECT v.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,doc.name AS doctor,
        CONCAT(if(cs.ovst_community_service_type_id='' OR cs.ovst_community_service_type_id IS NULL  ,'ไม่ระบุรหัสการให้บริการในชุมชน,',''))as err
        FROM ovst_community_service cs
        INNER JOIN ovst_community_service_type ct ON ct.ovst_community_service_type_id=cs.ovst_community_service_type_id
        LEFT OUTER JOIN vn_stat v On v.vn=cs.vn
        LEFT OUTER JOIN person p ON p.cid=v.cid
        INNER JOIN doctor doc ON doc.code=cs.doctor
        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
        AND cs.ovst_community_service_type_id='' OR cs.ovst_community_service_type_id IS NULL
        GROUP BY cs.vn";
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

