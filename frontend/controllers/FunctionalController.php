<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class FunctionalController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT f.vn) AS cc_vn
        from ovst_functional f
        left outer join vn_stat v on v.vn=f.vn
        where v.vstdate between '$date_start' AND '$date_end'
        AND (f.ovst_functional_type_id='0' OR f.ovst_functional_type_id='' OR f.ovst_functional_type_id IS NULL
        OR f.ovst_functional_score='0'  OR f.ovst_functional_score='' OR  f.ovst_functional_score IS NULL
        OR f.ovst_functional_dependent_type_id='0' OR f.ovst_functional_dependent_type_id='' OR f.ovst_functional_dependent_type_id IS NULL)";

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
        
        $sql_detail1 = "SELECT p.hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,v.vstdate,d.name AS doc_name,
        CONCAT(if(f.ovst_functional_type_id='0' OR f.ovst_functional_type_id='' OR f.ovst_functional_type_id IS NULL ,'ไม่ระบุวิธีประเมินความบกพร่อง,','')
        ,if(f.ovst_functional_score='0'  OR f.ovst_functional_score='' OR  f.ovst_functional_score IS NULL ,'ไม่ระบุคะแนนความบกพร่อง,','')
        ,if(f.ovst_functional_dependent_type_id='0' OR f.ovst_functional_dependent_type_id='' OR f.ovst_functional_dependent_type_id IS NULL ,'ไม่ระบุภาวะพึ่งพิงของผู้สูงอายุ,',''))as err
        from ovst_functional f
        left outer join vn_stat v on v.vn=f.vn
        LEFT OUTER JOIN patient p ON p.hn=v.hn
        LEFT OUTER JOIN doctor d ON d.code=f.doctor
        where v.vstdate between '$date_start' AND '$date_end'
        AND (f.ovst_functional_type_id='0' OR f.ovst_functional_type_id='' OR f.ovst_functional_type_id IS NULL
        OR f.ovst_functional_score='0'  OR f.ovst_functional_score='' OR  f.ovst_functional_score IS NULL
        OR f.ovst_functional_dependent_type_id='0' OR f.ovst_functional_dependent_type_id='' OR f.ovst_functional_dependent_type_id IS NULL)
        GROUP BY f.vn
        ORDER BY  v.vstdate DESC";
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
