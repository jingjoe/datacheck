<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ReferhistoryController extends Controller{
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
                FROM referout
                WHERE refer_date BETWEEN '$date_start' AND '$date_end'
                AND (refer_hospcode='' OR refer_hospcode IS NULL
                OR pre_diagnosis='' OR pre_diagnosis IS NULL
                OR ptstatus_text='' OR  ptstatus_text IS NULL
                OR referout_type_id='' OR referout_type_id IS NULL
                OR referout_emergency_type_id ='' OR referout_emergency_type_id IS NULL
                OR rfrcs='' OR  rfrcs IS NULL)";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT vn) AS cc_vn
                FROM referin
                WHERE refer_date BETWEEN '$date_start' AND '$date_end'
                AND (refer_hospcode='' OR refer_hospcode IS NULL
                OR pre_diagnosis='' OR pre_diagnosis IS NULL
                OR icd10='' OR icd10 IS NULL
                OR refer_type='' OR refer_type IS NULL
                OR rfrcs='' OR  rfrcs IS NULL
                OR f43_causein_id='' OR f43_causein_id IS NULL) ";
        
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
       
        $sql_detail1 = "SELECT ro.refer_number,ro.hn,concat(p.pname,p.fname,'  ',p.lname) as full_name,ro.refer_begin_time,ro.refer_hospcode
                ,d.name as doctor_name,k.department,CONCAT(if(ro.refer_hospcode='' OR ro.refer_hospcode IS NULL ,'ไม่ระบุสถานพยาบาลที่ส่งผู้ป่วยไป,','')
                ,if(ro.pre_diagnosis='' OR ro.pre_diagnosis IS NULL ,'ไม่ระบุวินิจฉัยโรคสุดท้าย (ชื่อโรค),','')
                ,if(ro.ptstatus_text='' OR  ro.ptstatus_text IS NULL  ,'ไม่ระบุสภาพผู้ป่วยก่อนส่งต่อ,','')
                ,if(ro.referout_type_id='' OR ro.referout_type_id IS NULL  ,'ไม่ระบุประเภทผู้ป่วย,','')
                ,if(ro.referout_emergency_type_id ='' OR ro.referout_emergency_type_id IS NULL  ,'ไม่ระบุระดับความเร่งด่วน,','')
                ,if(ro.rfrcs='' OR  ro.rfrcs IS NULL  ,'ไม่ระบุสาเหตุการส่งต่อผู้ป่วย,',''))as err
                from referout ro
                left outer join kskdepartment  k on k.depcode=ro.depcode
                left outer join patient p on p.hn=ro.hn
                left outer join doctor d on d.code = ro.doctor
                where ro.refer_date BETWEEN '$date_start' AND '$date_end'
                AND (ro.refer_hospcode='' OR ro.refer_hospcode IS NULL
                OR ro.pre_diagnosis='' OR ro.pre_diagnosis IS NULL
                OR ro.ptstatus_text='' OR  ro.ptstatus_text IS NULL
                OR ro.referout_type_id='' OR ro.referout_type_id IS NULL
                OR ro.referout_emergency_type_id ='' OR ro.referout_emergency_type_id IS NULL
                OR ro.rfrcs='' OR  ro.rfrcs IS NULL)
                ORDER BY   k.department ,ro.refer_date DESC";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1
        ]);
    }
    public function actionDetail2() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
       
        $sql_detail2 = "SELECT ri.hn,concat(p.pname,p.fname,'  ',p.lname) as full_name,ri.refer_date,ri.refer_hospcode
                ,d.name as doctor_name,k.department,CONCAT(if(ri.refer_hospcode='' OR ri.refer_hospcode IS NULL ,'ไม่ระบุสถานพยาบาลที่ส่งผู้ป่วยไป,','')
                ,if(ri.f43_causein_id='' OR  ri.f43_causein_id IS NULL  ,'ไม่ระบุสาเหตุการส่งต่อผู้ป่วย,','')
                ,if(ri.refer_type='' OR ri.refer_type IS NULL  ,'ไม่ระบุประเภทผู้ป่วย,','')
                ,if(ri.icd10 ='' OR ri.icd10 IS NULL  ,'ไม่ระบุวินิจฉัย,','')
                ,if(ri.rfrcs='' OR  ri.rfrcs IS NULL  ,'ไม่ระบุสาเหตุการส่งต่อผู้ป่วย,',''))as err
                FROM referin ri
                LEFT OUTER JOIN ovst v ON v.vn=ri.vn
                LEFT OUTER JOIN kskdepartment  k on k.depcode=ri.depcode
                LEFT OUTER JOIN patient p on p.hn=ri.hn
                LEFT OUTER JOIN doctor d on d.code = v.doctor
                WHERE ri.refer_date BETWEEN '$date_start' AND '$date_end'
                AND (ri.refer_hospcode='' OR ri.refer_hospcode IS NULL
                OR ri.icd10='' OR ri.icd10 IS NULL
                OR ri.refer_type='' OR ri.refer_type IS NULL
                OR ri.rfrcs='' OR  ri.rfrcs IS NULL
                OR ri.f43_causein_id='' OR ri.f43_causein_id IS NULL)
                ORDER BY   k.department ,ri.refer_date DESC";
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
