<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AuditpersonController extends Controller{
  public $enableCsrfValidation = false;
  public function behaviors() {

      $role = 0;
      if (!Yii::$app->user->isGuest) {
          $role = Yii::$app->user->identity->role;
      }

      $arr = [''];
      if ($role == 1 ) {
          $arr = ['rep0','rep1','rep2','rep3','rep4','rep5'];
      }
      if( $role == 2) {
           $arr = ['rep0','rep1','rep2','rep3','rep4','rep5'];
      }
      if( $role == 3) {
           $arr = ['rep0','rep1','rep2','rep3','rep4','rep5'];
      }

      return [
          'access' => [
              'class' => \yii\filters\AccessControl::className(),
              'denyCallback' => function ($rule, $action) {
                  throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
              },
              'only' => ['rep0','rep1','rep2','rep3','rep4','rep5'],
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
  public function actionRep0() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,nationality,type_area,last_update,vstdate
              FROM tmp_patient_visit
              WHERE vstdate  BETWEEN  '$date1'and '$date2'
              AND nationality='99'
              AND mod11(cid)=0 "; 


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep0', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep1() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,nationality,type_area,last_update,vstdate
              FROM tmp_patient_visit
              WHERE vstdate  BETWEEN  '$date1'and '$date2'
              AND nationality='99'
              AND SUBSTR(cid, 2, 5)<>'11418'
              AND  LEFT(cid, 1)  NOT IN('1','2','3','4','5','6','7','8')
              OR LENGTH(cid)!='13' OR (cid='' OR cid IS NULL)
              OR cid LIKE '11111%' AND hn<>'' AND death<>'Y'  ";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep1', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep2() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,nationality,type_area,last_update,vstdate
              FROM tmp_patient_visit
              WHERE vstdate  BETWEEN  '$date1'and '$date2'
              AND SUBSTR(cid, 2, 5)='11418'
              AND nationality='99'
              AND death<>'Y' ";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep2', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep3() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }

      $sql = "SELECT hn,cid,CONCAT(pname,fname,' ',lname) AS full_name,addr,nationality,bloodgrp,educate,type_area,
              pttype,pttypeno,pttype_begin,pttype_expire,hospmain,hospsub,vstdate,last_update
              FROM tmp_patient_visit
              WHERE vstdate  BETWEEN  '$date1'and '$date2'
              AND cid NOT IN(SELECT cid FROM tmp_person_visit)
              ORDER BY vstdate DESC ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep3', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep4() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT person_id,cid,patient_hn,CONCAT(pname,fname,' ',lname) AS full_name,
              nationality,house_regist_type_id,person_discharge_id,last_update,vstdate
              FROM tmp_person_visit
              WHERE vstdate BETWEEN '$date1'and '$date2'
              AND nationality<>'99'
              AND (person_labor_type_id='' OR person_labor_type_id IS NULL)
              AND death<>'Y'
              ORDER BY vstdate DESC ";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep4', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep5() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }

      $sql = "SELECT person_id,cid,patient_hn,CONCAT(pname,fname,' ',lname) AS full_name,
              nationality,house_regist_type_id,person_discharge_id,last_update,vstdate
              FROM tmp_person_visit
              WHERE vstdate BETWEEN '$date1'and '$date2'
              AND person_id NOT IN(SELECT person_id FROM hos.person_address)
              ORDER BY vstdate DESC  ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep5', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }

}
