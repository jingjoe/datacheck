<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DeathController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(DISTINCT person_id) AS cc_pid
                        FROM person_death
                        WHERE  (death_date='' OR death_date IS NULL
                        OR death_diag_1='' OR death_diag_1 IS NULL 
                        OR  death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_4 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_cause='' OR death_cause IS NULL
                        OR death_place='0' OR death_place=''  OR death_place IS NULL
                        OR death_hospcode='' OR  death_hospcode IS NULL ) ";
        $sql_chart2 = "SELECT COUNT(DISTINCT hn) AS cc_hn
                        FROM death
                        WHERE  (death_date='' OR death_date IS NULL
                        OR death_diag_1='' OR death_diag_1 IS NULL 
                        OR  death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_diag_4 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                        OR death_cause='' OR death_cause IS NULL
                        OR death_place='0' OR death_place=''  OR death_place IS NULL
                        OR death_hospcode='' OR  death_hospcode IS NULL) ";
        $sql_chart3 = "SELECT COUNT(DISTINCT pd.person_id) AS cc_pid
                        FROM hos.person_death pd
                        LEFT OUTER JOIN hos.person p ON p.person_id=pd.person_id
                        LEFT OUTER JOIN (SELECT hospcode,cid,CONCAT(death_d,'-',death_m,'-',death_y) AS date_d,cause_death,death_place FROM l_death_cup  GROUP BY cid) ld ON ld.cid=p.cid
                        WHERE (pd.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_1 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_2 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_3 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_4 BETWEEN 'R00' AND 'R99') ";
        
        $sql_chart4 = "SELECT COUNT(DISTINCT d.hn) AS cc_hn
                        FROM hos.death d
                        LEFT OUTER JOIN hos.patient p ON p.hn=d.hn
                        LEFT OUTER JOIN (SELECT hospcode,cid,CONCAT(death_d,'-',death_m,'-',death_y) AS date_d,cause_death,death_place FROM l_death_cup  GROUP BY cid) ld ON ld.cid=p.cid 
                        WHERE (d.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_1 BETWEEN 'R00' AND 'R99')
                        OR (d.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_2 BETWEEN 'R00' AND 'R99')
                        OR (d.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_3 BETWEEN 'R00' AND 'R99')
                        OR (d.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_4 BETWEEN 'R00' AND 'R99') ";

        $sql_chart5 = "SELECT COUNT(DISTINCT person_id) AS cc_pid
                       FROM hos.person 
                       WHERE cid IN (SELECT cid FROM l_death_cup GROUP BY cid)
                       AND death<>'Y'
                       AND person_discharge_id='9' ";
        
         $sql_chart6 = "SELECT COUNT(DISTINCT person_id) AS cc_pid
                       FROM person   
                       WHERE person_id IN(SELECT person_id  FROM person_death)
                       AND (person_discharge_id<>'1' OR person_discharge_id IS NULL)";
        
        $chart1 = Yii::$app->db2->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db2->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db->createCommand($sql_chart4)->queryAll();
        $chart5 = Yii::$app->db->createCommand($sql_chart5)->queryAll();
        $chart6 = Yii::$app->db2->createCommand($sql_chart6)->queryAll();

        return $this->render('index', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'chart5' => $chart5,
            'chart6' => $chart6]);
    }
        public function actionDetail1() {
        
        $sql_detail1 = "SELECT p.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,if(person_discharge_id='1','เสียชีวิต','อื่นๆ') AS discharge,pd.death_date,pd.death_place,pd.death_hospcode,pd.death_diag_1,
                CONCAT(if(pd.death_date='' OR pd.death_date IS NULL ,'ไม่ระบุวันที่ตาย,','')
                ,if(pd.death_diag_1='' OR pd.death_diag_1 IS NULL ,'สาเหตุการตาย_aเป็นค่าว่าง,','')
                ,if(pd.death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_aไม่ถูกต้อง,','')
                ,if(pd.death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_bไม่ถูกต้อง,','')
                ,if(pd.death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_cไม่ถูกต้อง,','')
                ,if(pd.death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_dไม่ถูกต้อง,','')
                ,if(pd.death_cause='' OR pd.death_cause IS NULL ,'ไม่ระบุสาเหตุการตาย,','')
                ,if(pd.death_place='' OR pd.death_place IS NULL ,'ไม่ระบุสถานที่ตาย,','')
                ,if(pd.death_hospcode='' OR pd.death_hospcode IS NULL ,'ไม่ระบุสถานบริการที่เสียชีวิต,',''))as err
                FROM person_death pd
                LEFT OUTER JOIN person p ON p.person_id=pd.person_id     
                WHERE  (pd.death_date='' OR pd.death_date IS NULL
                OR pd.death_diag_1='' OR pd.death_diag_1 IS NULL 
                OR  pd.death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_4 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_cause='' OR pd.death_cause IS NULL
                OR pd.death_place='0' OR pd.death_place=''  OR pd.death_place IS NULL
                OR pd.death_hospcode='' OR  pd.death_hospcode IS NULL) ";
        $data1 = Yii::$app->db2->createCommand($sql_detail1)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail1', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail1]);
    }
        public function actionDetail2() {
        
        $sql_detail2 = " SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,if(death='Y','เสียชีวิต','อื่นๆ') AS discharge,pd.death_date,pd.last_update,pd.death_place,pd.death_hospcode,pd.death_diag_1,
                CONCAT(if(pd.death_date='' OR pd.death_date IS NULL ,'ไม่ระบุวันที่ตาย,','')
                ,if(pd.death_diag_1='' OR pd.death_diag_1 IS NULL ,'สาเหตุการตาย_aเป็นค่าว่าง,','')
                ,if(pd.death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_aไม่ถูกต้อง,','')
                ,if(pd.death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_bไม่ถูกต้อง,','')
                ,if(pd.death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_cไม่ถูกต้อง,','')
                ,if(pd.death_diag_4 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_dไม่ถูกต้อง,','')
                ,if(pd.death_cause='' OR pd.death_cause IS NULL ,'ไม่ระบุสาเหตุการตาย,','')
                ,if(pd.death_place='' OR pd.death_place IS NULL ,'ไม่ระบุสถานที่ตาย,','')
                ,if(pd.death_hospcode='' OR pd.death_hospcode IS NULL ,'ไม่ระบุสถานบริการที่เสียชีวิต,',''))as err
                FROM death pd
                LEFT OUTER JOIN patient p ON p.hn=pd.hn    
                WHERE  (pd.death_date='' OR pd.death_date IS NULL
                OR pd.death_diag_1='' OR pd.death_diag_1 IS NULL 
                OR  pd.death_diag_1 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_2 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_3 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_diag_4 IN (SELECT icd10tm FROM datacheck.l_icd10tm_death)
                OR pd.death_cause='' OR pd.death_cause IS NULL
                OR pd.death_place='0' OR pd.death_place=''  OR pd.death_place IS NULL
                OR pd.death_hospcode='' OR  pd.death_hospcode IS NULL) ";
        $data2 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data2,
        ]);

        return $this->render('detail2', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail2]);
    }
        public function actionDetail3() {
        
        $sql_detail3 = "SELECT p.person_id,p.patient_hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,if(p.death='Y','เสียชีวิต','อื่นๆ') AS discharge,pd.death_date,
                        CONCAT(pd.death_diag_1,' , ',pd.death_diag_2,' , ',pd.death_diag_3,' , ',pd.death_diag_4) AS dx,pd.last_update,
                        CONCAT(if(pd.death_date='' OR pd.death_date IS NULL ,'ไม่ระบุวันที่ตาย,','')
                        ,if(pd.death_diag_1='' OR pd.death_diag_1 IS NULL ,'สาเหตุการตาย_aเป็นค่าว่าง,','')
                        ,if((pd.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_1 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_aไม่ถูกต้อง,','')
                        ,if((pd.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_2 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_bไม่ถูกต้อง,','')
                        ,if((pd.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_3 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_cไม่ถูกต้อง,','')
                        ,if((pd.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_4 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_dไม่ถูกต้อง,',''))as err
                        ,ld.date_d,ld.cause_death
                        FROM hos.person_death pd
                        LEFT OUTER JOIN hos.person p ON p.person_id=pd.person_id
                        LEFT OUTER JOIN (SELECT hospcode,cid,CONCAT(death_d,'-',death_m,'-',death_y) AS date_d,cause_death,death_place FROM l_death_cup  GROUP BY cid) ld ON ld.cid=p.cid
                        WHERE (pd.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_1 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_2 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_3 BETWEEN 'R00' AND 'R99')
                        OR (pd.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR pd.death_diag_4 BETWEEN 'R00' AND 'R99') ";
        $data3 = Yii::$app->db->createCommand($sql_detail3)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data3,
        ]);

        return $this->render('detail3', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail3]);
    }
        public function actionDetail4() {
        
        $sql_detail4 = " SELECT p.hn,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,if(death='Y','เสียชีวิต','อื่นๆ') AS discharge,d.death_date,
                    CONCAT(d.death_diag_1,' , ',d.death_diag_2,' , ',d.death_diag_3,' , ',d.death_diag_4) AS dx,d.last_update,
                    CONCAT(if(d.death_date='' OR d.death_date IS NULL ,'ไม่ระบุวันที่ตาย,','')
                    ,if(d.death_diag_1='' OR d.death_diag_1 IS NULL ,'สาเหตุการตาย_aเป็นค่าว่าง,','')
                    ,if((d.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_1 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_aไม่ถูกต้อง,','')
                    ,if((d.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_2 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_bไม่ถูกต้อง,','')
                    ,if((d.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_3 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_cไม่ถูกต้อง,','')
                    ,if((d.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_4 BETWEEN 'R00' AND 'R99'),'ไม่ระบุหรือรหัสโรคที่เป็นสาเหตุการตาย_dไม่ถูกต้อง,',''))as err
                    ,ld.date_d,ld.cause_death
                    FROM hos.death d
                    LEFT OUTER JOIN hos.patient p ON p.hn=d.hn
                    LEFT OUTER JOIN (SELECT hospcode,cid,CONCAT(death_d,'-',death_m,'-',death_y) AS date_d,cause_death,death_place FROM l_death_cup  GROUP BY cid) ld ON ld.cid=p.cid 
                    WHERE (d.death_diag_1 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_1 BETWEEN 'R00' AND 'R99')
                    OR (d.death_diag_2 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_2 BETWEEN 'R00' AND 'R99')
                    OR (d.death_diag_3 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_3 BETWEEN 'R00' AND 'R99')
                    OR (d.death_diag_4 IN('Y10','Y34','Y872','C80','C97','I472','I490','I46','I50','I514','I515','I516','I519','I1709')OR d.death_diag_4 BETWEEN 'R00' AND 'R99') ";
        $data4 = Yii::$app->db->createCommand($sql_detail4)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data4,
        ]);

        return $this->render('detail4', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4]);
    }
    
    public function actionDetail5() {
        
        $sql_detail5 = "SELECT p.person_id,p.cid,CONCAT(p.pname,p.fname,' ',p.lname) AS full_name,
                        if(p.person_discharge_id='9','ไม่จำหน่าย',p.person_discharge_id) AS discharge
                        ,ld.date_d,ld.cause_death,if(ld.death_place='','ไม่ทราบ',ld.death_place) AS place,p.last_update
                        FROM hos.person p
                        INNER JOIN (SELECT hospcode,cid,CONCAT(death_d,'-',death_m,'-',death_y) AS date_d,cause_death,death_place FROM l_death_cup  GROUP BY cid) ld ON ld.cid=p.cid
                        AND p.death<>'Y'
                        AND p.person_discharge_id='9' ";
        $data5 = Yii::$app->db->createCommand($sql_detail5)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data5,
        ]);

        return $this->render('detail5', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail5]);
    }
    public function actionDetail6() {
        
        $sql_detail6 = " SELECT person_id,cid,CONCAT(pname,fname,' ',lname) AS full_name,person_discharge_id,discharge_date,last_update,
                        CONCAT(if(person_discharge_id<>'1' OR person_discharge_id IS NULL ,'ไม่ระบุสถานะการจำหน่าย,',''))as err
                        FROM person    
                        WHERE person_id IN(SELECT person_id  FROM person_death)
                        AND  (person_discharge_id<>'1' OR person_discharge_id IS NULL) ";
        $data6 = Yii::$app->db2->createCommand($sql_detail6)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data6,
        ]);

        return $this->render('detail6', [
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail6]);
    }
       public function actionDownload() {
        $path = Yii::getAlias('@webroot') . '/documents';
        $file = $path . '/template83.pdf';
        if (file_exists($file)) {
        Yii::$app->response->sendFile($file);
        }
    }

}
