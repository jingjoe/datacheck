<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ServiceController extends Controller{
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
        /* ฟิว rfrocs เป็นสาเหตุการส่งต่อ , ovstost=54ส่งต่อสถานพยาบาลอื่น*/
        $sql_chart1 = "SELECT COUNT(o.vn) AS cc_vn
                    FROM ovst o
                    LEFT OUTER JOIN referout r ON r.vn=o.vn
                    LEFT OUTER JOIN rfrcs rc ON  rc.rfrcs=r.rfrcs 
                    LEFT OUTER JOIN ovstost ot ON  ot.ovstost=o.ovstost
                    WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND o.ovstost='54'  AND (r.rfrcs IS NULL OR r.rfrcs='') ";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT vn) AS cc_vn 
                       FROM opdscreen
                       WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                       AND vn IN(SELECT vn FROM opd_regist_sendlist) 
                       AND (cc='' OR cc IS NULL)";

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
        
        $sql_detail1 = "SELECT p.hn,o.vn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,
                        o.vstdate,ot.export_code AS typeout,rc.export_code AS causeout,
                        if((o.visit_type='O'),'ผู้ป่วยนอก','ผู้ป่วยใน') as visit_type,
                        d.name AS doc_name
                        FROM ovst o
                        LEFT OUTER JOIN patient p ON p.hn=o.hn
                        LEFT OUTER JOIN doctor d ON d.code=o.doctor
                        LEFT OUTER JOIN referout r ON r.vn=o.vn
                        LEFT OUTER JOIN rfrcs rc ON  rc.rfrcs=r.rfrcs
                        LEFT OUTER JOIN ovstost ot ON  ot.ovstost=o.ovstost
                        WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                        AND o.ovstost='54'  AND (r.rfrcs IS NULL OR r.rfrcs='')
                        ORDER BY o.hn ,o.vstdate DESC";
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
        
        $sql_detail2 = "SELECT o.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,CONCAT(o.vstdate,' ',o.vsttime) AS datetime,o.cc,
                    k2.department AS depsend,u.name  AS sendby,   CONCAT(k.spname,' , ',k.name)  AS deproom,
                    CONCAT(if(o.cc='' OR o.cc IS NULL ,'ไม่ระบุอาการสำคัญ CC,',''))as err
                    FROM  opdscreen o
                    INNER JOIN opd_regist_sendlist s ON s.vn=o.vn 
                    LEFT OUTER JOIN patient p ON p.hn=o.hn
                    LEFT OUTER JOIN opduser u  ON u.loginname=s.staff
                    LEFT OUTER JOIN spclty k  ON k.spclty=s.send_to_spclty
                    LEFT OUTER JOIN kskdepartment k2 ON k2.depcode=s.send_from_depcode
                    WHERE o.vstdate  BETWEEN '$date_start' AND '$date_end'
                    AND (o.cc='' OR o.cc IS NULL)
                    GROUP BY o.vn
                    ORDER BY k.spname,o.vstdate DESC";
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
}
