<?php

namespace backend\controllers;

use Yii;
use backend\models\Fileimport;
use backend\models\FileimportSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

// Add upload
use yii\web\UploadedFile;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use yii\helpers\Html;
use yii\helpers\Url;

// Add Models Drugcatalogue
use backend\models\Drugcatalogue;

// Add for database MySQL
use yii\db\Command;
use yii\db\Connection;

/**
 * FileimportController implements the CRUD actions for Fileimport model.
 */
class FileimportController extends Controller{
    public function behaviors() {
        
        $role = isset(Yii::$app->user->identity->role) ? Yii::$app->user->identity->role : 99;

        $arr = array();
        if ($role == 1) {
            $arr = ['index', 'view', 'create', 'update', 'delete','truncate','excel'];
        } else {
            $arr = [''];
        }
     
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'denyCallback' => function ($rule, $action) {
                    throw new \yii\web\ForbiddenHttpException("คุณไม่ได้รับอนุญาต");
                },
                'only' => ['index', 'view', 'create', 'update', 'delete','truncate','excel'],
                'rules' => [
                    [
                        'allow' => TRUE,
                        'actions' => $arr,
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => TRUE,
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

    public function actionIndex()
    {
        $model3 = new Drugcatalogue();
        $searchModel = new FileimportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model3'=>$model3,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

  
    public function actionView($id)
    {
        $model = $this->findModel($id);
        try{
           // $file =  Yii::$app->response->sendFile($model->getDocPath() . '/' . $model->file);
            $file = Yii::getAlias('@webroot').'/'.$model->uploadPath.'/'.$model->file;
            $inputFile = \PHPExcel_IOFactory::identify($file);
            $objReader = \PHPExcel_IOFactory::createReader($inputFile);
            $objPHPExcel = $objReader->load($file);
        }catch (Exception $e){
            Yii::$app->session->addFlash('error', 'เกิดข้อผิดพลาด'. $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $objWorksheet = $objPHPExcel->getActiveSheet();

        foreach($objWorksheet->getRowIterator() as $rowIndex => $row){
            $arr[] = $objWorksheet->rangeToArray('A'.$rowIndex.':'.$highestColumn.$rowIndex);
        }
        //print_r($arr);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $arr,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    
    public function actionCreate() {

        $model = new Fileimport();
        $model->token_upload = substr(Yii::$app->getSecurity()->generateRandomString(), 10);

        if ($model->load(Yii::$app->request->post())) {

            //  $this->Uploads(false);

            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
                $fileName = ($model->file);
                $image = $model->file;
                $model->file = $fileName;
                $image->saveAs('drugcat/' . $fileName);
                if ($model->save()) {

                    Yii::$app->session->setFlash('success', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
                    return $this->redirect('index.php?r=fileimport/index');
                }
            } else if ($model->save()) {
                    return $this->redirect('index.php?r=fileimport/index');
            }
        }

        return $this->render('create', [
                    'model' => $model
        ]);
    }

    public function actionUpdate($id) {

        $model = $this->findModel($id);
        $tempResume = $model->file;

        if ($model->load(Yii::$app->request->post())) {

            //$this->Uploads(false);

            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->file && $model->validate()) {
                $fileName = ($model->file);
                $image = $model->file;
                $model->file = $fileName;
                $image->saveAs('drugcat/' . $fileName);
                if ($model->save()) {
                   // return $this->redirect(['view', 'id' => $model->sql_id]);
                    Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
                    return $this->redirect('index.php?r=fileimport/index');
                }
            } else {
                $model->file = $tempResume;
                if ($model->save()) {
                    return $this->redirect('index.php?r=fileimport/index');
                }
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'ลบข้อมูลเรียบร้อยแล้ว');
        return $this->redirect(['index']);
    }

    public function actionTruncate(){
        Yii::$app->db->createCommand()->truncateTable('drugcatalogue')->execute();
        Yii::$app->session->setFlash('success', 'Truncat Table Success');
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Fileimport::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

 // Import File excel To Databases    
    public function actionExcel($type, $id){
        $model = $this->findModel($id);
    //$inputFile = 'uploads/siswa_file.xlsx';
        $inputFile = Yii::getAlias('@webroot').'/'.$model->uploadPath.'/'.$model->file;
    try{
        $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFile);
    } catch (Exception $e) {
        die('Error');
    }

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    for($row=1; $row <= $highestRow; $row++)
    {
        $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

        if($row==1)
        {
            continue;
        }

        $model2 = new Drugcatalogue();
        $model2->hospdrugcode = $rowData[0][0]; 
        $model2->productcat  = $rowData[0][1]; 
        $model2->tmtid  = $rowData[0][2]; 
        $model2->specprep  = $rowData[0][3]; 
        $model2->genericname  = $rowData[0][4]; 
        $model2->trandename  = $rowData[0][5]; 
        $model2->dfscode  = $rowData[0][6]; 
        $model2->dosageform  = $rowData[0][7]; 
        $model2->strength  = $rowData[0][8]; 
        $model2->content = $rowData[0][9];
        $model2->unitprice = $rowData[0][10];
        $model2->distributor = $rowData[0][11];
        $model2->manufacturer = $rowData[0][12];
        $model2->ised = $rowData[0][13];
        $model2->ndc24 = $rowData[0][14];
        $model2->packsize = $rowData[0][15];
        $model2->packprice = $rowData[0][16];
        $model2->updateflag = $rowData[0][17];
        $model2->datechange = $rowData[0][18];
        $model2->dateupdate = $rowData[0][19];
        $model2->dateeffective = $rowData[0][20];
        $model2->ised_approved = $rowData[0][21];
        $model2->ndc24_approved = $rowData[0][22];
        $model2->date_approved = $rowData[0][23];
        $model2->ised_status = $rowData[0][24];
        $model2->file_id = $id;
        $model2->file_name = $inputFile;
        $model2->status = 'success';
        $model2->save();

        //print_r($model2->getErrors());
    }   
        Yii::$app->session->setFlash('success', 'Import Data Success');
        return $this->redirect('index.php?r=fileimport/index');
        //die('okay');
}
}
