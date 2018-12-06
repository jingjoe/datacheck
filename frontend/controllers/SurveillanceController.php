<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SurveillanceController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT sm.hn) AS cc_hn
        FROM surveil_member sm
        LEFT OUTER JOIN ovst_seq o ON o.vn=sm.vn
        LEFT OUTER JOIN patient p ON p.hn=sm.hn
        LEFT OUTER JOIN opduser u ON u.loginname=sm.staff
        WHERE sm.vstdate BETWEEN '$date_start' AND '$date_end'
        AND (sm.vstdate='' OR sm.vstdate is null
        OR sm.pdx='' OR sm.pdx is null
        OR sm.code506='' OR sm.code506 is null
        OR sm.begin_date='' OR sm.begin_date is null
        OR sm.pat_moo=''  OR sm.pat_moo is null
        OR sm.pat_tmbpart='' OR sm.pat_tmbpart is null
        OR sm.pat_amppart=''   OR sm.pat_amppart is null
        OR sm.pat_chwpart='' OR sm.pat_chwpart  is null
        OR sm.ptstat='' OR sm.ptstat  is null)";

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
        
        $sql_detail1 = "SELECT sm.hn,CONCAT(p.pname,p.fname,' ',p.lname) full_name,sm.vstdate,u.name AS doctor,sm.last_update ,
                CONCAT(if(sm.vstdate='' OR sm.vstdate is null  ,'ไม่ระบุวันที่ให้บริการ,','')
                ,if(sm.pdx='' OR sm.pdx is null ,'ไม่ระบุรหัสการวินิจฉัยแรกรับ,','')
                ,if(sm.code506='' OR sm.code506 is null  ,'ไม่ระบุรหัส 506 แรกรับ,','')
                ,if(sm.begin_date='' OR sm.begin_date is null  ,'ไม่ระบุวันที่เริ่มป่วย,','')
                ,if(sm.pat_moo=''  OR sm.pat_moo is null  ,'ไม่ระบุรหัสหมู่บ้าน','')
                ,if(sm.pat_tmbpart='' OR sm.pat_tmbpart is null  ,'ไม่ระบุรหัสตำบล,','')
                ,if(sm.pat_amppart=''   OR sm.pat_amppart is null ,'ไม่ระบุรหัสอำเภอ,','')
                ,if(sm.pat_chwpart='' OR sm.pat_chwpart  is null ,'ไม่ระบุรหัสจังหวัด,','')
                ,if(sm.ptstat='' OR sm.ptstat  is null ,'ไม่ระบุสภาพผู้ป่วย,',''))as err
                FROM surveil_member sm
                LEFT OUTER JOIN ovst_seq o ON o.vn=sm.vn
                LEFT OUTER JOIN patient p ON p.hn=sm.hn
                LEFT OUTER JOIN opduser u ON u.loginname=sm.staff
                WHERE sm.vstdate BETWEEN '$date_start' AND '$date_end'
                AND (sm.vstdate='' OR sm.vstdate is null
                OR sm.pdx='' OR sm.pdx is null
                OR sm.code506='' OR sm.code506 is null
                OR sm.begin_date='' OR sm.begin_date is null
                OR sm.pat_moo=''  OR sm.pat_moo is null
                OR sm.pat_tmbpart='' OR sm.pat_tmbpart is null
                OR sm.pat_amppart=''   OR sm.pat_amppart is null
                OR sm.pat_chwpart='' OR sm.pat_chwpart  is null
                OR sm.ptstat='' OR sm.ptstat  is null)";
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

