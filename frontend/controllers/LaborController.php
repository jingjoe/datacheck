<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl

use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
class LaborController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT pa.person_anc_id)AS cc_labor
                FROM person_anc pa
                LEFT OUTER JOIN person p ON p.person_id=pa.person_id
                LEFT OUTER JOIN labour_place lp ON lp.labour_place_id=pa.labor_place_id
                LEFT OUTER JOIN person_labor_type lt ON lt.person_labor_type_id=pa.labour_type_id
                LEFT OUTER JOIN person_labour_doctor_type pd ON pd.person_labour_doctor_type_id=pa.labor_doctor_type_id
                WHERE pa.force_complete_date BETWEEN '$date_start' AND '$date_end'
                AND (pa.preg_no is null or pa.preg_no= '' 
                OR pa.lmp  is null or pa.lmp =''
                OR pa.labor_date is null or pa.labor_date=''
                OR pa.labor_icd10 is null or pa.labor_icd10=''
                OR pa.labor_place_id is null or pa.labor_place_id=''
                OR pa.labour_type_id is null or pa.labour_type_id=''
                OR pa.labor_doctor_type_id is null or pa.labor_doctor_type_id=''
                OR pa.alive_child_count is null or pa.alive_child_count='')";
             
        $sql_chart2 = "SELECT COUNT(DISTINCT il.an)AS cc_an
                FROM ipt_labour il
                LEFT OUTER JOIN ipt_pregnancy ip ON ip.an=il.an
                LEFT OUTER JOIN an_stat a ON a.an=il.an
                LEFT OUTER JOIN labour_place lp ON lp.labour_place_id=il.labour_place_id
                LEFT OUTER JOIN patient p ON p.hn=a.hn
                WHERE a.regdate BETWEEN '$date_start' AND '$date_end'
                AND (il.anc_count is null or il.anc_count= '' 
                OR il.lmp  is null or il.lmp =''
                OR a.regdate is null or a.regdate=''
                OR a.pdx  is null or a.pdx =''
                OR lp.place_name is null or lp.place_name=''
                OR ip.deliver_type is null or ip.deliver_type='')";
        
        $sql_chart3 = "select COUNT(DISTINCT i.an) AS cc_an
                from ipt i  
                left outer join iptadm ia on ia.an=i.an   
                left outer join iptdiag  idg on idg.an=i.an   
                left outer join labor l on l.an = i.an   
                left outer join ipt_labour il on il.an = i.an 
                left outer join ipt_pregnancy pg on pg.an = i.an  
                left outer join ipt_pregnancy_deliver_type it on it.id = pg.deliver_type  AND  pg.deliver_type='1'
                where i.ipt_type<>'4'  
                and i.an  in (select an from labor )  
                and l.labour_closedate between '$date_start' AND '$date_end'  
                AND idg.icd10 LIKE 'O80%' ";
         
        $sql_chart4 = "select COUNT(i.an) AS cc_an
                from ipt i  
                left outer join iptadm ia on ia.an=i.an   
                left outer join iptdiag  idg on idg.an=i.an   
                left outer join labor l on l.an = i.an   
                left outer join ipt_labour il on il.an = i.an 
                left outer join ipt_pregnancy pg on pg.an = i.an  
                left outer join ipt_pregnancy_deliver_type it on it.id = pg.deliver_type  AND  pg.deliver_type='1'
                where i.ipt_type='4'  
                and i.an  in (select an from labor )  
                and l.labour_closedate between '$date_start' AND '$date_end'   
                AND idg.icd10 NOT IN(SELECT icd10tm FROM datacheck.l_bresult_icd10)
                AND idg.diagtype='1' ";
        
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db2->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db2->createCommand($sql_chart4)->queryAll();


        return $this->render('index', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4]);
    }
    public function actionDetail1() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');

        $sql_detail1 = "SELECT pa.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) full_name,pa.labor_date,pa.preg_no AS gravida,pa.last_update,
                    CONCAT(if(pa.preg_no='' OR pa.preg_no is NULL,'ไม่ระบุครรภ์ที่,  ','') 
                    ,if(pa.lmp='' OR pa.lmp is NULL ,'ไม่ระบุ LMP','')
                    ,if(pa.edc='' OR pa.edc is NULL ,'ไม่ระบุ EDC','')
                    ,if(pa.labor_date='' OR pa.labor_date is NULL ,'ไม่ระบุวันคลอด / วันสิ้นสุดการตั้งครรภ์,','')
                    ,if(pa.labor_icd10='' OR pa.labor_icd10 is NULL ,'ไม่ระบุผลสิ้นสุดการตั้งครรภ์,','')
                    ,if(pa.labor_place_id='' OR pa.labor_place_id  is NULL ,'ไม่ระบุสถานที่คลอด,','')
                    ,if(pa.labour_type_id='' OR pa.labour_type_id is NULL ,'ไม่ระบุวิธีการคลอด/สิ้นสุดการตั้งครรภ์,','')
                    ,if(pa.labor_doctor_type_id='' OR pa.labor_doctor_type_id is NULL ,'ไม่ระบุประเภทของผู้ทำคลอด,','')
                    ,if(pa.alive_child_count='' OR pa.alive_child_count is NULL ,'ไม่ระบุจำนวนเกิดมีชีพ,',''))as err
                    FROM person_anc pa
                    LEFT OUTER JOIN person p ON p.person_id=pa.person_id
                    LEFT OUTER JOIN labour_place lp ON lp.labour_place_id=pa.labor_place_id
                    LEFT OUTER JOIN person_labor_type lt ON lt.person_labor_type_id=pa.labour_type_id
                    LEFT OUTER JOIN person_labour_doctor_type pd ON pd.person_labour_doctor_type_id=pa.labor_doctor_type_id
                    WHERE pa.force_complete_date BETWEEN '$date_start' AND '$date_end'
                    AND (pa.preg_no is null or pa.preg_no= '' 
                    OR pa.lmp  is null or pa.lmp =''
                    OR pa.labor_date is null or pa.labor_date=''
                    OR pa.labor_icd10 is null or pa.labor_icd10=''
                    OR pa.labor_place_id is null or pa.labor_place_id=''
                    OR pa.labour_type_id is null or pa.labour_type_id=''
                    OR pa.labor_doctor_type_id is null or pa.labor_doctor_type_id=''
                    OR pa.alive_child_count is null or pa.alive_child_count='')";
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

        $sql_detail2 = "SELECT p.cid,il.an,CONCAT(p.pname,p.fname,' ',p.lname) full_name,
                    a.regdate AS bdate,il.anc_count AS gravida,il.entry_datetime AS d_update,
                    CONCAT(if(il.anc_count ='' OR il.anc_count is NULL,'ไม่ระบุครรภ์ที่,  ','') 
                    ,if(il.lmp='' OR il.lmp is NULL ,'ไม่ระบุ LMP','')
                    ,if(il.edc='' OR il.edc is NULL ,'ไม่ระบุ EDC','')
                    ,if(a.regdate='' OR a.regdate is NULL ,'ไม่ระบุวันคลอด / วันสิ้นสุดการตั้งครรภ์,','')
                    ,if(a.pdx='' OR a.pdx is NULL ,'ไม่ระบุผลสิ้นสุดการตั้งครรภ์,','')
                    ,if(lp.place_name='' OR lp.place_name is NULL ,'ไม่ระบุสถานที่คลอด,','')
                    ,if(ip.deliver_type='' OR ip.deliver_type is NULL ,'ไม่ระบุวิธีการสิ้นสุดการตั้งครรภ์,',''))as err
                    FROM ipt_labour il
                    LEFT OUTER JOIN ipt_pregnancy ip ON ip.an=il.an
                    LEFT OUTER JOIN an_stat a ON a.an=il.an
                    LEFT OUTER JOIN labour_place lp ON lp.labour_place_id=il.labour_place_id
                    LEFT OUTER JOIN patient p ON p.hn=a.hn
                    WHERE a.regdate BETWEEN '$date_start' AND '$date_end'
                    AND (il.anc_count is null or il.anc_count= '' 
                    OR il.lmp  is null or il.lmp =''
                    OR a.regdate is null or a.regdate=''
                    OR a.pdx  is null or a.pdx =''
                    OR lp.place_name is null or lp.place_name=''
                    OR ip.deliver_type is null or ip.deliver_type='')";
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
    public function actionDetail3() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');

        $sql_detail3 = "select il.an,CONCAT(p.pname,p.fname,' ',p.lname) full_name,
            CONCAT(l.labour_closedate,' : ',l.labour_closetime) AS bdatetime,
            il.anc_count AS gravida,t.ipt_type_name,idg.icd10,
            CONCAT(if(i.ipt_type<>'4'  ,'ไม่บันทึกข้อมูลคลอด หน้าแสดงข้อมูล Admit,',''))as err
            from ipt i  
            LEFT OUTER JOIN patient p ON p.hn=i.hn
            left outer join iptadm ia on ia.an=i.an   
            left outer join iptdiag  idg on idg.an=i.an   
            left outer join labor l on l.an = i.an   
            left outer join ipt_labour il on il.an = i.an 
            left outer join ipt_pregnancy pg on pg.an = i.an  
            left outer join ipt_pregnancy_deliver_type it on it.id = pg.deliver_type  AND  pg.deliver_type='1'
            LEFT OUTER JOIN ipt_type t ON t.ipt_type=i.ipt_type
            where i.ipt_type<>'4'  
            and i.an  in (select an from labor )  
            and l.labour_closedate between '$date_start' AND '$date_end' 
            AND idg.icd10 LIKE 'O80%' ";
        $data3 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);
        return $this->render('detail3', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }
    
    public function actionDetail4() {
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');

        $sql_detail4 = "select i.an,concat(p.pname,p.fname,'  ',p.lname) as full_name,CONCAT(l.labour_closedate,' : ',l.labour_closetime) AS bdatetime,
            it.name as dtype,idg.icd10,idg.diagtype,
            CONCAT(if(idg.icd10 NOT IN(SELECT icd10tm FROM datacheck.l_bresult_icd10) ,'ผลสิ้นสุดการตั้งครรภ์ไม่ตรงตามรหัสมาตรฐานตาม ICD-10-TM 2016,',''))as err
            from ipt i  
            LEFT OUTER JOIN patient p ON p.hn=i.hn
            left outer join iptadm ia on ia.an=i.an   
            left outer join iptdiag  idg on idg.an=i.an   
            left outer join labor l on l.an = i.an   
            left outer join ipt_labour il on il.an = i.an 
            left outer join ipt_pregnancy pg on pg.an = i.an  
            left outer join ipt_pregnancy_deliver_type it on it.id = pg.deliver_type  AND  pg.deliver_type='1'
            where i.ipt_type='4'  
            and i.an  in (select an from labor )  
            and l.labour_closedate between '$date_start' AND '$date_end'
            AND idg.icd10 NOT IN(SELECT icd10tm FROM datacheck.l_bresult_icd10)
            AND idg.diagtype='1'";
        $data4 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data4,
        ]);
        return $this->render('detail4', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4]);
    }
}
