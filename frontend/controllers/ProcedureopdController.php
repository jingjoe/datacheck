<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProcedureopdController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(v.vn) AS cc_vn
            FROM vn_stat v
            WHERE  v.vstdate BETWEEN '$date_start' AND '$date_end'
            AND  (v.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm))";
        
        $sql_chart2 = "SELECT COUNT(v.vn) AS cc_vn
            FROM vn_stat v
            WHERE  v.vstdate BETWEEN '$date_start' AND '$date_end'
            AND (v.op0 IN('8904')
            OR v.op1 IN('8904')
            OR v.op2 IN('8904')
            OR v.op3 IN('8904')
            OR v.op4 IN('8904')
            OR v.op5 IN('8904'))";

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
        
        $sql_detail1 = "SELECT v.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,
            CONCAT(v.op0,' , ',v.op1,' , ',v.op2,' , ',v.op3,' , ',v.op4,' , ',v.op5)AS opercode,s.name AS clinic,d.name AS doc_name,
            CONCAT(if(v.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm) ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP0,','')
            ,if(v.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm) ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP1,','')
            ,if(v.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP2,','')
            ,if(v.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP3,','')
            ,if(v.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP4,','')
            ,if(v.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP5,',''))as err
            FROM vn_stat v
            LEFT OUTER JOIN ovst o ON o.vn=v.vn
            LEFT OUTER JOIN spclty s ON s.spclty=o.spclty
            LEFT OUTER JOIN patient p ON p.hn=v.hn
            LEFT OUTER JOIN doctor d ON d.code=v.dx_doctor
            WHERE v.vstdate BETWEEN '$date_start' AND '$date_end'
            AND  (v.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR v.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm))
            ORDER BY s.name,v.vstdate DESC";
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
        
        $sql_detail2 = "SELECT v.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,
            CONCAT(v.op0,' , ',v.op1,' , ',v.op2,' , ',v.op3,' , ',v.op4,' , ',v.op5)AS opercode,s.name AS clinic,d.name AS doc_name,
            CONCAT(if(v.op0 IN('8904') ,'รหัส ICD-9-CM เท่ากับ 8904 OP0,','')
            ,if(v.op1 IN('8904') ,'รหัส ICD-9-CM  เท่ากับ 8904 OP1,','')
            ,if(v.op2 IN('8904')  ,'รหัส ICD-9-CM  เท่ากับ 8904 OP2,','')
            ,if(v.op3 IN('8904')  ,'รหัส ICD-9-CM  เท่ากับ 8904 OP3,','')
            ,if(v.op4 IN('8904')  ,'รหัส ICD-9-CM  เท่ากับ 8904 OP4,','')
            ,if(v.op5 IN('8904')  ,'รหัส ICD-9-CM  เท่ากับ 8904 OP5,',''))as err
            FROM vn_stat v
            LEFT OUTER JOIN ovst o ON o.vn=v.vn
            LEFT OUTER JOIN spclty s ON s.spclty=o.spclty
            LEFT OUTER JOIN patient p ON p.hn=v.hn
            LEFT OUTER JOIN doctor d ON d.code=v.dx_doctor
            WHERE v.vstdate BETWEEN '$date_start' AND '$date_end'
            AND (v.op0 IN('8904')
            OR v.op1 IN('8904')
            OR v.op2 IN('8904')
            OR v.op3 IN('8904')
            OR v.op4 IN('8904')
            OR v.op5 IN('8904'))
            ORDER BY s.name,v.vstdate DESC";
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
        $file = $path . '/procedure_opd_ipd.xls';
        if (file_exists($file)) {
        Yii::$app->response->sendFile($file);
    }
  } 
}
