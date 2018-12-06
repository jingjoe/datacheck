<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

      
class PatientController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT hn) AS cc_hn 
                       FROM patient 
                       WHERE death<>'Y' AND (patient.type_area='' OR patient.type_area<>'4' OR patient.type_area IS NULL)";
        $sql_chart2 = "SELECT COUNT(DISTINCT p.hn) AS cc_hn
                       FROM  patient  p
                       LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
                       WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                       AND p.nationality='99' AND p.death<>'Y'
                       AND LEFT(p.cid,1)  NOT IN('1','2','3','4','5','6','7','8')";
        $sql_chart3 = "SELECT COUNT(DISTINCT hn) AS cc_hn 
                       FROM  patient
                       WHERE LEFT(cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
                       OR LENGTH(cid)!='13' OR (cid='' OR cid IS NULL)
                       OR cid LIKE '11111%' AND hn<>'' AND death<>'Y' ";
        $sql_chart4 = "SELECT COUNT(DISTINCT hn) AS cc_hn
                       FROM patient
                       WHERE  floor(datediff(curdate(),birthday)/365) >='100' AND death<>'Y'";
        $sql_chart5 = "SELECT COUNT(DISTINCT hn) AS cc_hn  
                        FROM patient 
                        WHERE death<>'Y' AND(occupation='' OR occupation IS NULL 
                        OR citizenship='' OR citizenship IS NULL
                        OR nationality='' OR nationality IS NULL
                        OR religion='' OR religion IS NULL
                        OR educate='' OR educate IS NULL
                        OR pname='' OR pname IS NULL
                        OR fname='' OR fname IS NULL
                        OR lname='' OR lname IS NULL 
                        OR sex='' OR sex IS NULL
                        OR birthday='' OR birthday IS NULL
                        OR bloodgrp='' OR bloodgrp IS NULL
                        OR marrystatus='' OR marrystatus IS NULL)";
        $sql_chart6 = "SELECT COUNT(DISTINCT hn) AS cc_hn
                       FROM patient
                       WHERE death<>'Y' AND cid NOT IN (SELECT cid FROM person WHERE person_discharge_id='9' AND death<>'Y')";
        $sql_chart7 = "SELECT COUNT(DISTINCT hn)AS cc_hn
                        FROM  patient
                        WHERE SUBSTR(cid, 2, 5)='11418'
                        AND death<>'Y'";
        $sql_chart8 = "select count(pt.hn) AS cc_hn
                       from patient pt, (select count(hn),cid from patient group by cid having count(hn)>1)as temp
                       where pt.cid=temp.cid AND pt.death<>'Y'";
        $sql_chart9 = "SELECT COUNT(DISTINCT p.hn)AS cc_hn
                        FROM patient p
                        WHERE p.hn IN (SELECT hn FROM vn_stat WHERE vstdate BETWEEN '$date_start' AND '$date_end' AND count_in_year='0')
                        AND p.death<>'Y' AND p.nationality='99'
                        AND LEFT(p.cid,1) IN('1','2','3','4','5','6','7','8')
                        AND SUBSTR(p.cid, 2, 5)<>'11418'
                        AND (p.informtel='' OR p.informtel IS NULL
                        OR p.addrpart='' OR p.addrpart IS NULL
                        OR p.moopart='' OR p.moopart IS NULL
                        OR p.amppart='' OR p.amppart IS NULL
                        OR p.chwpart='' OR p.chwpart IS NULL
                        OR p.informaddr='' OR p.informaddr IS NULL)";

        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db2->createCommand($sql_chart4)->queryAll();
        $chart5 = Yii::$app->db2->createCommand($sql_chart5)->queryAll();
        $chart6 = Yii::$app->db2->createCommand($sql_chart6)->queryAll();
        $chart7 = Yii::$app->db2->createCommand($sql_chart7)->queryAll();
        $chart8 = Yii::$app->db2->createCommand($sql_chart8)->queryAll();
        $chart9 = Yii::$app->db2->createCommand($sql_chart9)->queryAll();
        
        return $this->render('index', [
            'date_start' => $date_start,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'chart5' => $chart5,
            'chart6' => $chart6,
            'chart7' => $chart7,
            'chart8' => $chart8,
            'chart9' => $chart9]);
    }
    public function actionDetail1() {
        $sql_detail1 = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,floor(datediff(curdate(),birthday)/365) as age_y,type_area,last_update
                        FROM patient 
                        WHERE death<>'Y' AND (patient.type_area='' OR patient.type_area<>'4' OR patient.type_area IS NULL)";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1
        ]);
    }
    
    public function actionDetail2() {

        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        $sql_detail2 = "SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,p.nationality,
                       floor(datediff(curdate(),p.birthday)/365) as age_y,p.type_area,p.last_update,v.vstdate
                       FROM  patient  p
                       LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
                       WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                       AND p.nationality='99' AND p.death<>'Y'
                       AND LEFT(p.cid,1)  NOT IN('1','2','3','4','5','6','7','8')
                       group by p.hn
                       ORDER BY v.vstdate DESC";
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
   
    public function actionDetail3() {
        $sql_detail3 = "SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,
                CONCAT(p.addrpart,' ','ม.',p.moopart,'  ',th.full_name)as address,p.last_update
                FROM  patient p
                left outer join thaiaddress th on th.addressid=concat(p.chwpart,p.amppart,p.tmbpart)
                WHERE LEFT(p.cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
                OR LENGTH(p.cid)!='13' OR (p.cid='' OR p.cid IS NULL)
                OR p.cid LIKE '11111%' AND p.hn<>'' AND p.death<>'Y'";
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
        $sql_detail4 = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,nationality,
                       floor(datediff(curdate(),birthday)/365) as age_y,type_area,
                       if((death='Y'),'เสียชีวิต','มีชีวิตอยู่')as death,last_update
                       FROM patient p
                       WHERE  floor(datediff(curdate(),birthday)/365) >='100' AND p.death<>'Y'
                       ORDER BY age_y ";
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
        $sql_detail5 = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,floor(datediff(curdate(),birthday)/365) as age_y,last_update,
                    CONCAT(if(pname='' OR pname IS NULL ,'ไม่ระบุคำนำหน้า,','')
                    ,if(fname='' OR fname IS NULL ,'ไม่ระบุชื่อ,','')
                    ,if(lname='' OR lname IS NULL  ,'ไม่ระบุนามสกุล,','')
                    ,if(birthday='' OR birthday IS NULL  ,'ไม่ระบุวันเกิด,','')
                    ,if(sex='' OR sex IS NULL  ,'ไม่ระบุเพศ,','')
                    ,if(bloodgrp='' OR bloodgrp IS NULL  ,'ไม่ระบุหมู่เลือด,','')
                    ,if(occupation='' OR occupation IS NULL  ,'ไม่ระบุอาชีพ','')
                    ,if(citizenship='00' OR citizenship='' OR citizenship IS NULL ,'ไม่ระบุเชื้อชาติหรื่อไม่ถูกต้อง','')
                    ,if(nationality='00' OR nationality='' OR nationality IS NULL ,'ไม่ระบุสัญชาติหรื่อไม่ถูกต้อง,','')
                    ,if(religion='' OR religion IS NULL  ,'ไม่ระบุศาสนา,','')
                    ,if(educate='' OR educate IS NULL ,'ไม่ระบุระดับการศึกษา,','')
                    ,if(marrystatus='' OR marrystatus IS NULL ,'ไม่ระบุสถานะสมรส,',''))as err

                    FROM patient 
                    WHERE death<>'Y'AND (occupation='' OR occupation IS NULL 
                    OR citizenship='' OR citizenship IS NULL
                    OR nationality='' OR nationality IS NULL
                    OR religion='' OR religion IS NULL
                    OR educate='' OR educate IS NULL
                    OR pname='' OR pname IS NULL
                    OR fname='' OR fname IS NULL
                    OR lname='' OR lname IS NULL 
                    OR sex='' OR sex IS NULL
                    OR birthday='' OR birthday IS NULL
                    OR bloodgrp='' OR bloodgrp IS NULL
                    OR marrystatus='' OR marrystatus IS NULL)";
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
        $sql_detail6 = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,sex,birthday,
                       floor(datediff(curdate(),birthday)/365) as age_y,type_area,
                       if((death='Y'),'เสียชีวิต','มีชีวิตอยู่')as death,last_update
                       FROM patient
                       WHERE death<>'Y' AND cid NOT IN (SELECT cid FROM person WHERE person_discharge_id='9' AND death<>'Y')";
        $data6 = Yii::$app->db2->createCommand($sql_detail6)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data6,
        ]);

        return $this->render('detail6', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail6
        ]);
    }
    
    public function actionDetail7() {
        $sql_detail7 = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,
                    floor(datediff(curdate(),birthday)/365) as age_y,type_area,
                    if((death='Y'),'เสียชีวิต','มีชีวิตอยู่')as death,last_update
                    FROM  patient
                    WHERE SUBSTR(cid, 2, 5)='11418'
                    AND death<>'Y'";
        $data7 = Yii::$app->db2->createCommand($sql_detail7)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data7,
        ]);

        return $this->render('detail7', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail7
        ]);
    }
    public function actionDetail8() {
        $sql_detail8 = "select hn,pt.cid,concat(pt.pname,pt.fname,pt.lname) as full_name,
                birthday,floor(datediff(curdate(),birthday)/365) as age_y,type_area,
                if((death='Y'),'เสียชีวิต','มีชีวิตอยู่')as death,last_update
                from patient pt, (select count(hn),cid from patient group by cid having count(hn)>1)as temp
                where pt.cid=temp.cid AND death<>'Y'";
        $data8 = Yii::$app->db2->createCommand($sql_detail8)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data8,
        ]);

        return $this->render('detail8', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail8
        ]);
    }
    public function actionDetail9() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        $sql_detail9 = "SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,floor(datediff(curdate(),p.birthday)/365) as age_y,p.nationality,v.vstdate,p.last_update,
                        CONCAT(if(p.informtel='' OR p.informtel IS NULL ,'ไม่ระบุเบอร์โทรศัพท์,','')
                        ,if(p.addrpart='' OR p.addrpart IS NULL  ,'ไม่ระบุบ้านเลขที่,','')
                        ,if(p.moopart='' OR p.moopart IS NULL  ,'ไม่ระบุหมู่บ้าน,','')
                        ,if(p.amppart='' OR p.amppart IS NULL  ,'ไม่ระบุอำเภอ,','')
                        ,if(p.chwpart='' OR p.chwpart IS NULL ,'ไม่ระบุจังหวัด,','')
                        ,if(p.informaddr='' OR p.informaddr IS NULL ,'ไม่ระบุที่อยู่สำหรับติดต่อ,',''))as err
                        FROM  patient  p
                        LEFT OUTER JOIN vn_stat v ON v.hn=p.hn
                        WHERE v.vstdate  BETWEEN '$date_start' AND '$date_end'
                        AND v.count_in_year='0'
                        AND p.death<>'Y' AND p.nationality='99'
                        AND LEFT(p.cid,1) IN('1','2','3','4','5','6','7','8')
                        AND SUBSTR(p.cid, 2, 5)<>'11418'
                        AND (p.informtel='' OR p.informtel IS NULL
                        OR p.addrpart='' OR p.addrpart IS NULL
                        OR p.moopart='' OR p.moopart IS NULL
                        OR p.amppart='' OR p.amppart IS NULL
                        OR p.chwpart='' OR p.chwpart IS NULL
                        OR p.informaddr='' OR p.informaddr IS NULL)
                        GROUP BY v.hn
                        ORDER BY v.vstdate  DESC";
        $data9 = Yii::$app->db2->createCommand($sql_detail9)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data9,
        ]);

        return $this->render('detail9', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail9
        ]);
    }
}
?>

