<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\components\AppController;

class ProcessreportController extends AppController{
    
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    
    protected function call($store_name, $arg = NULL) {
        $sql = "";
        if ($arg != NULL) {
            $sql = "call " . $store_name . "(" . $arg . ");";
        } else {
            $sql = "call " . $store_name . "();";
        }
        $this->exec_sql($sql);
    }

    protected function exec_sql($sql) {
        $affect_row = \Yii::$app->db->createCommand($sql)->execute();
        return $affect_row;
    }
    public function actionIndex(){
        $this->permitRole([1]);
        return $this->render('index');
    }
    
    public function actionExec() {
        // ระมวลผลหน้า Dashboard
            $this->call("cal_opd_visit", NULL);
            $this->call("cal_ipd_admit", NULL);
            
        //ประมวลผลตรวจสอบคุณภาพข้อมูล
            $this->call("cal_diag_opd", NULL);
            $this->call("cal_patient_visit", NULL);
            $this->call("cal_person_visit", NULL);
            
        //ประมวลผลข้อมูล ตรวจสอบ 43 แฟ้มปีงบประมาณ 2560
            $this->call("cal_nodiag_opd", NULL);
            $this->call("cal_nodiag_ipd", NULL);
        
        //ตรวจสอบข้อมูล SSOP สิทธิประกันสังคม  
            $this->call("cal_ssop_error_r24", NULL);
            $this->call("cal_ssop_error_s15", NULL);
            $this->call("cal_ssop_error_c07", NULL);
            $this->call("cal_ssop_error_s18", NULL);
            $this->call("cal_ssop_error_s14", NULL);
            $this->call("cal_ssop_error_t32", NULL);
            $this->call("cal_ssop_error_s41", NULL);
            $this->call("cal_ssop_error_s19", NULL);
            
    }
}
