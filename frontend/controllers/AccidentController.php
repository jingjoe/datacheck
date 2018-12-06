<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AccidentController extends Controller {
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
        
        $sql_chart1 = "SELECT coalesce(sum(t.cc), 0)  AS cc_vn
                    FROM (SELECT COUNT(DISTINCT en.vn) AS cc
                    FROM er_nursing_detail en
                    INNER JOIN (SELECT * FROM er_regist WHERE vstdate BETWEEN '$date_start' AND '$date_end' AND er_pt_type='2') e ON e.vn=en.vn
                    INNER JOIN ovst o ON o.vn=en.vn
                    INNER JOIN patient p ON p.hn=o.hn
                    INNER JOIN pq_screen  pq ON pq.vn=en.vn
                    INNER JOIN opduser ou ON ou.loginname=pq.staff
                    INNER JOIN doctor d ON d.code=o.doctor
                    WHERE e.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (en.arrive_time<>'' OR en.arrive_time IS NOT NULL)
                    AND (en.visit_type='' OR en.visit_type IS NULL
                    OR en.accident_airway_type_id='' OR en.accident_airway_type_id IS NULL
                    OR en.accident_bleed_type_id='' OR en.accident_bleed_type_id IS NULL
                    OR en.accident_splint_type_id='' OR en.accident_splint_type_id IS NULL
                    OR en.accident_fluid_type_id='' OR en.accident_fluid_type_id IS NULL )
                    GROUP BY o.hn ) AS t ";

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
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail1 = "SELECT p.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,e.vstdate,ou.name AS doc_name,
                    CONCAT(if(en.visit_type='' OR en.visit_type IS NULL ,'ไม่ระบุประเภทการมารับบริการกรณีอุบัติเหตุฉุกเฉิน,','')
                    ,if(en.accident_airway_type_id='' OR en.accident_airway_type_id IS NULL ,'ไม่ระบุการดูแลการหายใจ,','')
                    ,if(en.accident_bleed_type_id='' OR en.accident_bleed_type_id IS NULL ,'ไม่ระบุการห้ามเลือด,','')
                    ,if(en.accident_splint_type_id='' OR en.accident_splint_type_id IS NULL ,'ไม่ระบุการใส่ splint/ slab,','')
                    ,if(en.accident_fluid_type_id='' OR en.accident_fluid_type_id IS NULL ,'ไม่ระบุการให้นํ้าเกลือ,',''))as err
                    FROM er_nursing_detail en
                    INNER JOIN (SELECT * FROM er_regist WHERE vstdate BETWEEN '$date_start' AND '$date_end' AND er_pt_type='2') e ON e.vn=en.vn
                    INNER JOIN ovst o ON o.vn=en.vn
                    INNER JOIN patient p ON p.hn=o.hn
                    INNER JOIN pq_screen  pq ON pq.vn=en.vn
                    INNER JOIN opduser ou ON ou.loginname=pq.staff
                    INNER JOIN doctor d ON d.code=o.doctor
                    WHERE e.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (en.arrive_time<>'' OR en.arrive_time IS NOT NULL)
                    AND (en.visit_type='' OR en.visit_type IS NULL
                    OR en.accident_airway_type_id='' OR en.accident_airway_type_id IS NULL
                    OR en.accident_bleed_type_id='' OR en.accident_bleed_type_id IS NULL
                    OR en.accident_splint_type_id='' OR en.accident_splint_type_id IS NULL
                    OR en.accident_fluid_type_id='' OR en.accident_fluid_type_id IS NULL )
                    GROUP BY o.hn
                    ORDER BY e.enter_er_time DESC ";
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
