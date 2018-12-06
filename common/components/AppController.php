<?php

namespace common\components;

use Yii;

class AppController extends \yii\web\Controller {

    public function init() {
        parent::init();
    }
    
    protected function doLogin(){
        if (\Yii::$app->user->isGuest) {
            $this->redirect(['site/login']);           
        }
    }

    
    protected function getRole(){
        if (!\Yii::$app->user->isGuest) {
             return \Yii::$app->user->identity->role;
         }  else {
             return "?";            
         }
    }

    public function permitRole($role=[]){ 
        
        $r = $this->getRole();   
        if(empty($role)){
             throw  new \yii\web\ForbiddenHttpException("ไม่ได้รับอนุญาต");
             
        }
        if( !in_array($r,$role)){
            throw  new \yii\web\ForbiddenHttpException("ไม่ได้รับอนุญาต");
        }         
        
    }

}
