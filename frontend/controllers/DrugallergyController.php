<?php
namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DrugallergyController extends Controller{
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
        $sql_chart1 = "SELECT COUNT(DISTINCT oa.hn ) AS cc_hn
                        FROM opd_allergy oa
                        WHERE ( oa.report_date='' OR oa.report_date IS NULL
                        OR oa.agent_code24='' OR oa.agent_code24 IS NULL
                        OR oa.agent='' OR oa.agent IS NULL
                        OR oa.seriousness_id='' OR oa.seriousness_id IS NULL
                        OR oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL
                        /* OR oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL */
                        /* OR oa.symptom='' OR oa.symptom IS NULL */
                        OR oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL)
                        AND oa.opd_allergy_source_id IN('4')";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT oa.hn ) AS cc_hn
                        FROM opd_allergy oa
                        WHERE ( oa.report_date='' OR oa.report_date IS NULL
                        OR oa.agent_code24='' OR oa.agent_code24 IS NULL
                        OR oa.agent='' OR oa.agent IS NULL
                        OR oa.seriousness_id='' OR oa.seriousness_id IS NULL
                        OR oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL
                        /* OR oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL */
                        /* OR oa.symptom='' OR oa.symptom IS NULL */
                        OR oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL)
                        AND oa.opd_allergy_source_id IN('1','2','3')";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();

        return $this->render('index', [
                             'chart1' => $chart1,
                             'chart2' => $chart2]);
    }
    public function actionDetail1() {
        $sql_detail1 = "SELECT oa.hn,CONCAT(p.pname,p.fname,' ',p.lname) full_name,oa.agent AS dname,oa.report_date,oa.reporter,
                        CONCAT(if(oa.report_date='' OR oa.report_date IS NULL ,'ไม่ระบุวันที่บันทึกประวัติการแพ้ยา,','') ,
                         if(oa.agent_code24='' OR oa.agent_code24 IS NULL ,'ไม่ระบุรหัสยาที่มีประวัติการแพ้ยา,','') ,
                         if(oa.agent='' OR oa.agent IS NULL ,'ไม่ระบุชื่อยา,','') ,
                         if(oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL ,'ไม่ระบุประเภทการวินิจฉัยการแพ้ยา,','') ,
                         if(oa.seriousness_id='' OR oa.seriousness_id IS NULL ,'ไม่ระบุระดับความรุนแรงของการแพ้ยา,','') ,
                         if(oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL ,'ไม่ระบุประเภทการวินิจฉัยการแพ้ยา,','') ,
                         if(oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL ,'ไม่ระบุผู้ให้ประวัติการแพ้ยา,',''))as err
                        FROM opd_allergy oa
                        LEFT OUTER JOIN patient p ON p.hn=oa.hn
                        LEFT OUTER JOIN allergy_relation ar ON ar.allergy_relation_id=oa.allergy_relation_id
                        LEFT OUTER JOIN allergy_seriousness al ON al.seriousness_id=oa.seriousness_id
                        WHERE( oa.report_date='' OR oa.report_date IS NULL
                        OR oa.agent_code24='' OR oa.agent_code24 IS NULL
                        OR oa.agent='' OR oa.agent IS NULL
                        OR oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL
                        OR oa.seriousness_id='' OR oa.seriousness_id IS NULL
                        OR oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL
                        OR oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL)
                        AND oa.opd_allergy_source_id IN('4')
                        GROUP BY oa.hn
                        ORDER BY oa.hn , oa.report_date DESC ";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
        
    public function actionDetail2() {
        $sql_detail2 = "SELECT oa.hn,CONCAT(p.pname,p.fname,' ',p.lname) full_name,oa.agent AS dname,oa.report_date,oa.reporter,
                        CONCAT(if(oa.report_date='' OR oa.report_date IS NULL ,'ไม่ระบุวันที่บันทึกประวัติการแพ้ยา,','') ,
                         if(oa.agent_code24='' OR oa.agent_code24 IS NULL ,'ไม่ระบุรหัสยาที่มีประวัติการแพ้ยา,','') ,
                         if(oa.agent='' OR oa.agent IS NULL ,'ไม่ระบุชื่อยา,','') ,
                         if(oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL ,'ไม่ระบุประเภทการวินิจฉัยการแพ้ยา,','') ,
                         if(oa.seriousness_id='' OR oa.seriousness_id IS NULL ,'ไม่ระบุระดับความรุนแรงของการแพ้ยา,','') ,
                         if(oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL ,'ไม่ระบุประเภทการวินิจฉัยการแพ้ยา,','') ,
                         if(oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL ,'ไม่ระบุผู้ให้ประวัติการแพ้ยา,',''))as err
                        FROM opd_allergy oa
                        LEFT OUTER JOIN patient p ON p.hn=oa.hn
                        LEFT OUTER JOIN allergy_relation ar ON ar.allergy_relation_id=oa.allergy_relation_id
                        LEFT OUTER JOIN allergy_seriousness al ON al.seriousness_id=oa.seriousness_id
                        WHERE( oa.report_date='' OR oa.report_date IS NULL
                        OR oa.agent_code24='' OR oa.agent_code24 IS NULL
                        OR oa.agent='' OR oa.agent IS NULL
                        OR oa.allergy_relation_id='' OR oa.allergy_relation_id IS NULL
                        OR oa.seriousness_id='' OR oa.seriousness_id IS NULL
                        OR oa.naranjo_result_id='' OR oa.naranjo_result_id IS NULL
                        OR oa.opd_allergy_source_id='' OR oa.opd_allergy_source_id IS NULL)
                        AND oa.opd_allergy_source_id IN('1','2','3')
                        GROUP BY oa.hn
                        ORDER BY oa.hn , oa.report_date DESC ";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
}
