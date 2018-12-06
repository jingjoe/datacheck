<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class NewbornController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT p.person_id) AS cc_pid
                FROM person_labour  pl
                LEFT OUTER JOIN person p ON p.person_id=pl.person_id
                INNER JOIN person_labour_place pp ON pp.person_labor_place_id=pl.person_labour_place_id
                INNER JOIN labor_ba ba ON ba.labor_ba_id=pl.labor_ba_id
                INNER JOIN person_labour_doctor_type ld ON ld.person_labour_doctor_type_id=pl.person_labour_doctor_type_id
                INNER JOIN person_labour_type lt On lt.person_labour_type_id=pl.person_labour_type_id
                INNER JOIN person_labour_birth_no lo ON lo.person_labour_birth_no_id=pl.person_labour_birth_no_id
                WHERE p.birthdate BETWEEN '$date_start'AND '$date_end'
                AND (p.person_id is null  or p.person_id= ''
                OR p.mother_person_id is null or p.mother_person_id= '' 
                OR p.birthdate is null or p.birthdate=''
                OR lo.person_labour_birth_no_name is null or lo.person_labour_birth_no_name=''
                OR ba.labor_ba_name is null or ba.labor_ba_name=''
                OR pl.has_vitk is null or pl.has_vitk=''
                OR pl.thalassaemia_wife_location_type_id is null or pl.thalassaemia_wife_location_type_id='') 
                ORDER BY p.birthdate DESC";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT p.person_id) AS cc_pid
                FROM person_wbc pw
                LEFT JOIN person p ON p.person_id=pw.person_id 
                LEFT JOIN person_wbc_post_care pwc ON pwc.person_wbc_id=pw.person_wbc_id
                LEFT JOIN doctor d ON d.code=pwc.doctor_code
                WHERE p.birthdate BETWEEN '$date_start'AND '$date_end'
                AND (pw.force_complete_export IS NULL OR pw.force_complete_date IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'chart1' => $chart1,
            'chart2' => $chart2]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT p.person_id,p.cid ,CONCAT(p.pname,p.fname,' ',p.lname) full_name,pl.gravida ,pl.ga,p.birthdate,
                        CONCAT(if(p.mother_person_id='' OR p.mother_person_id IS NULL ,'ไม่ระบุทะเบียนบุคคล (แม่),','')
                        ,if(p.birthdate='' OR p.birthdate IS NULL ,'ไม่ระบุวันที่คลอด,','')
                        ,if(lo.person_labour_birth_no_name='' OR lo.person_labour_birth_no_name IS NULL  ,'ไม่ระบุลำดับที่ของทารกที่คลอด,','')
                        ,if(ba.labor_ba_name='' OR ba.labor_ba_name IS NULL  ,'ไม่ระบุสภาวการณ์ขาดออกซิเจน,','')
                        ,if(pl.has_vitk='' OR pl.has_vitk IS NULL  ,'ไม่ระบุการได้รับ VIT K หรือไม่,','')
                        ,if(pl.thalassaemia_wife_location_type_id='' OR pl.thalassaemia_wife_location_type_id IS NULL  ,'ไม่ระบุการได้รับการตรวจ TSH หรือไม่,',''))as err

                        FROM person_labour  pl
                        LEFT OUTER JOIN person p ON p.person_id=pl.person_id
                        INNER JOIN person_labour_place pp ON pp.person_labor_place_id=pl.person_labour_place_id
                        INNER JOIN labor_ba ba ON ba.labor_ba_id=pl.labor_ba_id
                        INNER JOIN person_labour_doctor_type ld ON ld.person_labour_doctor_type_id=pl.person_labour_doctor_type_id
                        INNER JOIN person_labour_type lt On lt.person_labour_type_id=pl.person_labour_type_id
                        INNER JOIN person_labour_birth_no lo ON lo.person_labour_birth_no_id=pl.person_labour_birth_no_id
                        WHERE p.birthdate BETWEEN '$date_start'AND '$date_end'
                        AND (p.mother_person_id is null or p.mother_person_id= '' 
                        OR p.birthdate is null or p.birthdate=''
                        OR lo.person_labour_birth_no_name is null or lo.person_labour_birth_no_name=''
                        OR ba.labor_ba_name is null or ba.labor_ba_name=''
                        OR pl.has_vitk is null or pl.has_vitk=''
                        OR pl.thalassaemia_wife_location_type_id is null or pl.thalassaemia_wife_location_type_id='')";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
    
    public function actionDetail2() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail2 = "SELECT p.cid ,CONCAT(p.pname,p.fname,' ',p.lname) full_name,p.birthdate,d.`name` AS doc_name,pw.last_update,
                        CONCAT(if(pw.force_complete_export IS NULL ,'ไม่คลิกส่งออกข้อมูล (ส่งออกเมื่อทำครบทุกกิจกรรม),','') ,
                        if(pw.force_complete_date IS NULL ,'ไม่ระบุวันที่ส่งออกข้อมูล,',''))as err 
                        FROM person_wbc pw
                        LEFT JOIN person p ON p.person_id=pw.person_id 
                        LEFT JOIN person_wbc_post_care pwc ON pwc.person_wbc_id=pw.person_wbc_id
                        LEFT JOIN doctor d ON d.code=pwc.doctor_code
                        WHERE p.birthdate BETWEEN '$date_start'AND '$date_end' 
                        AND (pw.force_complete_export IS NULL OR pw.force_complete_date IS NULL)";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data2,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail2', [
            'date_start' => $date_start,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }

}
