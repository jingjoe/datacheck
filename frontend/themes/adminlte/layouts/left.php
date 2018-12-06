<?php
use yii\helpers\Html;

$username = 'ผู้ใช้งานทั่วไป';
?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user_datacheck.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p>
                    <?php
                          if(Yii::$app->user->isGuest)  {
                              print("สวัสดี ". '[' . $username . ']');
                          } else {

                              print("สวัสดี". '[' . Html::encode(Yii::$app->user->identity->username) . ']');
                    }?>
                </p>
                <a href="#"><i class="fa fa-circle text-success"></i> ออนไลน์</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                //['label' => 'Gii', 'icon' => 'fa fa-file-code-o', 'url' => ['/gii']],
                //['label' => 'Debug', 'icon' => 'fa fa-dashboard', 'url' => ['/debug']],
                //['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
                //ตรวจสอบข้อมูล 43 แฟ้ม
                    [
                        'label' => 'ตรวจสอบข้อมูล 43 แฟ้ม',
                        'icon' => 'fa fa-dashboard text-orange',
                        'url' => '#',
                        'items' => [
                        ['label' => 'แฟ้มสะสม', 'options' => ['class' => 'box-header']],
                            [
                                'label' => 'แฟ้ม PERSON',
                                'icon' => 'fa fa-circle-o text-red',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'PATIENT ทะเบียนผู้ป่วย', 'icon' => 'fa fa-circle-o text-yellow', 'url' => ['patient/index'],],
                                    ['label' => 'PERSON บัญชี1', 'icon' => 'fa fa-circle-o text-aqua', 'url' => ['person/index'],],
                                ],
                            ],
                            ['label' => 'แฟ้ม ADDRESS', 'icon' => 'fa fa-circle-o text-red', 'url' => ['address/index'],],
                            ['label' => 'แฟ้ม DEATH', 'icon' => 'fa fa-circle-o text-red', 'url' => ['death/index'],],
                            ['label' => 'แฟ้ม CHRONIC', 'icon' => 'fa fa-circle-o text-red', 'url' => ['chronic/index'],],
                            ['label' => 'แฟ้ม CARD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['card/index'],],
                            ['label' => 'แฟ้ม PROVIDER', 'icon' => 'fa fa-circle-o text-red', 'url' => ['provider/index'],],
                            ['label' => 'แฟ้ม WOMEN', 'icon' => 'fa fa-circle-o text-red', 'url' => ['women/index'],],
                            ['label' => 'แฟ้ม DRUGALLERGY', 'icon' => 'fa fa-circle-o text-red', 'url' => ['drugallergy/index'],],
                            ['label' => 'แฟ้ม PRENATAL', 'icon' => 'fa fa-circle-o text-red', 'url' => ['prenatal/index'],],
                            ['label' => 'แฟ้ม LABOR', 'icon' => 'fa fa-circle-o text-red', 'url' => ['labor/index'],],
                            ['label' => 'แฟ้ม NEWBORN', 'icon' => 'fa fa-circle-o text-red', 'url' => ['newborn/index'],],
                        ['label' => 'แฟ้มบริการ', 'options' => ['class' => 'box-header']],
                            ['label' => 'แฟ้ม FUNCTIONAL', 'icon' => 'fa fa-circle-o text-red', 'url' => ['functional/index'],],
                            ['label' => 'แฟ้ม ICF', 'icon' => 'fa fa-circle-o text-red', 'url' => ['icf/index'],],
                            ['label' => 'แฟ้ม SERVICE', 'icon' => 'fa fa-circle-o text-red', 'url' => ['service/index'],],
                            ['label' => 'แฟ้ม DIAGNOSIS_OPD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['diagnosisopd/index'],],
                            ['label' => 'แฟ้ม DRUG_OPD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['drugopd/index'],],
                            ['label' => 'แฟ้ม PROCEDURE_OPD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['procedureopd/index'],],
                            ['label' => 'แฟ้ม SURVEILLANCE', 'icon' => 'fa fa-circle-o text-red', 'url' => ['surveillance/index'],],
                            ['label' => 'แฟ้ม ACCIDENT', 'icon' => 'fa fa-circle-o text-red', 'url' => ['accident/index'],],
                            ['label' => 'แฟ้ม DIAGNOSIS_IPD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['diagnosisipd/index'],],
                            ['label' => 'แฟ้ม PROCEDURE_IPD', 'icon' => 'fa fa-circle-o text-red', 'url' => ['procedureipd/index'],],
                            ['label' => 'แฟ้ม APPOINTMENT', 'icon' => 'fa fa-circle-o text-red', 'url' => ['appointment/index'],],
                            ['label' => 'แฟ้ม DENTAL', 'icon' => 'fa fa-circle-o text-red', 'url' => ['dental/index'],],
                            ['label' => 'แฟ้ม FP', 'icon' => 'fa fa-circle-o text-red', 'url' => ['fp/index'],],
                            ['label' => 'แฟ้ม COMMUNITY_SERVICE', 'icon' => 'fa fa-circle-o text-red', 'url' => ['communityservice/index'],],
                            ['label' => 'แฟ้ม REFER_HISTORY', 'icon' => 'fa fa-circle-o text-red', 'url' => ['referhistory/index'],],
                        ['label' => 'แฟ้มบริการกึ่งสำรวจ', 'options' => ['class' => 'box-header']],
                            ['label' => 'แฟ้ม REHABILITATION', 'icon' => 'fa fa-circle-o text-red', 'url' => ['rehabilitation/index'],],
                            ['label' => 'แฟ้ม ANC', 'icon' => 'fa fa-circle-o text-red', 'url' => ['anc/index'],],
                            ['label' => 'แฟ้ม POSTNATAL', 'icon' => 'fa fa-circle-o text-red', 'url' => ['postnatal/index'],],
                            ['label' => 'แฟ้ม NEWBORNCARE', 'icon' => 'fa fa-circle-o text-red', 'url' => ['newborncare/index'],],
                            ['label' => 'แฟ้ม EPI', 'icon' => 'fa fa-circle-o text-red', 'url' => ['epi/index'],],
                            ['label' => 'แฟ้ม SPECIALPP', 'icon' => 'fa fa-circle-o text-red', 'url' => ['specialpp/index'],],
                        ['label' => 'แฟ้มตามนโยบาย', 'options' => ['class' => 'box-header']],
                            ['label' => 'แฟ้ม POLICY', 'icon' => 'fa fa-circle-o text-red', 'url' => ['policy/index'],],
                        //['label' => 'แฟ้มแก้ไข', 'options' => ['class' => 'box-header']],
                            //['label' => 'แฟ้ม DATA_CORRECT', 'icon' => 'fa fa-circle-o text-red', 'url' => ['index'],],
                            ],
                    ],

                //รายงานออนไลน์
                    ['label' => 'รายงานออนไลน์', 'icon' => 'fa  fa-bar-chart text-blue', 'url' => ['report/index']],
                //ตรวจสอบคุณภาพข้อมูล
                    ['label' => 'ตรวจสอบคุณภาพข้อมูล', 'icon' => 'fa fa-database text-green', 'url' => ['audit/index']],
                //สืบค้นรหัสมาตรฐาน ICD-10
                    ['label' => 'สืบค้นรหัสมาตรฐาน ICD-10', 'icon' => 'fa  fa-search text-red', 'url' => ['icd10/index']],
                //ตรวจสอบรหัสยา TMT
                    ['label' => 'ตรวจสอบรหัสยา TMT','icon' => 'fa fa-toggle-on text-info','url' => ['drugcat/index']],
                //ตรวจข้อมูลประกันสังคม SSOP
                    ['label' => 'ตรวจสอบข้อมูล SSOP','icon' => 'fa fa-bell-o text-secondary','url' => ['ssop/index']],
                //แบบสำรวจความพึงพอใจ
                    //['label' => 'แบบสำรวจความพึงพอใจ','icon' => 'fa  fa-check-square text-info','url' => ['site/survery'],'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role != 99],
                //สืบค้นข้อมูลการตาย
                    ['label' => 'สืบค้นข้อมูลการตาย', 'icon' => 'fa  fa-spinner text-aqua', 'url' => ['deathcup/index'],'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role != 99],
                //จัดการระบบ
                    ['label' => 'จัดการระบบ','icon' => 'fa fa-cogs text-aqua','url' => Yii::$app->urlManagerBackend->createUrl(['site/index']),'visible' => !Yii::$app->user->isGuest && Yii::$app->user->identity->role == 1],

                    ['label' => 'เข้าสู่ระบบ', 'icon' => 'fa fa-sign-in text-aqua','url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],

                ],
            ]
        ) ?>
            <li>
                <div class="small-box">
                   <div class="inner">
                        <h3>
                         <?php $hn_total = Yii::$app->db->createCommand("SELECT COUNT(DISTINCT hn) AS hn_total from tmp_visit_opd WHERE vstdate = DATE(NOW())")->queryScalar(); ?>
                                <font color="#0080FF"><?php echo $hn_total;?></font>  <sup style="font-size: 20px"><font color="#FA58AC">คน</font></sup>
                         <?php $vn_total = Yii::$app->db->createCommand("SELECT COUNT(vn) AS vn_total from tmp_visit_opd WHERE vstdate = DATE(NOW())")->queryScalar(); ?>
                                <font color="#0080FF"><?php echo $vn_total;?></font> <sup style="font-size: 20px"><font color="#FA58AC">ครั้ง</font></sup>
                         </h3>
                       <div class="inner">
                       <p><b><font color="#8904B1">ผู้รับบริการวันนี้</font></b></p>
                            <?php
                            function DateThai($strDate) {
                               $timezone = date_default_timezone_set("Asia/Bangkok");
                               $strYear = date("Y", strtotime($strDate)) + 543;
                               $strMonth = date("n", strtotime($strDate));
                               $strDay = date("j", strtotime($strDate));
                               $strHour = date("H", strtotime($strDate));
                               $strMinute = date("i", strtotime($strDate));
                               $strSeconds = date("s", strtotime($strDate));
                               $strMonthCut = Array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
                               $strMonthThai = $strMonthCut[$strMonth];
                               return "$strDay $strMonthThai $strYear เวลา $strHour:$strMinute:$strSeconds น.";
                            }
                                $strDate = "now";
                                echo "" . DateThai($strDate);
                            ?>
                        </div>
                   </div>
                   <div class="icon"><i class="fa fa-user-md"></i></div>
               </div>
            </li>
    </section>

</aside>
