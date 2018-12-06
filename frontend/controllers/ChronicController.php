<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ChronicController extends Controller{
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
        $sql_chart1 = "SELECT COUNT(DISTINCT c.hn) AS cc_hn
            FROM clinicmember c
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            LEFT OUTER JOIN patient pt ON pt.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE i.dxtype='1'
            AND (i.icd10='' OR i.icd10 IS NULL
            OR c.regdate='' OR c.regdate IS NULL
            OR c.clinic_member_status_id='' OR c.clinic_member_status_id IS NULL)";
        
        $sql_chart2 = "SELECT COUNT(DISTINCT c.hn) AS cc_hn
            FROM clinicmember c
            INNER  JOIN patient p on p.hn=c.hn
            WHERE c.hn  IN (SELECT hn FROM death)
            AND c.clinic_member_status_id<>'3'";
        
        $sql_chart3 = "SELECT COUNT(DISTINCT c.hn) AS cc_hn
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE c.discharge<>'Y' AND pt.nationality='99'
            AND LEFT(pt.cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
            OR LENGTH(pt.cid)!='13' OR (pt.cid='' OR pt.cid IS NULL)
            OR pt.cid LIKE '11111%'";
        
        $sql_chart4 = "SELECT COUNT(DISTINCT c.hn) AS cc_hn
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE ms.provis_typedis IN ('03','05')
            AND (c.discharge<>'N' OR c.dchdate<>'' OR c.dchdate IS NOT NULL)";
        
        $sql_chart5 = "SELECT COUNT(DISTINCT c.hn) AS cc_hn
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE ms.provis_typedis NOT IN ('03','05')
            AND (c.discharge<>'Y' OR c.discharge='' OR c.discharge IS NULL
            OR c.dchdate='' OR c.dchdate IS NULL)";
        
        $sql_chart6 = "SELECT COUNT(DISTINCT o.vn) AS cc_vn
            FROM ovstdiag o
            LEFT OUTER JOIN opdscreen cr ON cr.vn=o.vn
            WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
            AND cr.pth>'70'
            AND o.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND o.icd10 in('E160','E161','E162','E100','E110','E120','E130','E140')";
        
        $sql_chart7 = "SELECT COUNT(ipd.an) AS cc_an
            FROM iptdiag  ipd
            INNER JOIN an_stat a ON a.an=ipd.an
            LEFT OUTER JOIN opdscreen cr ON cr.vn=a.vn
            WHERE a.dchdate  BETWEEN '$date_start' AND '$date_end'
            AND cr.pth>'70'
            AND a.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND ipd.icd10 IN('E160','E161','E162','E100','E110','E120','E130','E140')";
        
        $sql_chart8 = "SELECT COUNT(DISTINCT o.vn) AS cc_vn
            FROM ovstdiag o
            LEFT OUTER JOIN opdscreen cr ON cr.vn=o.vn
            WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
            AND  cr.pth>'600'
            AND o.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND o.icd10 in('R739')";
        
        $sql_chart9 = "SELECT COUNT(ipd.an) AS cc_an
            FROM iptdiag  ipd
            INNER JOIN an_stat a ON a.an=ipd.an
            LEFT OUTER JOIN opdscreen cr ON cr.vn=a.vn
            WHERE a.dchdate  BETWEEN '$date_start' AND '$date_end'
            AND cr.pth>'600'
            AND a.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND ipd.icd10 IN('R739')";

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
            'date_end' => $date_end,
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

        $sql_detail1 = "SELECT c.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,
        floor(datediff(curdate(),pt.birthday)/365) as age_y,c.regdate,cl.name AS clinic_name,
        CONCAT(if(i.icd10='' OR i.icd10 IS NULL ,'ไม่ระบุรหัสวินิฉัยโรคเรื้อรัง,','')
        ,if(c.regdate='' OR c.regdate IS NULL ,'ไม่ระบุวันที่ตรวจพบครั้งแรก,','')
        ,if(c.clinic_member_status_id='' OR c.clinic_member_status_id IS NULL  ,'ไม่ระบุประเภทการจำหน่าย หรือสถานะของผู้ป่วยที่ทราบผลหลังสุด,',''))as err
        FROM clinicmember c
        LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
        LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
        LEFT OUTER JOIN patient pt ON pt.hn=c.hn
        INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
        WHERE i.dxtype='1'
        AND (i.icd10='' OR i.icd10 IS NULL
        OR c.regdate='' OR c.regdate IS NULL
        OR c.clinic_member_status_id='' OR c.clinic_member_status_id IS NULL)";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data1,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
    
    public function actionDetail2() {
        $sql_detail2 = "SELECT concat(c.clinic,'/',cl.name) as clinic,c.hn,cs.clinic_member_status_name,p.death as pt_death,
            p.cid,concat(p.pname,p.fname,' : ',p.lname) as fullname
            FROM clinicmember c
            INNER  JOIN clinic_member_status cs ON cs.clinic_member_status_id=c.clinic_member_status_id
            INNER  JOIN clinic cl ON cl.clinic=c.clinic
            INNER  JOIN patient p ON p.hn=c.hn
            WHERE c.hn  IN (SELECT hn FROM death)
            AND c.clinic_member_status_id<>'3'";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data2,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
    public function actionDetail3() {
        $sql_detail3 = "SELECT c.hn,pt.cid,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,c.regdate,c.discharge,cl.name AS clinic,
            CONCAT(if(pt.cid='' OR pt.cid IS NULL ,'เลข 13 หลักเป็นค่าว่าง,','')
            ,if(pt.cid LIKE '0%' OR LENGTH(pt.cid)!='13' ,'เลข 13 หลัก GEN หรือ  ไม่ถูกต้อง,',''))AS err
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE c.discharge<>'Y'  AND pt.nationality='99'
            AND LEFT(pt.cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
            OR LENGTH(pt.cid)!='13' OR (pt.cid='' OR pt.cid IS NULL)
            OR pt.cid LIKE '11111%'
            GROUP BY c.hn 
            ORDER BY cl.name";
        $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data3,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail3', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }
    public function actionDetail4() {
        $sql_detail4 = "SELECT cl.name AS clinic,pt.cid,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,c.dchdate,c.discharge,
            ms.clinic_member_status_name AS status_name ,
            CONCAT(if(c.discharge<>'N' ,'ต้องไม่จำหน่วยผู้ป่วยออกจากทะเบียน,','')
            ,if(c.dchdate<>'' OR c.dchdate IS NOT NULL ,'ต้องไม่ระบุวันจำหน่าย,',''))AS err
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE ms.provis_typedis IN ('03','05')
            AND (c.discharge<>'N' OR c.dchdate<>'' OR c.dchdate IS NOT NULL)
            GROUP BY c.hn 
            ORDER BY cl.name";
        $data4 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data4,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail4', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4]);
    }
    
    public function actionDetail5() {
        $sql_detail5 = "SELECT cl.name AS clinic,pt.cid,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,c.dchdate,c.discharge,
            ms.clinic_member_status_name AS status_name ,
            CONCAT(if(c.discharge<>'Y' OR c.discharge='' OR c.discharge IS NULL,'ต้องจำหน่วยผู้ป่วยออกจากทะเบียน,','')
            ,if(c.dchdate='' OR c.dchdate IS  NULL ,'ต้องระบุวันจำหน่ายผู้ป่วย,',''))AS err
            FROM clinicmember  c
            LEFT OUTER JOIN  patient pt ON pt.hn=c.hn
            LEFT OUTER JOIN clinic cl ON cl.clinic=c.clinic
            LEFT OUTER JOIN clinic_persist_icd i ON i.hn=c.hn
            INNER JOIN clinic_member_status ms ON ms.clinic_member_status_id=c.clinic_member_status_id
            WHERE ms.provis_typedis NOT IN ('03','05')
            AND (c.discharge<>'Y' OR c.discharge='' OR c.discharge IS NULL
            OR c.dchdate='' OR c.dchdate IS NULL)
            GROUP BY c.hn 
            ORDER BY cl.name";
        $data5 = Yii::$app->db2->createCommand($sql_detail5)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data5,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('detail5', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail5]);
    }
    public function actionDetail6() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        $sql_detail6 = "SELECT o.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,o.vstdate,o.icd10,cr.pth AS dtx,o.diagtype,d.name AS doc_name,
            CONCAT(if(o.icd10 in('E160','E161','E162','E100','E110','E120','E130','E140') ,'พบผู้ป่วย DM มีภาวะ Hypoglycemia,',''))as err
            FROM ovstdiag o
            LEFT OUTER JOIN opdscreen cr ON cr.vn=o.vn
            LEFT OUTER JOIN patient pt ON pt.hn=o.hn
            LEFT OUTER JOIN doctor d ON d.code=o.doctor
            WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
            AND o.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND o.icd10 in('E160','E161','E162','E100','E110','E120','E130','E140')
            AND  cr.pth>'70'
            GROUP BY o.vn ORDER BY o.vstdate ";
        $data6 = Yii::$app->db2->createCommand($sql_detail6)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data6,
        ]);

        return $this->render('detail6', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail6]);
    }
    public function actionDetail7() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        $sql_detail7 = "SELECT ipd.an,a.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,a.dchdate,ipd.icd10,cr.pth AS dtx,ipd.diagtype,d.name  AS doc_name,
            CONCAT(if(ipd.icd10 in('E160','E161','E162','E100','E110','E120','E130','E140') ,'พบผู้ป่วย DM มีภาวะ Hypoglycemia,',''))as err
            FROM iptdiag  ipd
            INNER JOIN an_stat a ON a.an=ipd.an
            LEFT OUTER JOIN opdscreen cr ON cr.vn=a.vn
            LEFT OUTER JOIN  patient pt ON pt.hn=a.hn
            LEFT OUTER JOIN doctor d  ON d.code=ipd.doctor
            WHERE a.dchdate  BETWEEN '$date_start' AND '$date_end'
            AND  cr.pth>'70'
            AND a.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
            AND ipd.icd10 IN('E160','E161','E162','E100','E110','E120','E130','E140')
            GROUP BY ipd.an
            ORDER BY a.dchdate";
        $data7 = Yii::$app->db2->createCommand($sql_detail7)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data7,
        ]);

        return $this->render('detail7', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail7]);
    }
    
    public function actionDetail8() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail8 = "SELECT o.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,o.vstdate,o.icd10,cr.pth AS dtx,o.diagtype,d.name AS doc_name,
                    CONCAT(if(o.icd10='R739' AND  cr.pth>'600'  ,'พบผู้ป่วย DM มีภาวะ Hypoglycemia,',''))as err
                    FROM ovstdiag o
                    LEFT OUTER JOIN opdscreen cr ON cr.vn=o.vn
                    LEFT OUTER JOIN patient pt ON pt.hn=o.hn
                    LEFT OUTER JOIN doctor d ON d.code=o.doctor
                    WHERE o.vstdate BETWEEN '$date_start' AND '$date_end'
                    AND  cr.pth>'600'
                    AND o.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
                    AND o.icd10 in('R739')";
        $data8 = Yii::$app->db2->createCommand($sql_detail8)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data8,
        ]);

        return $this->render('detail8', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail8]);
    }
    
    public function actionDetail9() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
        
        $sql_detail9 = "SELECT ipd.an,a.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS full_name,
                    a.dchdate,ipd.icd10,cr.pth AS dtx,ipd.diagtype,d.name AS doc_name, 
                    CONCAT(if(ipd.icd10='R739' AND cr.pth>'600' ,'พบผู้ป่วย DM มีภาวะ Hyperglycemai IPD,',''))as err 
                    FROM iptdiag  ipd
                    INNER JOIN an_stat a ON a.an=ipd.an
                    LEFT OUTER JOIN opdscreen cr ON cr.vn=a.vn
                    LEFT OUTER JOIN patient pt ON pt.hn=a.hn 
                    LEFT OUTER JOIN doctor d ON d.code=ipd.doctor 
                    WHERE a.dchdate  BETWEEN '$date_start' AND '$date_end'
                    AND cr.pth>'600'
                    AND a.hn IN(SELECT hn FROM clinicmember WHERE clinic='001')
                    AND ipd.icd10 IN('R739')
                    GROUP BY ipd.an 
                    ORDER BY a.dchdate ";
        $data9 = Yii::$app->db2->createCommand($sql_detail9)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data9,
        ]);

        return $this->render('detail9', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail9]);
    }
}


