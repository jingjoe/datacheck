<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class EpiController extends Controller{
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
        
        $sql_chart1 = "SELECT COUNT(p.person_id) AS cc_pid
        from person_wbc_vaccine_detail    wv
        inner join person_wbc_service ps ON ps.person_wbc_service_id=wv.person_wbc_service_id
        inner join person_wbc pw ON pw.person_wbc_id=ps.person_wbc_id
        inner join person p ON p.person_id=pw.person_id
        INNER JOIN vn_stat v ON v.cid=p.cid
        INNER JOIN wbc_vaccine va ON va.wbc_vaccine_id=wv.wbc_vaccine_id
        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=wv.wbc_vaccine_id
        WHERE ps.service_date BETWEEN '$date_start' and '$date_end'
        AND (wv.wbc_vaccine_id='' OR wv.wbc_vaccine_id IS NULL
        OR va.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype))";
        
        $sql_chart2 = "SELECT COUNT(p.person_id) AS cc_pid
        FROM person_epi_vaccine_list evl
        INNER JOIN person_epi_vaccine ev ON ev.person_epi_vaccine_id =evl.person_epi_vaccine_id
        INNER JOIN person_epi e ON e.person_epi_id=ev.person_epi_id
        INNER JOIN person p ON p.person_id=e.person_id
        INNER JOIN vn_stat v ON v.cid=p.cid
        INNER JOIN epi_vaccine ec ON ec.epi_vaccine_id =evl.epi_vaccine_id
        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=evl.epi_vaccine_id
        WHERE ev.vaccine_date BETWEEN '$date_start' and '$date_end'
        AND (evl.epi_vaccine_id='' OR evl.epi_vaccine_id IS NULL
        OR ec.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype))";
        
        $sql_chart3 = "SELECT COUNT(v.hn) AS cc_hn
        FROM ovst_vaccine ov
        LEFT OUTER JOIN person_vaccine pc ON pc.person_vaccine_id=ov.person_vaccine_id
        LEFT OUTER JOIN vn_stat v ON v.vn=ov.vn
        LEFT OUTER JOIN patient pt ON pt.cid=v.cid
        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=ov.person_vaccine_id
        WHERE v.vstdate BETWEEN '$date_start' and '$date_end'
        AND (ov.person_vaccine_id='' OR ov.person_vaccine_id IS NULL
        OR pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype))";
        
        $sql_chart4 = "SELECT COUNT(pt.hn) AS cc_hn
        FROM patient_vaccine pv
        INNER JOIN person_vaccine pc ON pc.person_vaccine_id=pv.baby_code
        INNER JOIN patient pt ON pt.hn=pv.hn
        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=pv.baby_code
        WHERE pv.vaccine_date  BETWEEN '$date_start' and '$date_end'
        AND (pv.baby_code='' OR pv.baby_code IS NULL
        OR pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype))";

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
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail1 = "SELECT pe.hn,CONCAT(pe.pname,pe.fname,' ',pe.lname) AS ptname,v.age_y,ps.service_date,d.name  AS doc_name,
                        CONCAT(if(wv.wbc_vaccine_id='' OR wv.wbc_vaccine_id IS NULL ,'ไม่ระบุวัคซีน,','')
                              ,if(va.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype) ,'รัหสวัคซีนไม่ตรงมาตฐาน,',''))as err
                        FROM person_wbc_vaccine_detail  wv
                        INNER JOIN person_wbc_service ps ON ps.person_wbc_service_id=wv.person_wbc_service_id
                        INNER JOIN person_wbc pw ON pw.person_wbc_id=ps.person_wbc_id
                        INNER JOIN person p ON p.person_id=pw.person_id
                        INNER JOIN vn_stat v ON v.cid=p.cid
                        INNER JOIN patient pe ON pe.hn=v.hn
                        LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                        INNER JOIN wbc_vaccine va ON va.wbc_vaccine_id=wv.wbc_vaccine_id
                        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=wv.wbc_vaccine_id
                        WHERE ps.service_date BETWEEN '$date_start' and '$date_end'
                        AND (wv.wbc_vaccine_id='' OR wv.wbc_vaccine_id IS NULL
                        OR va.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype)) ";
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
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail2 = "SELECT pe.hn,CONCAT(pe.pname,pe.fname,' ',pe.lname) AS ptname,v.age_y,ev.vaccine_date,d.name  AS doc_name,
                        CONCAT(if(evl.epi_vaccine_id='' OR evl.epi_vaccine_id IS NULL ,'ไม่ระบุวัคซีน,','')
                              ,if(ec.export_vaccine_code  NOT IN(SELECT code FROM provis_vcctype) ,'รัหสวัคซีนไม่ตรงมาตฐาน,',''))as err
                        FROM person_epi_vaccine_list evl
                        INNER JOIN person_epi_vaccine ev ON ev.person_epi_vaccine_id =evl.person_epi_vaccine_id
                        INNER JOIN person_epi e ON e.person_epi_id=ev.person_epi_id
                        INNER JOIN person p ON p.person_id=e.person_id
                        INNER JOIN vn_stat v ON v.cid=p.cid
                        INNER JOIN patient pe ON pe.hn=v.hn
                        LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                        INNER JOIN epi_vaccine ec ON ec.epi_vaccine_id =evl.epi_vaccine_id
                        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=evl.epi_vaccine_id
                        WHERE ev.vaccine_date BETWEEN '$date_start' and '$date_end'
                        AND (evl.epi_vaccine_id='' OR evl.epi_vaccine_id IS NULL
                        OR ec.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype))";
        $data1 = Yii::$app->db2->createCommand($sql_detail2)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
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
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail3 = "SELECT pt.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS ptname,v.age_y,v.vstdate,d.name  AS doc_name,
                        CONCAT(if(ov.person_vaccine_id='' OR ov.person_vaccine_id IS NULL ,'ไม่ระบุวัคซีน,','')
                              ,if(pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype) ,'รัหสวัคซีนไม่ตรงมาตฐาน,',''))as err
                        FROM ovst_vaccine ov
                        LEFT OUTER JOIN person_vaccine pc ON pc.person_vaccine_id=ov.person_vaccine_id
                        LEFT OUTER JOIN vn_stat v ON v.vn=ov.vn
                        LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                        LEFT OUTER JOIN patient pt ON pt.cid=v.cid
                        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=ov.person_vaccine_id
                        WHERE v.vstdate BETWEEN '$date_start' and '$date_end'
                        AND (ov.person_vaccine_id='' OR ov.person_vaccine_id IS NULL
                        OR pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype)) ";
        $data1 = Yii::$app->db2->createCommand($sql_detail3)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
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
        if (Yii::$app->request->isPost) {
            $date_start= $_POST['date_start'];
            $date_end = $_POST['date_end'];
        }
        
        $sql_detail4 = "SELECT pt.hn,CONCAT(pt.pname,pt.fname,' ',pt.lname) AS ptname,v.age_y,pv.vaccine_date,d.name  AS doc_name,
                        CONCAT(if(pv.baby_code='' OR pv.baby_code IS NULL ,'ไม่ระบุวัคซีน,','')
                              ,if(pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype) ,'รัหสวัคซีนไม่ตรงมาตฐาน,',''))as err
                        FROM patient_vaccine pv
                        INNER JOIN person_vaccine pc ON pc.person_vaccine_id=pv.baby_code
                        INNER JOIN ovst_vaccine ov ON ov.person_vaccine_id=pc.person_vaccine_id
                        LEFT OUTER JOIN vn_stat v ON v.vn=ov.vn
                        LEFT OUTER JOIN doctor d  ON d.code=v.dx_doctor
                        INNER JOIN patient pt ON pt.hn=pv.hn
                        LEFT OUTER JOIN person_vaccine_list pl ON pl.person_vaccine_id=pv.baby_code
                        WHERE pv.vaccine_date  BETWEEN '$date_start' and '$date_end'
                        AND (pv.baby_code='' OR pv.baby_code IS NULL
                        OR pc.export_vaccine_code NOT IN(SELECT code FROM provis_vcctype)) ";
        $data1 = Yii::$app->db2->createCommand($sql_detail4)->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data1,
        ]);

        return $this->render('detail4', [
            'date_start' => $date_start,
            'date_end' => $date_end,
            'dataProvider' => $dataProvider,
            'sql' => $sql_detail4]);
    }
}

