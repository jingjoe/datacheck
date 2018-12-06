<?php
namespace frontend\controllers;

use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
class AddressController extends Controller{
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

        $sql_chart1 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    FROM person
                    WHERE person_id NOT IN (SELECT person_id FROM person_address)
                    AND house_regist_type_id='4'";
        $sql_chart2 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    from person_address 
                    WHERE (chwpart='00' OR  chwpart='' OR chwpart IS NULL)";
        
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();

        return $this->render('index', [
            'chart1' => $chart1,
            'chart2' => $chart2]);
    }
    public function actionDetail1() {

        $sql_detail1 = "SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name ,
                floor(datediff(curdate(),birthdate)/365) as age_y,nationality ,
                house_regist_type_id,person_discharge_id,last_update
                FROM person
                WHERE person_id NOT IN (SELECT person_id FROM person_address)
                AND house_regist_type_id='4'";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);
        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
    public function actionDetail2() {

        $sql_detail2 = "SELECT p.person_id,cid,p.patient_hn,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name ,
                floor(datediff(curdate(),p.birthdate)/365) as age_y,p.nationality,pa.moopart,pa.road,
                pa.chwpart,pa.amppart,pa.tmbpart
                from person_address   pa
                left outer join person p ON p.person_id=pa.person_id
                where (pa.chwpart='00' OR  pa.chwpart='' OR pa.chwpart IS NULL)";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);
        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }

}
