<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PolicyController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT ib.an) AS cc_an
                    FROM ipt i,ipt_newborn ib,patient p
                    LEFT OUTER JOIN  patient pm ON pm.hn=p.mother_hn
                    WHERE  ib.an=i.an AND p.hn=i.hn
                    AND ib.born_date BETWEEN '$date_start' AND '$date_end'
                    AND  (p.mother_hn='' OR p.mother_hn IS NULL
                    OR ib.mother_an='' OR ib.mother_an IS NULL)";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT ili.hn) AS cc_an
                    FROM ipt_labour_infant  ili
                    INNER JOIN ipt_labour il ON ili.ipt_labour_id=il.ipt_labour_id
                    INNER JOIN (select i.hn,ib.mother_an,ib.an FROM ipt i,ipt_newborn ib,patient p LEFT OUTER JOIN patient pm ON pm.hn=p.mother_hn
                    WHERE  ib.an=i.an AND p.hn=i.hn)as temp on temp.mother_an=il.an
                    AND ili.birth_date BETWEEN '$date_start' AND '$date_end'
                    AND  (ili.hn='' OR ili.hn IS NULL)";

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
        
        $sql_detail1 = "SELECT i.hn,ib.an,concat(p.pname,p.fname,' ',p.lname)as full_name,ib.born_date,
                    ib.born_time,p.mother_hn,concat(pm.pname,pm.fname,' ',pm.lname)as mather,
                    CONCAT(if(p.mother_hn='' OR p.mother_hn IS NULL ,'ไม่ระบุ HN แม่,','')
                    ,if(ib.mother_an='' OR ib.mother_an IS NULL ,'ไม่ระบุ AN แม่,',''))as err
                    FROM ipt i,ipt_newborn ib,patient p
                    LEFT OUTER JOIN  patient pm ON pm.hn=p.mother_hn
                    WHERE  ib.an=i.an AND p.hn=i.hn
                    AND ib.born_date BETWEEN '$date_start' AND '$date_end'
                    AND  (p.mother_hn='' OR p.mother_hn IS NULL
                    OR ib.mother_an='' OR ib.mother_an IS NULL)";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 20,
            ],
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
        
        $sql_detail2 = "SELECT ili.hn as hn_newborn,concat(p.pname,p.fname,' ',p.lname)as full_name,
                    temp.hn as temphn,temp.mother_an,temp.an as newborn_an,
                    CONCAT(if(ili.hn='' OR ili.hn IS NULL,'ไม่ระบุ HN ทารกหรื่อเป็นค่าว่าง,',''))as err
                    FROM ipt_labour_infant  ili
                    LEFT OUTER JOIN patient p on p.hn=ili.hn
                    INNER JOIN ipt_labour il ON ili.ipt_labour_id=il.ipt_labour_id
                    INNER JOIN (select i.hn,ib.mother_an,ib.an FROM ipt i,ipt_newborn ib,patient p LEFT OUTER JOIN patient pm ON pm.hn=p.mother_hn
                    WHERE  ib.an=i.an AND p.hn=i.hn)as temp on temp.mother_an=il.an
                    AND ili.birth_date BETWEEN '$date_start' AND '$date_end'
                    AND  (ili.hn='' OR ili.hn IS NULL)";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data2,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail2', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
}
