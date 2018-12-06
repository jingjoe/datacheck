<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AppointmentController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT op.oapp_id) AS cc_hn
                FROM oapp op
                LEFT OUTER JOIN ovst_seq o ON o.vn=op.vn
                LEFT OUTER JOIN provis_aptype pa ON pa.code=op.provis_aptype_code
                LEFT OUTER JOIN doctor d ON d.code=op.doctor
                LEFT OUTER JOIN patient pt ON pt.hn=op.hn
                LEFT OUTER JOIN clinic c ON c.clinic=op.clinic
                WHERE op.vstdate BETWEEN '$date_start' AND '$date_end'
                AND (pt.cid is null  or pt.cid= ''
                OR o.seq_id is null or o.seq_id= '' 
                OR op.vstdate  is null or op.vstdate  =''
                OR op.clinic is null or op.clinic=''
                OR op.nextdate is null or op.nextdate=''
                OR op.provis_aptype_code is null or op.provis_aptype_code='')";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'chart1' => $chart1]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT pt.cid,CONCAT(pt.pname,pt.fname,' ',pt.lname) full_name,op.vstdate,c.name AS clinic,pa.name AS aptype,d.name AS provider,
                CONCAT(if(o.seq_id='' OR o.seq_id IS NULL ,'ไม่ระบุลำดับที่,','')
                ,if(op.vstdate='' OR op.vstdate IS NULL  ,'ไม่ระบุวันที่ให้บริการ,','')
                ,if(op.clinic='' OR op.clinic IS NULL  ,'ไม่ระบุแผนกที่รับบริการ,','')
                ,if(op.nextdate='' OR op.nextdate IS NULL  ,'ไม่ระบุรหัสโรคที่นัดมาตรวจ,','')
                ,if(op.provis_aptype_code='' OR op.provis_aptype_code IS NULL  ,'ไม่ระบุประเภทกิจกรรมที่นัด,',''))as err
                FROM oapp op
                LEFT OUTER JOIN ovst_seq o ON o.vn=op.vn
                LEFT OUTER JOIN provis_aptype pa ON pa.code=op.provis_aptype_code
                LEFT OUTER JOIN doctor d ON d.code=op.doctor
                LEFT OUTER JOIN patient pt ON pt.hn=op.hn
                LEFT OUTER JOIN clinic c ON c.clinic=op.clinic
                WHERE op.vstdate BETWEEN '$date_start' AND '$date_end'
                AND (pt.cid is null  or pt.cid= ''
                OR o.seq_id is null or o.seq_id= '' 
                OR op.vstdate  is null or op.vstdate  =''
                OR op.clinic is null or op.clinic=''
                OR op.nextdate is null or op.nextdate=''
                OR op.provis_aptype_code is null or op.provis_aptype_code='')
                ORDER BY c.name,op.vstdate DESC";
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
