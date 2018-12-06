<?php

namespace frontend\controllers;

class HelpController extends \yii\web\Controller {
    public function actionPatient(){
        return $this->render('patient');
    }  
    public function actionPatientkey(){
        return $this->render('patient_key');
    }
    
    public function actionPerson(){
        return $this->render('person');
    }
    public function actionPersonkey(){
        return $this->render('person_key');
    }
    
    public function actionAddress(){
        return $this->render('address');
    }
    public function actionAddresskey(){
        return $this->render('address_key');
    }
    
    public function actionDeath(){
        return $this->render('death');
    }
    public function actionDeathkey(){
        return $this->render('death_key');
    }
    
    public function actionProvider(){
        return $this->render('provider');
    }
    public function actionProviderkey(){
        return $this->render('provider_key');
    }
    
    public function actionDrugallergy(){
        return $this->render('drugallergy');
    }
    public function actionDrugallergykey(){
        return $this->render('drugallergy_key');
    }
    
    public function actionLabor(){
        return $this->render('labor');
    }
    public function actionLaborkey(){
        return $this->render('labor_key');
    }
    
    public function actionNewborn(){
        return $this->render('newborn');
    }
    public function actionNewbornkey(){
        return $this->render('newborn_key');
    }
    
    public function actionService(){
        return $this->render('service');
    }
    public function actionServicekey(){
        return $this->render('service_key');
    }
    
    public function actionDiagnosisopd(){
        return $this->render('diagnosisopd');
    }
    public function actionDiagnosisopdkey(){
        return $this->render('diagnosisopd_key');
    }
    
    public function actionDrugopd(){
        return $this->render('drugopd');
    }
    public function actionDrugopdkey(){
        return $this->render('drugopd_key');
    }
    
    public function actionProcedureopd(){
        return $this->render('procedureopd');
    }
    public function actionProcedureopdkey(){
        return $this->render('procedureopd_key');
    }
    
    public function actionProcedureipd(){
        return $this->render('procedureipd');
    }
    public function actionProcedureipdkey(){
        return $this->render('procedureipd_key');
    }
    
    public function actionAccident(){
        return $this->render('accident');
    }
    public function actionAccidentkey(){
        return $this->render('accident_key');
    }
    
    public function actionDiagnosisipd(){
        return $this->render('diagnosisipd');
    }
    public function actionDiagnosisipdkey(){
        return $this->render('diagnosisipd_key');
    }
    
    public function actionAppointment(){
        return $this->render('appointment');
    }
    public function actionAppointmentkey(){
        return $this->render('appointment_key');
    }
    
    public function actionDental(){
        return $this->render('dental');
    }
    public function actionDentalkey(){
        return $this->render('dental_key');
    }
    
    public function actionRehabilitation(){
        return $this->render('rehabilitation');
    }
    public function actionRehabilitationkey(){
        return $this->render('rehabilitation_key');
    }
    
    public function actionAnc(){
        return $this->render('anc');
    }
    public function actionAnckey(){
        return $this->render('anc_key');
    }
    
    public function actionPostnatal(){
        return $this->render('postnatal');
    }
    public function actionPostnatalkey(){
        return $this->render('postnatal_key');
    }
      
    public function actionNewborncare(){
        return $this->render('newborncare');
    }
    public function actionNewborncarekey(){
        return $this->render('newborncare_key');
    }
    
    public function actionPolicy(){
        return $this->render('policy');
    }
    public function actionPolicykey(){
        return $this->render('policy_key');
    }
    
    public function actionReferhistory(){
        return $this->render('referhistory');
    }
    public function actionReferhistorykey(){
        return $this->render('referhistory_key');
    }
    
    public function actionChronic(){
        return $this->render('chronic');
    }
    public function actionChronickey(){
        return $this->render('chronic_key');
    }
    
    public function actionPrenatal(){
        return $this->render('prenatal');
    }
    public function actionPrenatalkey(){
        return $this->render('prenatal_key');
    }
    
    public function actionSpecialpp(){
        return $this->render('specialpp');
    }
    public function actionSpecialppkey(){
        return $this->render('specialpp_key');
    }
    
    public function actionCommunityservice(){
        return $this->render('communityservice');
    }
    public function actionCommunityservicekey(){
        return $this->render('communityservice_key');
    }
    
    public function actionWomen(){
        return $this->render('women');
    }
    public function actionWomenkey(){
        return $this->render('women_key');
    }
    
    public function actionCard(){
        return $this->render('card');
    }
    public function actionCardkey(){
        return $this->render('card_key');
    }
    
    public function actionFp(){
        return $this->render('fp');
    }
    public function actionFpkey(){
        return $this->render('fp_key');
    }
    
    public function actionFunctional(){
        return $this->render('functional');
    }
    public function actionFunctionalkey(){
        return $this->render('functional_key');
    }
    
    public function actionIcf(){
        return $this->render('icf');
    }
    public function actionIcfkey(){
        return $this->render('icf_key');
    }
    
    public function actionR24(){
        return $this->render('error_r24');
    }
    public function actionS15(){
        return $this->render('error_s15');
    }
    public function actionC07(){
        return $this->render('error_c07');
    }
    public function actionS18(){
        return $this->render('error_s18');
    }
    public function actionS14(){
        return $this->render('error_s14');
    }
    public function actionT32(){
        return $this->render('error_t32');
    }
    public function actionS41(){
        return $this->render('error_s41');
    }
    public function actionS19(){
        return $this->render('error_s19');
    }
}
