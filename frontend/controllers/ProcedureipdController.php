<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ProcedureipdController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(a.an) AS cc_an
            FROM an_stat a
            WHERE  a.dchdate BETWEEN '$date_start' AND '$date_end'
            AND  (a.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm))";

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
        
        $sql_detail1 = "SELECT a.hn,a.an,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,a.dchdate,
            CONCAT(a.op0,' , ',a.op1,' , ',a.op2,' , ',a.op3,' , ',a.op4,' , ',a.op5)AS opercode ,
            w.name AS ward,d.name AS doc_name,
            CONCAT(if(a.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm) ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP0,','')
            ,if(a.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm) ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP1,','')
            ,if(a.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP2,','')
            ,if(a.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP3,','')
            ,if(a.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP4,','')
            ,if(a.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)  ,'รหัส ICD-9-CM  ไม่ตรงมาตรฐาน OP5,',''))as err
            FROM an_stat a
            LEFT OUTER JOIN patient p ON p.hn=a.hn
            LEFT OUTER JOIN doctor d ON d.code=a.dx_doctor
            LEFT OUTER JOIN ward w ON w.ward=a.ward
            WHERE  a.dchdate BETWEEN '$date_start' AND '$date_end'
            AND  (a.op0 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op1 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op2 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op3 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op4 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm)
            OR a.op5 NOT IN (SELECT icd9tm FROM datacheck.l_icd9tm))
            GROUP BY a.an
            ORDER BY w.name,a.dchdate DESC";
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
    public function actionDownload() {
        $path = Yii::getAlias('@webroot') . '/documents';
        $file = $path . '/procedure_opd_ipd.xls';
        if (file_exists($file)) {
        Yii::$app->response->sendFile($file);
    }
  } 
}
