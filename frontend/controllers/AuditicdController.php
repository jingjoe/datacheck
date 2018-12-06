<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use yii\db\Query;
use yii\data\ArrayDataProvider;

//AccessControl
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AuditicdController extends Controller
{
  public $enableCsrfValidation = false;
  public function behaviors() {

      $role = 0;
      if (!Yii::$app->user->isGuest) {
          $role = Yii::$app->user->identity->role;
      }

      $arr = [''];
      if ($role == 1 ) {
          $arr = ['rep1','rep2','rep3','rep4','rep5','rep6','rep7','rep8'];
      }
      if( $role == 2) {
           $arr = ['rep1','rep2','rep3','rep4','rep5','rep6','rep7','rep8'];
      }
      if( $role == 3) {
           $arr = ['rep1','rep2','rep3','rep4','rep5','rep6','rep7','rep8'];
      }

      return [
          'access' => [
              'class' => \yii\filters\AccessControl::className(),
              'denyCallback' => function ($rule, $action) {
                  throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
              },
              'only' => ['rep1','rep2','rep3','rep4','rep5','rep6','rep7','rep8'],
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
  public function actionDoc() {
      $path = Yii::getAlias('@webroot') . '/documents';
      $file = $path . '/audit_icd10_2016.pdf';
      if (file_exists($file)) {
      Yii::$app->response->sendFile($file);
      }
  }

  public function actionRep1() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,full_name,IF(sex=1,'เพศชาย','เพศหญิง') AS sex,age_y,vstdate,pdx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND sex='1'
              AND (left(pdx,3) IN ('A34','D06','D25','D26','D27','D28','D39','E28','F53',
              'Q50','Q51','Q52','R87','Y76','Z32','Z33','Z34','Z35','Z36','Z39')
              OR (left(pdx,4) IN ('B373','C796','E894','F525','I863','L292','L705','M800',
              'M801','M810','M811','M830','N992','N993','P546','S314','S374','S375','S376',
              'T192','T193','T833','Z014','Z124','Z301','Z303','Z305','Z311','Z312','Z437',
              'Z875','Z975'))
              OR (left(pdx,3) BETWEEN 'C51' AND 'C58')
              OR (left(pdx,3) BETWEEN 'O00' AND 'O99')
              OR (left(pdx,3) BETWEEN 'N70' AND 'N98')
              OR (left(pdx,4) BETWEEN 'D070' AND 'D073'))
              GROUP BY vn
              ORDER BY vstdate DESC ";


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


      $sql = "SELECT hn,full_name,age_y,vstdate,pdx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND (pdx LIKE 'v%'
              OR pdx LIKE 'w%'
              OR pdx LIKE 'x%'
              OR pdx LIKE 'y%')
              GROUP BY vn
              ORDER BY vstdate DESC ";


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


      $sql = "SELECT hn,full_name,age_y,vstdate,pdx,CONCAT(dx0,',',dx1,',',dx2,',',dx3) AS dx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND (pdx LIKE 'S%' OR pdx LIKE 'T%')
              AND dx0 NOT LIKE  'v%'AND dx0 NOT LIKE  'w%'
              AND dx0 NOT LIKE  'x%'AND dx0 NOT LIKE  'y%'
              AND dx1 NOT LIKE  'v%'AND dx1 NOT LIKE  'w%'
              AND dx1 NOT LIKE  'x%'AND dx1 NOT LIKE  'y%'
              AND dx2 NOT LIKE  'v%'AND dx2 NOT LIKE  'w%'
              AND dx2 NOT LIKE  'x%'AND dx2 NOT LIKE  'y%'
              AND dx3 NOT LIKE  'v%'AND dx3 NOT LIKE  'w%'
              AND dx3 NOT LIKE  'x%'AND dx3 NOT LIKE  'y%'
              GROUP BY vn
              ORDER BY vstdate DESC";


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


      $sql = "SELECT hn,full_name,age_y,vstdate,pdx,CONCAT(dx0,',',dx1,',',dx2,',',dx3) AS dx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND pdx BETWEEN 'Z230' AND 'Z279'
              AND (dx0 BETWEEN 'Z000' AND 'Z029'
              OR dx1 BETWEEN 'Z000' AND 'Z029'
              OR dx2 BETWEEN 'Z000' AND 'Z029'
              OR dx3 BETWEEN 'Z000' AND 'Z029')
              GROUP BY vn
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


      $sql = "SELECT hn,full_name,age_y,vstdate,pdx,CONCAT(dx0,',',dx1,',',dx2,',',dx3) AS dx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND pdx BETWEEN 'T310' AND 'T319'
              GROUP BY vn
              ORDER BY vstdate DESC ";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep5', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep6() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,full_name,age_y,vstdate,icd10,diagtype,dep,doc_name
              FROM tmp_diag_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND icd10 BETWEEN 'V00' AND 'Y34'
              AND LENGTH(icd10)!='5'
              GROUP BY vn
              ORDER BY vstdate DESC";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep6', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep7() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }


      $sql = "SELECT hn,full_name,age_y,vstdate,pdx,CONCAT(dx0,',',dx1,',',dx2,',',dx3) AS dx,dep,doc_name
              FROM  tmp_visit_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND pdx BETWEEN 'Z470' AND 'Z489'
              AND (dx0 LIKE  'S%'  OR  dx0 LIKE  'T%'
              OR dx1 LIKE  'S%' OR  dx1 LIKE  'T%'
              OR dx2 LIKE  'S%' OR  dx2 LIKE  'T%'
              OR dx3 LIKE  'S%' OR  dx3 LIKE  'T%')
              GROUP BY vn
              ORDER BY vstdate DESC ";


      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep7', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
  public function actionRep8() {
      $sql_date = Yii::$app->db->createCommand('SELECT date FROM set_datetime')->queryOne();
      $date1 =  $sql_date['date'];
      $date2 = date('Y-m-d');
      if (Yii::$app->request->isPost) {
          $date1 = $_POST['date1'];
          $date2 = $_POST['date2'];
      }

      $sql = "SELECT hn,full_name,age_y,vstdate,icd10,diagtype,dep,doc_name
              FROM tmp_diag_opd
              WHERE  vstdate BETWEEN '$date1'and '$date2'
              AND icd10 IN('J069','D229','L029','L039','T07','Z349')
              OR icd10 BETWEEN 'T14' AND 'T149'
              GROUP BY vn
              ORDER BY vstdate DESC ";

      $data = Yii::$app->db->createCommand($sql)->queryAll();
      $dataProvider = new ArrayDataProvider([
          'allModels'=>$data,
      ]);

      return $this->render('rep8', ['dataProvider' => $dataProvider,'sql' => $sql,'date1' => $date1, 'date2' => $date2]);
  }
}
