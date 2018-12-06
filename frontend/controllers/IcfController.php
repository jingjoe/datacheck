<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class IcfController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(p.hn) AS cc_hn
        FROM ovst_icf oi
        LEFT OUTER JOIN vn_stat v ON v.vn=oi.vn
        LEFT OUTER JOIN ovst_seq o ON o.vn=oi.vn
        LEFT OUTER JOIN patient p ON p.hn=v.hn
        LEFT OUTER JOIN doctor d ON d.code=oi.doctor
        LEFT OUTER JOIN ovst_icf_level_type fl ON fl.ovst_icf_level_type_id=oi.ovst_icf_level_type_id
        LEFT OUTER JOIN ovst_icf_type it ON it.ovst_icf_type_id=oi.ovst_icf_type_id
        WHERE v.vstdate BETWEEN '$date_start' AND '$date_end'
        AND (v.vstdate ='' OR v.vstdate is null
        OR oi.ovst_icf_type_id='' OR oi.ovst_icf_type_id is null
        OR oi.ovst_icf_level_type_id='' OR oi.ovst_icf_level_type_id is null)";

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
        
        $sql_detail1 = "SELECT p.hn,CONCAT(p.pname,p.fname,' ',p.lname) full_name,v.vstdate,d.name AS doc_name,oi.entry_datetime AS d_update ,
         CONCAT(if(v.vstdate ='' OR v.vstdate is null ,'ไม่ระบุวันที่ประเมินสภาวะสุขภาพ,','')
        ,if(oi.ovst_icf_type_id='' OR oi.ovst_icf_type_id is null  ,'ไม่ระบุรหัสสภาวะสุขภาพ,','')
        ,if(oi.ovst_icf_level_type_id='' OR oi.ovst_icf_level_type_id is null  ,'ไม่ระบุระดับของสภาวะสุขภาพ,',''))as err
        FROM ovst_icf oi
        LEFT OUTER JOIN vn_stat v ON v.vn=oi.vn
        LEFT OUTER JOIN ovst_seq o ON o.vn=oi.vn
        LEFT OUTER JOIN patient p ON p.hn=v.hn
        LEFT OUTER JOIN doctor d ON d.code=oi.doctor
        LEFT OUTER JOIN ovst_icf_level_type fl ON fl.ovst_icf_level_type_id=oi.ovst_icf_level_type_id
        LEFT OUTER JOIN ovst_icf_type it ON it.ovst_icf_type_id=oi.ovst_icf_type_id
        WHERE v.vstdate BETWEEN '$date_start' AND '$date_end'
        AND (v.vstdate ='' OR v.vstdate is null
        OR oi.ovst_icf_type_id='' OR oi.ovst_icf_type_id is null
        OR oi.ovst_icf_level_type_id='' OR oi.ovst_icf_level_type_id is null)";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }

}
