<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PersonController extends Controller {
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
        
        $sql_chart1 = "SELECT COUNT(v.hn) AS cc_hn 
                    FROM  vn_stat v
                    WHERE v.vstdate  BETWEEN '$date_start' AND DATE(NOW())
                    AND v.cid NOT IN(SELECT cid FROM person)";
        $sql_chart2 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    FROM person 
                    WHERE person_discharge_id='9' AND death<>'Y' AND(house_regist_type_id<>'4' OR house_regist_type_id='' 
                    OR house_regist_type_id IS NULL)";
        $sql_chart3 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    FROM  person
                    WHERE LEFT(cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
                    OR LENGTH(cid)!='13' OR (cid='' OR cid IS NULL)
                    OR cid LIKE '11111%' AND person_discharge_id='9' AND death<>'Y' ";
        $sql_chart4 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    FROM person     
                    WHERE person_discharge_id='9' AND death<>'Y' AND(pname='' OR pname IS NULL
                    OR fname='' OR fname IS NULL
                    OR lname='' OR lname IS NULL 
                    OR sex='' OR sex IS NULL
                    OR education=''   OR education IS NULL
                    OR occupation ='' OR occupation IS NULL
                    OR nationality='' OR   nationality IS NULL
                    OR citizenship='' OR   citizenship IS NULL
                    OR blood_group='' OR blood_group IS NULL
                    OR birthdate='' OR birthdate IS NULL
                    OR religion='' OR religion IS NULL
                    OR marrystatus='' OR marrystatus IS NULL) ";
        $sql_chart5 = "SELECT COUNT(DISTINCT person_id)AS cc_pid
                    FROM  person
                    WHERE SUBSTR(cid, 2, 5)='11418'
                    AND person_discharge_id='9' AND death<>'Y'";
        $sql_chart6 = "SELECT COUNT(DISTINCT pt.person_id)AS cc_pid
                    from person pt, (select count(person_id),cid from person group by cid having count(person_id)>1)as temp
                    where pt.cid=temp.cid AND person_discharge_id='9' AND death<>'Y' ";
         
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db2->createCommand($sql_chart4)->queryAll();
        $chart5 = Yii::$app->db2->createCommand($sql_chart5)->queryAll();
        $chart6 = Yii::$app->db2->createCommand($sql_chart6)->queryAll();
        
        return $this->render('index', [
            'date_start' => $date_start,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'chart5' => $chart5,
            'chart6' => $chart6]);
    }
    
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail1 = "SELECT v.hn,v.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,floor(datediff(curdate(),p.birthday)/365) as age_y,
                        type_area,v.vstdate,o.staff,last_update
                        FROM  vn_stat v
                        LEFT OUTER JOIN patient p ON p.cid=v.cid
                        LEFT OUTER JOIN opd_regist_sendlist o ON o.vn=v.vn
                        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                        AND v.cid NOT IN(SELECT cid FROM person)
                        GROUP BY  v.hn
                        ORDER BY v.vstdate";
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
        $sql_detail2 = "SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name ,
                    floor(datediff(curdate(),birthdate)/365) as age_y,nationality ,
                    house_regist_type_id,person_discharge_id,last_update
                    FROM person 
                    WHERE person_discharge_id='9' AND death<>'Y' AND (house_regist_type_id<>'4' OR house_regist_type_id='' 
                    OR house_regist_type_id IS NULL)";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2
        ]);
    }
    
    public function actionDetail3() {        
        $sql_detail3 = "SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name ,
                       floor(datediff(curdate(),birthdate)/365) as age_y,nationality ,
                       house_regist_type_id,person_discharge_id,last_update
                       FROM  person
                       WHERE LEFT(cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
                       OR LENGTH(cid)!='13' OR (cid='' OR cid IS NULL)
                       OR cid LIKE '11111%' AND person_discharge_id='9' AND death<>'Y'";
        $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);

        return $this->render('detail3', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3
        ]);
    }
    
    public function actionDetail4() {        
        $sql_detail4 = "SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name,floor(datediff(curdate(),birthdate)/365) as age_y,last_update,
                CONCAT(if(pname='' OR pname IS NULL ,'ไม่ระบุคำนำหน้า,','')
                ,if(fname='' OR fname IS NULL ,'ไม่ระบุชื่อ,','')
                ,if(lname='' OR lname IS NULL  ,'ไม่ระบุนามสกุล,','')
                ,if(birthdate='' OR birthdate IS NULL  ,'ไม่ระบุวันเกิด,','')
                ,if(sex='' OR sex IS NULL  ,'ไม่ระบุเพศ,','')
                ,if(blood_group='' OR blood_group IS NULL  ,'ไม่ระบุหมู่เลือด,','')
                ,if(occupation='' OR occupation IS NULL  ,'ไม่ระบุอาชีพ','')
                ,if(citizenship='00' OR citizenship='' OR citizenship IS NULL ,'ไม่ระบุเชื้อชาติหรื่อไม่ถูกต้อง','')
                ,if(nationality='00'  OR nationality='' OR nationality IS NULL ,'ไม่ระบุสัญชาติหรื่อไม่ถูกต้อง,','')
                ,if(religion='' OR religion IS NULL  ,'ไม่ระบุศาสนา,','')
                ,if(education='' OR education IS NULL ,'ไม่ระบุระดับการศึกษา,','')
                ,if(marrystatus='' OR marrystatus IS NULL ,'ไม่ระบุสถานะสมรส,',''))as err
                FROM person     
                WHERE person_discharge_id='9' AND death<>'Y' AND(pname='' OR pname IS NULL
                OR fname='' OR fname IS NULL
                OR lname='' OR lname IS NULL 
                OR sex='' OR sex IS NULL
                OR education=''   OR education IS NULL
                OR occupation ='' OR occupation IS NULL
                OR nationality='' OR   nationality IS NULL
                OR citizenship='' OR   citizenship IS NULL
                OR blood_group='' OR blood_group IS NULL
                OR birthdate='' OR birthdate IS NULL
                OR religion='' OR religion IS NULL
                OR marrystatus='' OR marrystatus IS NULL)";
        $data4 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data4,
        ]);

        return $this->render('detail4', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4
        ]);
    }
    
        public function actionDetail5() {        
        $sql_detail5 = "SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name,
                floor(datediff(curdate(),birthdate)/365) as age_y,house_regist_type_id,
                if(person_discharge_id='1','เสียชีวิต','อื่นๆ') AS discharge,last_update
                FROM  person
                WHERE SUBSTR(cid, 2, 5)='11418'
                AND person_discharge_id='9' AND death<>'Y'";
        $data5 = Yii::$app->db2->createCommand($sql_detail5)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data5,
        ]);

        return $this->render('detail5', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail5
        ]);
    }
        public function actionDetail6() {        
        $sql_detail6 = "SELECT pt.person_id,pt.cid,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,pt.birthdate,
                floor(datediff(curdate(),pt.birthdate)/365) as age_y,
                house_regist_type_id, if(person_discharge_id='1','เสียชีวิต','อื่นๆ') AS discharge,last_update
                from person pt, (select count(person_id),cid from person group by cid having count(person_id)>1)as temp
                where pt.cid=temp.cid AND person_discharge_id='9' AND death<>'Y' ";
        $data6 = Yii::$app->db2->createCommand($sql_detail6)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data6,
        ]);

        return $this->render('detail6', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail6
        ]);
    }
}
?>
