<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProviderController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT d.code) AS cc_doctor
                    FROM doctor d
                    LEFT OUTER JOIN vn_stat v ON v.dx_doctor=d.code
                    WHERE v.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (d.code='' OR d.code IS NULL
                    OR d.cid ='' OR d.cid IS NULL
                    OR d.name='' OR d.name  IS NULL
                    OR d.sex='' OR d.sex IS NULL
                    OR d.birth_date='' OR d.birth_date IS NULL
                    OR d.provider_type_code='' OR d.provider_type_code IS NULL
                    OR d.start_date='' OR d.start_date IS NULL
                    OR d.update_datetime ='' OR d.update_datetime  IS NULL 
                    OR d.council_code ='' OR d.council_code IS NULL
                    OR d.licenseno ='' OR d.licenseno IS NULL)";
        
         $sql_chart2 = "SELECT COUNT(DISTINCT d.code) AS cc_doctor
                    FROM doctor d
                    WHERE  (d.code='' OR d.code IS NULL
                    OR d.cid ='' OR d.cid IS NULL
                    OR d.name='' OR d.name  IS NULL
                    OR d.sex='' OR d.sex IS NULL
                    OR d.birth_date='' OR d.birth_date IS NULL
                    OR d.provider_type_code='' OR d.provider_type_code IS NULL
                    OR d.start_date='' OR d.start_date IS NULL
                    OR d.update_datetime ='' OR d.update_datetime  IS NULL 
                    OR d.council_code ='' OR d.council_code IS NULL
                    OR d.licenseno ='' OR d.licenseno IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT d.code,CONCAT(d.pname,' ',d.name) AS doc_name
                    ,d.birth_date AS birth,d.start_date AS startdate,d.update_datetime,
                    CONCAT(if(d.code='' OR d.code IS NULL ,'ไม่ระบุเลขที่ผู้ให้บริการ,','')
                    ,if(d.cid ='' OR d.cid IS NULL  ,'ไม่ระบุเลขที่บัตรประชาชน,','')
                    ,if(d.name='' OR d.name IS NULL  ,'ไม่ระบุชื่อ,','')
                    ,if(d.sex='' OR d.sex IS NULL ,'ไม่ระบุเพศ,','')
                    ,if(d.birth_date='' OR d.birth_date IS NULL  ,'ไม่ระบุวันเกิด,','')
                    ,if(d.provider_type_code='' OR d.provider_type_code IS NULL ,'ไม่ระบุรหัสประเภทบุคลากร,','')
                    ,if(d.start_date='' OR d.start_date IS NULL  ,'ไม่ระบุวันที่เริ่มปฏิบัติงาน,','')
                    ,if(d.council_code ='' OR d.council_code IS NULL  ,'ไม่ระบุรหัสสภาวิชาชีพ,','')
                    ,if(d.licenseno ='' OR d.licenseno IS NULL ,'ไม่ระบุเลขที่ใบประกอบวิชาชีพ,',''))as err
                    FROM doctor d
                    LEFT OUTER JOIN vn_stat v ON v.dx_doctor=d.code
                    WHERE v.vstdate BETWEEN '$date_start' AND '$date_end' 
                    AND (d.code='' OR d.code IS NULL
                    OR d.cid ='' OR d.cid IS NULL
                    OR d.name='' OR d.name IS NULL
                    OR d.sex='' OR d.sex IS NULL
                    OR d.birth_date='' OR d.birth_date IS NULL
                    OR d.provider_type_code='' OR d.provider_type_code IS NULL
                    OR d.start_date='' OR d.start_date IS NULL
                    OR d.update_datetime ='' OR d.update_datetime  IS NULL 
                    OR d.council_code ='' OR d.council_code IS NULL
                    OR d.licenseno ='' OR d.licenseno IS NULL)
                    ORDER BY d.update_datetime ";
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
        
        $sql_detail2 = "SELECT d.code,CONCAT(d.pname,' ',d.name) AS doc_name
                    ,d.birth_date AS birth,d.start_date AS startdate,d.update_datetime, 
                    CONCAT(if(d.code='' OR d.code IS NULL ,'ไม่ระบุเลขที่ผู้ให้บริการ,','')
                    ,if(d.cid ='' OR d.cid IS NULL  ,'ไม่ระบุเลขที่บัตรประชาชน,','')
                    ,if(d.name='' OR d.name IS NULL  ,'ไม่ระบุชื่อ,','')
                    ,if(d.sex='' OR d.sex IS NULL ,'ไม่ระบุเพศ,','')
                    ,if(d.birth_date='' OR d.birth_date IS NULL  ,'ไม่ระบุวันเกิด,','')
                    ,if(d.provider_type_code='' OR d.provider_type_code IS NULL ,'ไม่ระบุรหัสประเภทบุคลากร,','')
                    ,if(d.start_date='' OR d.start_date IS NULL  ,'ไม่ระบุวันที่เริ่มปฏิบัติงาน,','')
                    ,if(d.council_code ='' OR d.council_code IS NULL  ,'ไม่ระบุรหัสสภาวิชาชีพ,','')
                    ,if(d.licenseno ='' OR d.licenseno IS NULL ,'ไม่ระบุเลขที่ใบประกอบวิชาชีพ,',''))as err
                    FROM doctor d
                    WHERE  (d.code='' OR d.code IS NULL
                    OR d.cid ='' OR d.cid IS NULL
                    OR d.name='' OR d.name IS NULL
                    OR d.sex='' OR d.sex IS NULL
                    OR d.birth_date='' OR d.birth_date IS NULL
                    OR d.provider_type_code='' OR d.provider_type_code IS NULL
                    OR d.start_date='' OR d.start_date IS NULL
                    OR d.update_datetime ='' OR d.update_datetime  IS NULL 
                    OR d.council_code ='' OR d.council_code IS NULL
                    OR d.licenseno ='' OR d.licenseno IS NULL)
                    ORDER BY d.update_datetime ";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }

}
