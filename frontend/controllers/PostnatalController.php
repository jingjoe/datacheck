<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PostnatalController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pa.person_id)AS cc_anc
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        LEFT OUTER JOIN ovst_seq o ON o.vn=pas.vn
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
         AND pa.labor_status_id IN('2','3')
        AND pa.labor_icd10<>'' AND pa.labor_icd10 IS NOT NULL
        AND (pa.preg_no is null OR pa.preg_no=''
        OR pa.labor_date is null OR pa.labor_date=''
        OR pa.post_labor_service1_date is null OR pa.post_labor_service1_date='')";

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

        $sql_detail1 = "SELECT p.person_id, p.cid,CONCAT(p.pname,p.fname,' ',p.lname) full_name
        ,pas.anc_service_date,pa.anc_register_staff AS provider,pa.last_update AS d_update ,
        CONCAT(if(pa.preg_no is null or pa.preg_no ='' ,'ไม่ระบุครรภ์ที่,','')
        ,if(pa.labor_date is null or pa.labor_date=''  ,'ไม่ระบุวันคลอด / วันสิ้นสุดการตั้งครรภ์,','')
        ,if(pa.post_labor_service1_date is null or pa.post_labor_service1_date='' ,'ไม่ระบุวันที่ดูแลแม่,',''))as err
        FROM person_anc_service pas
        LEFT OUTER JOIN person_anc pa ON pa.person_anc_id=pas.person_anc_id
        LEFT OUTER JOIN person p ON p.person_id=pa.person_id
        LEFT OUTER JOIN ovst_seq o ON o.vn=pas.vn
        WHERE pas.anc_service_date BETWEEN '$date_start' AND '$date_end'
        AND pa.labor_status_id IN('2','3')
        AND pa.labor_icd10<>'' AND pa.labor_icd10 IS NOT NULL
        AND (pa.preg_no is null OR pa.preg_no=''
        OR pa.labor_date is null OR pa.labor_date=''
        OR pa.post_labor_service1_date is null OR pa.post_labor_service1_date='') 
        GROUP BY pa.person_id
        ORDER BY pas.anc_service_date DESC";
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
