<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class RehabilitationController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pm.vn) AS cc_vn
                        FROM physic_main pm
                        LEFT OUTER JOIN physic_list  pl ON pl.vn=pm.vn
                        LEFT OUTER JOIN physic_items pi ON pi.physic_items_id=pl.physic_items_id
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND (pi.f43_rehab_code='' OR pi.f43_rehab_code IS NULL
                        OR pi.icd10tm_code='' OR pi.icd10tm_code IS NULL
                        OR pm.vstdate='' OR pm.vstdate IS NULL) ";
         
        $sql_chart2 = "SELECT coalesce(sum(t.cc), 0)  AS cc_phy
                        FROM (SELECT COUNT(DISTINCT pm.vn) AS cc
                        FROM physic_main  pm
                        INNER JOIN opitemrece o ON o.vn=pm.vn
                        INNER JOIN nondrugitems n ON n.icode=o.icode
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND n.income='14'
                        GROUP BY pm.vn,pm.vstdate,o.icode
                        HAVING COUNT(o.icode)>1) AS t ";

            $sql_chart3 = "SELECT coalesce(sum(t.cc), 0)  AS cc_phy_opd
                        FROM (SELECT COUNT(DISTINCT pm.vn) AS cc
                        FROM physic_main  pm
                        LEFT JOIN patient p ON p.hn=pm.hn
                        INNER JOIN physic_list pl ON pl.vn=pm.vn
                        INNER JOIN physic_items i ON i.physic_items_id=pl.physic_items_id
                        LEFT JOIN doctor d ON d.`code`=pl.doctor
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pm.vn,pm.vstdate,i.f43_rehab_code
                        HAVING COUNT(i.f43_rehab_code)>1) AS t ";
          
            $sql_chart4 = "SELECT coalesce(sum(t.cc), 0)  AS cc_phy_ipd
                        FROM (SELECT COUNT(DISTINCT pmi.an) AS cc
                        FROM physic_main_ipd    pmi
                        LEFT JOIN patient p ON p.hn=pmi.hn
                        INNER JOIN physic_list_ipd  pli ON pli.physic_main_ipd_id=pmi.physic_main_ipd_id
                        INNER JOIN physic_items i ON i.physic_items_id=pli.physic_items_id
                        LEFT JOIN doctor d ON d.`code`=pli.doctor
                        WHERE pmi.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pmi.an,pmi.vstdate,i.f43_rehab_code
                        HAVING COUNT(i.f43_rehab_code)>1) AS t ";
          
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db2->createCommand($sql_chart4)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT pt.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) full_name,pm.vstdate,pm.service_time,pm.doctor_text,
                        pi.icd9,pi.icd10tm_code AS icd10tm ,
                        CONCAT(if(pm.vstdate='' OR pm.vstdate IS NULL ,'ไม่ระบุวันที่ได้รับบริการฟื้นฟูสมรรถภาพ,','')
                        ,if(pi.icd10tm_code='' OR pi.icd10tm_code IS NULL  ,'ไม่ระบุรหัสบริการฟื้นฟูสมรรถภาพ,',''))as err
                        FROM physic_main pm
                        LEFT OUTER JOIN patient pt ON pt.hn=pm.hn
                        LEFT OUTER JOIN physic_list  pl ON pl.vn=pm.vn
                        LEFT OUTER JOIN physic_items pi ON pi.physic_items_id=pl.physic_items_id
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND (pi.f43_rehab_code='' OR pi.f43_rehab_code IS NULL
                        OR pi.icd10tm_code='' OR pi.icd10tm_code IS NULL
                        OR pm.vstdate='' OR pm.vstdate IS NULL)
                        GROUP BY pm.vn
                        ORDER BY pm.vstdate DESC";
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
        $sql_detail2 = "SELECT pm.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,pm.vstdate,
                        d.name AS doc_name,GROUP_CONCAT(o.icode,' : ',n.`name`) AS err
                        FROM physic_main  pm
                        INNER JOIN opitemrece o ON o.vn=pm.vn
                        LEFT JOIN patient p ON p.hn=o.hn
                        INNER JOIN nondrugitems n ON n.icode=o.icode
                        LEFT JOIN doctor d ON d.`code`=o.doctor
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND n.income='14'
                        GROUP BY pm.vn,pm.vstdate,o.icode
                        HAVING COUNT(o.icode)>1
                        ORDER BY pm.vstdate DESC ";
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
    
    public function actionDetail3() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        $sql_detail3 = "SELECT pm.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,pm.vstdate,
                        d.`name` AS doc_name,GROUP_CONCAT(i.f43_rehab_code,' : ',i.`name`) AS err
                        FROM physic_main  pm
                        LEFT JOIN patient p ON p.hn=pm.hn
                        INNER JOIN physic_list pl ON pl.vn=pm.vn
                        INNER JOIN physic_items i ON i.physic_items_id=pl.physic_items_id
                        LEFT JOIN doctor d ON d.`code`=pl.doctor
                        WHERE pm.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pm.vn,pm.vstdate,i.f43_rehab_code
                        HAVING COUNT(i.f43_rehab_code)>1
                        ORDER BY pm.vstdate DESC ";
                $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);

        return $this->render('detail3', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3
        ]);
    }
    public function actionDetail4() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        $sql_detail4 = "SELECT pmi.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,pmi.an,pmi.vstdate,
                        d.`name` AS doc_name,GROUP_CONCAT(i.f43_rehab_code,' : ',i.`name`) AS err
                        FROM physic_main_ipd    pmi
                        LEFT JOIN patient p ON p.hn=pmi.hn
                        INNER JOIN physic_list_ipd  pli ON pli.physic_main_ipd_id=pmi.physic_main_ipd_id
                        INNER JOIN physic_items i ON i.physic_items_id=pli.physic_items_id
                        LEFT JOIN doctor d ON d.`code`=pli.doctor
                        WHERE pmi.vstdate BETWEEN '$date_start' AND '$date_end'
                        GROUP BY pmi.an,pmi.vstdate,i.f43_rehab_code
                        HAVING COUNT(i.f43_rehab_code)>1
                        ORDER BY pmi.vstdate DESC ";
                $data4 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data4,
        ]);

        return $this->render('detail4', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4
        ]);
    }
}
