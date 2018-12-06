<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class NewborncareController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pc.person_wbc_post_care_id) AS cc_wbc
        FROM person_wbc_post_care pc
        LEFT OUTER JOIN person_wbc pw ON pw.person_wbc_id=pc.person_wbc_id
        INNER JOIN person p ON p.person_id=pw.person_id
        LEFT OUTER JOIN vn_stat v ON v.cid=p.cid
        LEFT OUTER JOIN ovst_seq o ON o.vn=v.vn
        LEFT OUTER JOIN person_wbc_post_care_result_type cr ON cr.person_wbc_post_care_result_type_id=pc.person_wbc_post_care_result_type_id
        LEFT OUTER JOIN person_nutrition_food_type nf ON nf.person_nutrition_food_type_id=pc.person_nutrition_food_type_id
        LEFT OUTER JOIN doctor doc ON doc.code=pc.doctor_code
        LEFT OUTER JOIN person_labour pb ON pb.person_id=p.person_id
        WHERE pc.care_date BETWEEN '$date_start' AND '$date_end'
        AND (p.birthdate is null or p.birthdate= ''
        OR pc.care_date  is null or pc.care_date =''
        OR pc.person_wbc_post_care_result_type_id is null or pc.person_wbc_post_care_result_type_id=''
        OR pc.person_nutrition_food_type_id is null or pc.person_nutrition_food_type_id='')";
        

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
        
        $sql_detail1 = "SELECT p.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) full_name,p.birthdate,doc.name AS provider,
        CONCAT(if(p.birthdate is null or p.birthdate= '' ,'ไม่ระบุวันที่คลอด,','')
        ,if(pc.care_date  is null or pc.care_date =''  ,'ไม่ระบุวันที่ดูแลลูก,','')
        ,if(pc.person_wbc_post_care_result_type_id is null or pc.person_wbc_post_care_result_type_id='' ,'ไม่ระบุผลการตรวจทารกหลังคลอด,','')
        ,if( pc.person_nutrition_food_type_id is null or pc.person_nutrition_food_type_id=''  ,'ไม่ระบุอาหารที่รับประทาน,',''))as err
        FROM person_wbc_post_care pc
        LEFT OUTER JOIN person_wbc pw ON pw.person_wbc_id=pc.person_wbc_id
        INNER JOIN person p ON p.person_id=pw.person_id
        LEFT OUTER JOIN vn_stat v ON v.cid=p.cid
        LEFT OUTER JOIN ovst_seq o ON o.vn=v.vn
        LEFT OUTER JOIN person_wbc_post_care_result_type cr ON cr.person_wbc_post_care_result_type_id=pc.person_wbc_post_care_result_type_id
        LEFT OUTER JOIN person_nutrition_food_type nf ON nf.person_nutrition_food_type_id=pc.person_nutrition_food_type_id
        LEFT OUTER JOIN doctor doc ON doc.code=pc.doctor_code
        LEFT OUTER JOIN person_labour pb ON pb.person_id=p.person_id
        WHERE pc.care_date BETWEEN '$date_start' AND '$date_end'
        AND (p.birthdate is null or p.birthdate= ''
        OR pc.care_date  is null or pc.care_date =''
        OR pc.person_wbc_post_care_result_type_id is null or pc.person_wbc_post_care_result_type_id=''
        OR pc.person_nutrition_food_type_id is null or pc.person_nutrition_food_type_id='')";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1
        ]);
    }

}
