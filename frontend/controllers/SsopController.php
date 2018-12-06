<?php

namespace frontend\controllers;
use yii\web\Controller;
use yii\db\Query;
use Yii;
use yii\data\ArrayDataProvider;
//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class SsopController extends Controller {
    public $enableCsrfValidation = false;
    public function behaviors() {

        $role = 0;
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
        }
        
        $arr = [''];
        if ($role == 1 ) {
            $arr =  ['index','rep01','rep02','rep03','rep04','rep5','rep6','rep7','rep8','rep9','rep10',
                    'rep01detail','rep02detail','rep03detail','rep04detail','rep05detail','rep06detail',
                    'rep07detail','rep08detail','rep09detail','rep10detail'];
        }
        if( $role == 2) {
             $arr = ['index','rep01','rep02','rep03','rep04','rep5','rep6','rep7','rep8','rep9','rep10',
                    'rep01detail','rep02detail','rep03detail','rep04detail','rep05detail','rep06detail',
                    'rep07detail','rep08detail','rep09detail','rep10detail'];
        }
        if( $role == 3) {
             $arr = ['index','rep01','rep02','rep03','rep04','rep5','rep6','rep7','rep8','rep9','rep10',
                    'rep01detail','rep02detail','rep03detail','rep04detail','rep05detail','rep06detail',
                    'rep07detail','rep08detail','rep09detail','rep10detail'];
        }

        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
                },
                'only' => ['rep01detail', 'rep02detail', 'rep03detail', 'rep04detail', 'rep05detail',
                           'rep06detail','rep07detail','rep08detail','rep09detail','rep10detail'],
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
    
    public function actionDoc1() {
      $path = Yii::getAlias('@webroot') . '/documents';
      $file = $path . '/sks_standardcode icd10cm_tm_update2018.xlsx';
      if (file_exists($file)) {
      Yii::$app->response->sendFile($file);
      }
    }
    public function actionDoc2() {
      $path = Yii::getAlias('@webroot') . '/documents';
      $file = $path . '/sks_standardcode icd9cm_tm_update2018.xlsx';
      if (file_exists($file)) {
      Yii::$app->response->sendFile($file);
      }
    }
    
   public function actionIndex($y = NULL , $m = NULL) {

        $y = "2561";
            if (Yii::$app->request->isPost) {$y = $_POST['y'];
        }
                   
        $m = "01";
            if (Yii::$app->request->isPost) {$m = $_POST['m'];
        }
        
        $sql_date = Yii::$app->db->createCommand('SELECT end_process FROM tmp_ssop_error_s18 LIMIT 1')->queryOne();
        $process =  $sql_date['end_process'];
        
        $sql_chart1 = "SELECT COUNT(icode) AS cc
                       FROM tmp_ssop_error_r24
                       WHERE y=$y
                       AND m=$m ";
        
        $sql_chart2 = "SELECT COUNT(dcode) AS cc
                       FROM tmp_ssop_error_s15
                       WHERE y=$y
                       AND m=$m ";
        
        $sql_chart3 = "SELECT COUNT(vn) AS cc
                       FROM tmp_ssop_error_c07
                       WHERE y=$y
                       AND m=$m
                       AND hospmain<>hoschk ";
        
        $sql_chart4 = "SELECT COUNT(vn) AS cc
                        FROM tmp_ssop_error_s18
                        WHERE icd10 NOT IN(SELECT `code` FROM l_ssop_icd10)
                        AND y=$y
                        AND m=$m ";
        
        $sql_chart5 = "SELECT COUNT(vn) AS cc
                        FROM tmp_ssop_error_s14
                        WHERE  y=$y
                        AND m=$m ";
        
        $sql_chart6 = "SELECT COUNT(vn) AS cc
                        FROM tmp_ssop_error_t32
                        WHERE  y=$y
                        AND m=$m ";
        $sql_chart7 = "SELECT COUNT(vn) AS cc
                        FROM tmp_ssop_error_s41
                        WHERE  y=$y
                        AND m=$m ";
        $sql_chart8 = "SELECT COUNT(vn) AS cc
                        FROM tmp_ssop_error_s19
                        WHERE err<>''
                        AND  y=$y
                        AND m=$m ";
        
        $chart1 = Yii::$app->db->createCommand($sql_chart1)->queryAll();
        $chart2 = Yii::$app->db->createCommand($sql_chart2)->queryAll();
        $chart3 = Yii::$app->db->createCommand($sql_chart3)->queryAll();
        $chart4 = Yii::$app->db->createCommand($sql_chart4)->queryAll();
        $chart5 = Yii::$app->db->createCommand($sql_chart5)->queryAll();
        $chart6 = Yii::$app->db->createCommand($sql_chart6)->queryAll();
        $chart7 = Yii::$app->db->createCommand($sql_chart7)->queryAll();
        $chart8 = Yii::$app->db->createCommand($sql_chart8)->queryAll();

        return $this->render('index', [
            'y' => $y,  
            'm' => $m,
            'date_process' => $process,
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'chart4' => $chart4,
            'chart5' => $chart5,
            'chart6' => $chart6,
            'chart7' => $chart7,
            'chart8' => $chart8]);
    }
    
   public function actionRep01($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(icode) AS cc
                FROM tmp_ssop_error_r24
                WHERE y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep01', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep01detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype,vstdate,vsttime,drug_name,pdx,last_modified
                FROM tmp_ssop_error_r24
                WHERE y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate,vsttime ";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep01detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    
    public function actionRep02($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctorcode,staff_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_c07
                WHERE y=$y
                AND m=$m
                AND hospmain<>hoschk
                GROUP BY  doctorcode
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep02', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep03detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,vstdate,vsttime,pttype_name,hospmain,hospsub,hoschk,send_from_dep,send_to_dep
                FROM tmp_ssop_error_c07
                WHERE y=$y
                AND m=$m
                AND doctorcode=$dcode
                AND hospmain<>hoschk
                ORDER BY vstdate ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep03detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    public function actionRep02detail($y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT dcode,cid,doctor_name,position,licenseno,active,update_datetime
                FROM tmp_ssop_error_s15
                WHERE y=$y
                AND m=$m ";
        
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep02detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m]);       
    } 
    
    public function actionRep03($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_s18
                WHERE icd10 NOT IN(SELECT `code` FROM l_ssop_icd10)
                AND y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep03', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep04detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype,vstdate,vsttime,CONCAT(icd10,' : ',dx_name) AS dx,doctor_name,
                CONCAT(IF(icd10 NOT IN(SELECT `code` FROM l_ssop_icd10) ,'ตรวจสอบการให้รหัสโรคว่าถูกต้องหรือสัมพันธ์กับ ICD10CM_TM หรือไม่',''))as err
                FROM tmp_ssop_error_s18
                WHERE icd10 NOT IN(SELECT `code` FROM l_ssop_icd10)
                AND y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate,vsttime ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep04detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    
    public function actionRep04($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_s14
                WHERE  y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep04', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep05detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype,vstdate,nextdate,dep,clinic,end_process
                FROM tmp_ssop_error_s14
                WHERE y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep05detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    
    public function actionRep05($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_t32
                WHERE  y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep05', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep06detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype_name,vstdate,vsttime,CONCAT(icode,' : ',nondrug_name) AS nondrug_name,paidst_name,last_modified
                FROM tmp_ssop_error_t32
                WHERE y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep06detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    
    public function actionRep06($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_s41
                WHERE  y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep06', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep07detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype_name,vstdate,vsttime,CONCAT(icode,' : ',nondrug_name) AS nondrug_name,last_modified
                FROM tmp_ssop_error_s41
                WHERE y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep07detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }
    
    public function actionRep07($y=NULL,$m=NULL) {
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }

        $sql = "SELECT doctor_code,doctor_name,COUNT(vn) AS cc
                FROM tmp_ssop_error_s19
                WHERE err<>''
                AND  y=$y
                AND m=$m
                GROUP BY  doctor_code
                ORDER BY cc DESC ";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data,
        ]);

        return $this->render('rep07', ['dataProvider' => $dataProvider, 'chart' => $data,'y' => $y,'m' => $m]);
    } 
    
    public function actionRep08detail($dcode=NULL,$y=NULL,$m=NULL) {
        
        if (Yii::$app->request->isPost) {
            $y = $_POST['y'];
            $m = $_POST['m'];
        }
        $sql = "SELECT hn,full_name,pttype_name,vstdate,vsttime,opercode,IF(icode_link='Y','ผูกค่าบริการกับหัตถการ','ไม่ผูกค่าบริการกับหัตถการ') AS link,last_modified,err
                FROM tmp_ssop_error_s19
                WHERE err<>''
                AND y=$y
                AND m=$m
                AND doctor_code=$dcode
                ORDER BY vstdate";

        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels'=>$data
          
        ]);

        return $this->render('rep08detail', [
            'dataProvider' => $dataProvider,
            'y' => $y,
            'm' => $m,
            'dcode' => $dcode]);       
    }

}


