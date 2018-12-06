<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class AuditController extends Controller{

    public function actionIndex(){
        $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
        $date_start =  $sql_date['date'];
        $date_end = date('Y-m-d');
//ตรวจสอบคุณภาพข้อมูล PERSON
        $chk10 = "SELECT (SELECT COUNT(DISTINCT hn) FROM tmp_patient_visit WHERE vstdate  BETWEEN '$date_start' AND '$date_end') AS target
                ,COUNT(DISTINCT hn) AS   result
                FROM tmp_patient_visit
                WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                AND nationality='99'
                AND mod11(cid)=0 ";
        $chk11 = "SELECT (SELECT COUNT(DISTINCT hn) FROM tmp_patient_visit WHERE vstdate  BETWEEN '$date_start' AND '$date_end') AS target
                ,COUNT(DISTINCT hn) AS   result
                FROM tmp_patient_visit
                WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                AND nationality='99'
                AND SUBSTR(cid, 2, 5)<>'11418'
                AND  LEFT(cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
                OR LENGTH(cid)!='13' OR (cid='' OR cid IS NULL)
                OR cid LIKE '11111%' AND hn<>'' AND death<>'Y' ";
        $chk12 = "SELECT (SELECT COUNT(DISTINCT hn) FROM tmp_patient_visit WHERE vstdate  BETWEEN '$date_start' AND '$date_end') AS target
                ,COUNT(DISTINCT hn) AS   result
                FROM tmp_patient_visit
                WHERE vstdate  BETWEEN '$date_start' AND '$date_end'
                AND SUBSTR(cid, 2, 5)='11418'
                AND nationality='99'
                AND death<>'Y' ";
        $chk13 = "SELECT (SELECT COUNT(DISTINCT cid) FROM tmp_patient_visit WHERE vstdate BETWEEN '$date_start' AND '$date_end') AS  target
                ,COUNT(DISTINCT cid) AS result
                FROM tmp_patient_visit
                WHERE vstdate BETWEEN '$date_start' AND '$date_end'
                AND cid NOT IN(SELECT cid FROM tmp_person_visit WHERE vstdate) ";
        $chk14 = "SELECT (SELECT COUNT(DISTINCT person_id) FROM tmp_person_visit WHERE vstdate) AS target
                ,COUNT(DISTINCT person_id) AS result
                FROM tmp_person_visit
                WHERE vstdate BETWEEN '$date_start' AND '$date_end'
                AND nationality<>'99'
                AND (person_labor_type_id='' OR person_labor_type_id IS NULL)
                AND death<>'Y' ";
        $chk15 = "SELECT (SELECT COUNT(DISTINCT cid) FROM tmp_person_visit WHERE vstdate BETWEEN '$date_start' AND '$date_end') AS  target
                ,COUNT(DISTINCT cid) AS result
                FROM tmp_person_visit
                WHERE vstdate BETWEEN '$date_start' AND '$date_end'
                AND person_id NOT IN(SELECT person_id FROM hos.person_address) ";
//คุณภาพการให้รหัส ICD ตาม สนย.กำหนด

        $chk01 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end' ) AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM  tmp_visit_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND sex='1'
                    AND (left(pdx,3) IN ('A34','D06','D25','D26','D27','D28','D39','E28','F53','Q50','Q51','Q52','R87','Y76','Z32','Z33','Z34','Z35','Z36','Z39')
                    OR (left(pdx,4) IN ('B373','C796','E894','F525','I863','L292','L705','M800','M801','M810','M811','M830','N992','N993','P546','S314','S374','S375','S376','T192','T193','T833','Z014','Z124','Z301','Z303','Z305','Z311','Z312','Z437','Z875','Z975'))
                    OR (left(pdx,3) BETWEEN 'C51' AND 'C58')
                    OR (left(pdx,3) BETWEEN 'O00' AND 'O99')
                    OR (left(pdx,3) BETWEEN 'N70' AND 'N98')
                    OR (left(pdx,4) BETWEEN 'D070' AND 'D073'))";
        $chk02 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end') AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM  tmp_visit_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (pdx LIKE 'v%'
                    OR pdx LIKE 'w%'
                    OR pdx LIKE 'x%'
                    OR pdx LIKE 'y%')";
        $chk03 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE
                    vstdate BETWEEN '$date_start' AND '$date_end' AND (pdx LIKE 'S%' OR pdx LIKE 'T%')) AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM tmp_visit_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND (pdx LIKE 'S%' OR pdx LIKE 'T%')
                    AND dx0 NOT LIKE  'v%'AND dx0 NOT LIKE  'w%'
                    AND dx0 NOT LIKE  'x%'AND dx0 NOT LIKE  'y%'
                    AND dx1 NOT LIKE  'v%'AND dx1 NOT LIKE  'w%'
                    AND dx1 NOT LIKE  'x%'AND dx1 NOT LIKE  'y%'
                    AND dx2 NOT LIKE  'v%'AND dx2 NOT LIKE  'w%'
                    AND dx2 NOT LIKE  'x%'AND dx2 NOT LIKE  'y%'
                    AND dx3 NOT LIKE  'v%'AND dx3 NOT LIKE  'w%'
                    AND dx3 NOT LIKE  'x%'AND dx3 NOT LIKE  'y%'";
        $chk04 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end' AND pdx BETWEEN 'Z230' AND 'Z279') AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM  tmp_visit_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND pdx BETWEEN 'Z230' AND 'Z279'
                    AND (dx0 BETWEEN 'Z000' AND 'Z029'
                    OR dx1 BETWEEN 'Z000' AND 'Z029'
                    OR dx2 BETWEEN 'Z000' AND 'Z029'
                    OR dx3 BETWEEN 'Z000' AND 'Z029')";
        $chk05 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end') AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM  tmp_visit_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND pdx BETWEEN 'T310' AND 'T319'";

        $chk06 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_diag_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end' AND icd10 BETWEEN 'V00' AND 'Y34') AS target
                    ,COUNT(DISTINCT vn) AS result
                    FROM tmp_diag_opd
                    WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                    AND icd10 BETWEEN 'V00' AND 'Y34'
                    AND LENGTH(icd10)!='5' ";

        $chk07 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_visit_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end'  AND pdx BETWEEN 'Z470' AND 'Z489') AS target
                ,COUNT(DISTINCT vn) AS result
                FROM tmp_visit_opd
                WHERE  vstdate BETWEEN '$date_start' AND '$date_end'
                AND pdx BETWEEN 'Z470' AND 'Z489'
                AND (dx0 LIKE  'S%'  OR  dx0 LIKE  'T%'
                OR dx1 LIKE  'S%' OR  dx1 LIKE  'T%'
                OR dx2 LIKE  'S%' OR  dx2 LIKE  'T%'
                OR dx3 LIKE  'S%' OR  dx3 LIKE  'T%')";

        $chk08 = "SELECT (SELECT COUNT(DISTINCT vn) FROM tmp_diag_opd WHERE vstdate BETWEEN '$date_start' AND '$date_end' ) AS target
                ,COUNT(DISTINCT vn) AS result
                FROM tmp_diag_opd
                WHERE vstdate BETWEEN '$date_start' AND '$date_end'
                AND icd10 IN('J069','D229','L029','L039','T07','Z349')
                OR icd10 BETWEEN 'T14' AND 'T149' ";

//ตรวจสอบคุณภาพข้อมูล PERSON
        $chk10 = Yii::$app->db->createCommand($chk10)->queryAll();
        $chk11 = Yii::$app->db->createCommand($chk11)->queryAll();
        $chk12 = Yii::$app->db->createCommand($chk12)->queryAll();
        $chk13 = Yii::$app->db->createCommand($chk13)->queryAll();
        $chk14 = Yii::$app->db->createCommand($chk14)->queryAll();
        $chk15 = Yii::$app->db->createCommand($chk15)->queryAll();
//คุณภาพการให้รหัส ICD ตาม สนย.กำหนด
        $chk01 = Yii::$app->db->createCommand($chk01)->queryAll();
        $chk02 = Yii::$app->db->createCommand($chk02)->queryAll();
        $chk03 = Yii::$app->db->createCommand($chk03)->queryAll();
        $chk04 = Yii::$app->db->createCommand($chk04)->queryAll();
        $chk05 = Yii::$app->db->createCommand($chk05)->queryAll();
        $chk06 = Yii::$app->db->createCommand($chk06)->queryAll();
        $chk07 = Yii::$app->db->createCommand($chk07)->queryAll();
        $chk08 = Yii::$app->db->createCommand($chk08)->queryAll();


        return $this->render('index', [
            'chk10' => $chk10,
            'chk11' => $chk11,
            'chk12' => $chk12,
            'chk13' => $chk13,
            'chk14' => $chk14,
            'chk15' => $chk15,
            'chk01' => $chk01,
            'chk02' => $chk02,
            'chk03' => $chk03,
            'chk04' => $chk04,
            'chk05' => $chk05,
            'chk06' => $chk06,
            'chk07' => $chk07,
            'chk08' => $chk08]);

    }

}
