<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DrugcatController extends Controller{
    
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    public function actionIndex() {

        $sql_chart1 = "SELECT COUNT(*)AS cc FROM drugcatalogue";
        
        $sql_chart2 = "SELECT COUNT(d.icode) AS cc
                        FROM hos.drugitems d
                        INNER JOIN drugcatalogue c ON c.hospdrugcode=d.icode
                        WHERE d.istatus='Y'";
        
        $sql_chart3 = "SELECT COUNT(d.icode) AS cc
                       FROM hos.drugitems d
                       WHERE d.istatus='Y' 
                       AND d.icode NOT IN(SELECT hospdrugcode FROM drugcatalogue GROUP BY hospdrugcode)";
        
        $chart1 = Yii::$app->db->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db->createCommand($sql_chart3)->queryAll();

        return $this->render('index', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3]);
    }
    
    public function actionDetail1($search = NULL) {
        
        if (!empty($search)) {
            $se = $search;
        } else {
            $se = '';
        }
        $sql = Yii::$app->db->createCommand('SELECT SUBSTR(file_name,-28,28) AS file_excel,date_import FROM drugcatalogue  LIMIT 1')->queryAll();
        $sql_detail1 = "SELECT hospdrugcode,tmtid,genericname,trandename,strength,
            content,unitprice,manufacturer,ised,ndc24,datechange,dateupdate,updateflag,
            dateeffective,date_approved,date_import,file_name
            FROM drugcatalogue
            WHERE genericname like '%$se%'";
        $data1 = Yii::$app->db->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
//            'pagination'=>[
//            'pageSize'=>10 //แบ่งหน้า
//        ]
        ]);
        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'excel' => $sql]);
    }
    public function actionDetail2($search = NULL) {
        if (!empty($search)) {
            $se = $search;
        } else {
            $se = '';
        }
        $sql_detail2 = "SELECT d.icode,CONCAT(d.name,d.strength) AS drug,d.units,
                        d.did,d.istatus,c.tmtid,c.genericname,c.ised,c.updateflag,
                        c.datechange,c.dateupdate
                        FROM hos.drugitems d
                        INNER JOIN drugcatalogue c ON c.hospdrugcode=d.icode
                        WHERE d.istatus='Y'
                        AND d.name like '%$se%'";
        $data2 = Yii::$app->db->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
//            'pagination'=>[
//            'pageSize'=>10 //แบ่งหน้า
//            ]
        ]);
        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
    
    public function actionDetail3($search = NULL) {
        if (!empty($search)) {
            $se = $search;
        } else {
            $se = '';
        }
        $sql_detail3 = "SELECT d.icode,CONCAT(d.name,d.strength) AS drug,d.units,
                        d.did,d.istatus,d.lastupdatestdprice
                        FROM hos.drugitems d
                        LEFT JOIN drugcatalogue c ON c.hospdrugcode=d.icode
                        WHERE d.istatus='Y'
                        AND c.hospdrugcode IS NULL
                        AND d.name like '%$se%'";
        $data3 = Yii::$app->db->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
//            'pagination'=>[
//            'pageSize'=>10 //แบ่งหน้า
//            ]
        ]);
        return $this->render('detail3', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }

}
