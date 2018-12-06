<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SpecialppController extends Controller{
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
                        FROM pp_special pp
                        INNER JOIN pp_special_type ppt ON ppt.pp_special_type_id=pp.pp_special_type_id
                        LEFT OUTER JOIN vn_stat v On v.vn=pp.vn
                        LEFT OUTER JOIN person p ON p.cid=v.cid
                        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                        AND (pp.pp_special_type_id='' OR pp.pp_special_type_id IS NULL
                        OR pp_special_service_place_type_id='' OR pp_special_service_place_type_id IS NULL)";
        
        $sql_chart2 = "SELECT coalesce(sum(t.cc), 0) AS cc_pp
                        FROM (SELECT COUNT(DISTINCT pp.vn) AS cc
                        FROM pp_special pp
                        INNER JOIN ovst o ON o.vn=pp.vn
                        LEFT JOIN pp_special_type ppt ON ppt.pp_special_type_id=pp.pp_special_type_id
                        WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pp.vn,o.vstdate,pp.pp_special_type_id  
                        HAVING COUNT(pp.pp_special_type_id)>1 ) AS t";
        
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT v.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,doc.name AS doctor,
                        CONCAT(if(pp.pp_special_type_id='' OR pp.pp_special_type_id IS NULL ,'ไม่ระบุรหัสบริการส่งเสริมป้องกันเฉพาะ,','')
                        ,if(pp_special_service_place_type_id='' OR pp_special_service_place_type_id IS NULL ,'ไม่ระบุบริการใน-นอกสถานบริการ,',''))as err
                        FROM pp_special pp
                        INNER JOIN pp_special_type ppt ON ppt.pp_special_type_id=pp.pp_special_type_id
                        LEFT OUTER JOIN vn_stat v On v.vn=pp.vn
                        LEFT OUTER JOIN  ovst_seq o ON o.vn=v.vn
                        LEFT OUTER JOIN person p ON p.cid=v.cid
                        INNER JOIN doctor doc ON doc.code=pp.doctor
                        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                        AND (pp.pp_special_type_id='' OR pp.pp_special_type_id IS NULL
                        OR pp_special_service_place_type_id='' OR pp_special_service_place_type_id IS NULL) ";
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
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        $sql_detail2 = "SELECT pp.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,o.vstdate,
                        u.name AS doc_name,GROUP_CONCAT(ppt.pp_special_code) AS err
                        FROM pp_special pp
                        INNER JOIN ovst o ON o.vn=pp.vn
                        LEFT JOIN patient p ON p.hn=o.hn
                        LEFT JOIN pp_special_type ppt ON ppt.pp_special_type_id=pp.pp_special_type_id
                        LEFT JOIN opduser u ON u.loginname=o.staff
                        WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pp.vn,o.vstdate,pp.pp_special_type_id  
                        HAVING COUNT(pp.pp_special_type_id)>1
                        ORDER BY o.vstdate DESC ";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2
        ]);
    }
}

