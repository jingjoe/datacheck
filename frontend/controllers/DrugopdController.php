<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DrugopdController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(op.vn) AS cc_vn
                    FROM opitemrece   op
                    LEFT OUTER JOIN doctor d ON d.code=op.doctor
                    LEFT OUTER JOIN drugitems s ON s.icode=op.icode
                    WHERE op.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (op.qty='0' OR  op.qty='' OR op.qty IS NULL
                    OR op.sum_price='0' OR  op.sum_price='' OR op.sum_price IS NULL
                    OR s.provis_medication_unit_code NOT IN (select provis_medication_unit_code from provis_medication_unit))
                    AND op.income='03' AND s.istatus='Y'";
        
        $sql_chart2 = "SELECT COUNT(icode) AS cc_icode 
                        FROM drugitems
                        WHERE istatus='Y'
                        AND (LENGTH(did)<>'24' OR did='' OR did IS NULL)";
        
        $sql_chart3 = "SELECT COUNT(DISTINCT o.icode) AS cc_icode
                        FROM drugitems d
                        INNER JOIN opitemrece o ON o.icode=d.icode
                        WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND d.istatus='Y'
                        AND (LENGTH(d.did)<>'24' OR d.did='' OR d.did IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();

        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail1 = "SELECT p.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,op.vstdate,s.name AS drug_name,k.department,d.name AS doc_name ,
                CONCAT(if(op.qty='' OR op.qty IS NULL ,'จำนวนที่จ่ายเท่ากับ 0,','')
                ,if(op.sum_price='' OR op.sum_price IS NULL  ,'ราคาขายเท่ากับ 0,','')
                ,if(s.provis_medication_unit_code NOT IN (select provis_medication_unit_code from provis_medication_unit)  ,'ไม่ระบุหน่วยนับของยาหรือไม่ตรงตามรหัสมาตรฐาน,','') )as err
                FROM opitemrece   op
                LEFT OUTER JOIN kskdepartment k ON k.depcode=op.dep_code
                LEFT OUTER JOIN doctor d ON d.code=op.doctor
                LEFT OUTER JOIN drugitems s ON s.icode=op.icode
                LEFT OUTER JOIN patient p ON p.hn=op.hn
                WHERE op.vstdate  BETWEEN '$date_start' AND '$date_end'
                AND (op.qty='0' OR  op.qty='' OR op.qty IS NULL
                OR op.sum_price='0' OR  op.sum_price='' OR op.sum_price IS NULL
                OR s.provis_medication_unit_code NOT IN (select provis_medication_unit_code from provis_medication_unit))
                AND op.income='03' AND s.istatus='Y' ORDER BY op.vstdate DESC";
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

        
        $sql_detail2 = "SELECT icode,CONCAT(name,' ',strength,' ',units) AS drug,generic_name,did,provis_medication_unit_code,lastupdatestdprice,
                        CONCAT(	if(LENGTH(did)<>'24' ,'รหัสยา 24 หลักไม่ถูกต้อง',''),' ,',
				if(did='' OR did IS NULL  ,'รหัสยา 24 หลักเป็นค่าว่าง',''),',',
				if(provis_medication_unit_code='' OR provis_medication_unit_code IS NULL  ,'หน่วยนับยาไม่ถูกต้อง','') )as err
                        FROM drugitems
                        WHERE istatus='Y'
                        AND (LENGTH(did)<>'24' OR did='' OR did IS NULL)";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
    
    public function actionDetail3() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail3 = "SELECT d.icode,CONCAT(name,' ',d.strength,' ',d.units) AS drug,d.generic_name,did,d.provis_medication_unit_code,d.lastupdatestdprice,
                        CONCAT(	if(LENGTH(d.did)<>'24' ,'รหัสยา 24 หลักไม่ถูกต้อง',''),' ,',
				if(d.did='' OR d.did IS NULL  ,'รหัสยา 24 หลักเป็นค่าว่าง',''),',',
				if(d.provis_medication_unit_code='' OR d.provis_medication_unit_code IS NULL  ,'หน่วยนับยาไม่ถูกต้อง','') )as err
                        FROM drugitems d
                        INNER JOIN opitemrece o ON o.icode=d.icode
                        WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND d.istatus='Y'
                        AND (LENGTH(d.did)<>'24' OR d.did='' OR d.did IS NULL)
                        GROUP BY o.icode";
        $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);

        return $this->render('detail3', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }
}
