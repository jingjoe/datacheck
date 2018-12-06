<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DiagnosisopdController extends Controller{
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
        $sql_chart1 = "SELECT COUNT(DISTINCT vn) AS cc_vn 
                       FROM vn_stat
                       WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                       AND (pdx='' OR pdx IS NULL)";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT vn) AS cc_vn 
                        FROM vn_stat
                        WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                        AND pdx NOT IN(SELECT icd10tm FROM datacheck.l_icd10tm)  
                        AND pdx<>'' ";

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
        
        $sql_detail1 = "SELECT v.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,CONCAT(o.vstdate,' ',o.vsttime) AS datetime,o.cc,
                    CONCAT(k.spname,' , ',k.name)  AS deproom,v.pdx,d.name  AS doc_name,
                    CONCAT(if(v.pdx='' OR v.pdx IS NULL ,'รหัสโรคที่วินิจฉัยว่าง,',''))as err 
                    FROM  vn_stat v
                    LEFT OUTER JOIN opdscreen o ON o.vn=v.vn
                    LEFT OUTER JOIN patient p ON p.hn=v.hn
                    LEFT OUTER JOIN spclty k  ON k.spclty=v.spclty
                    LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                    WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                    AND (v.pdx='' OR v.pdx IS NULL)
                    ORDER BY k.name,v.vstdate DESC";
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
        
        $sql_detail2 = "SELECT v.hn,CONCAT(p.pname,p.fname,p.lname) AS full_name,CONCAT(o.vstdate,' ',o.vsttime) AS datetime,o.cc,
                    CONCAT(k.spname,' , ',k.name)  AS deproom,v.pdx,d.name  AS doc_name,
                    CONCAT(if(v.pdx NOT IN(SELECT icd10tm FROM datacheck.l_icd10tm) ,'รหัสวินิจฉัยโรคไม่มีใน ICD10-TM 2016 ของ สนย.StandardCode43_v2.2',''))as err 
                    FROM  vn_stat v
                    LEFT OUTER JOIN opdscreen o ON o.vn=v.vn
                    LEFT OUTER JOIN patient p ON p.hn=v.hn
                    LEFT OUTER JOIN spclty k  ON k.spclty=v.spclty
                    LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                    WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                    AND v.pdx NOT IN(SELECT icd10tm FROM datacheck.l_icd10tm)
                    AND v.pdx<>''
                    ORDER BY k.name,v.vstdate DESC";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
    public function actionDownload() {
        $path = Yii::getAlias('@webroot') . '/documents';
        $file = $path . '/diagnosis_opd.xls';
        if (file_exists($file)) {
        Yii::$app->response->sendFile($file);
    }
    
  }
}
