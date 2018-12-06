<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\db\Query;
use yii\data\ArrayDataProvider;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        
        $a_year = Yii::$app->db->createCommand('SELECT YEAR(date)+543 AS d FROM set_datetime LIMIT 1')->queryScalar();
        $b_year=$a_year+1;
      

        $opd_p = "SELECT COUNT(DISTINCT hn) AS cc_hn FROM tmp_visit_opd ";
        $opd_v = "SELECT COUNT(DISTINCT vn) AS cc_vn FROM tmp_visit_opd ";
        $ipd_a = "SELECT COUNT(DISTINCT hn) AS cc_a FROM tmp_admit_ipd ";
        $ipd_d = "SELECT SUM(admdate) AS date_t FROM tmp_admit_ipd ";

        $m_opd = "SELECT 'ต.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='10'
                    UNION
                    SELECT 'พ.ย.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='11'
                    UNION
                    SELECT 'ธ.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='12'
                    UNION
                    SELECT 'ม.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='1'
                    UNION
                    SELECT 'ก.พ.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='2'
                    UNION
                    SELECT 'มี.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='3'
                    UNION
                    SELECT 'เม.ย.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='4'
                    UNION
                    SELECT 'พ.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='5'
                    UNION
                    SELECT 'มิ.ย.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='6'
                    UNION
                    SELECT 'ก.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='7'
                    UNION
                    SELECT 'ส.ค.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='8'
                    UNION
                    SELECT 'ก.ย.' AS m,COUNT(DISTINCT hn) AS cc,COUNT(DISTINCT vn) AS vv
                    FROM tmp_visit_opd
                    WHERE m='9' ";

        $m_ipd = "SELECT 'ต.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='10'
                    UNION
                    SELECT 'พ.ย.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='11'
                    UNION
                    SELECT 'ธ.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='12'
                    UNION
                    SELECT 'ม.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='1'
                    UNION
                    SELECT 'ก.พ.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='2'
                    UNION
                    SELECT 'มี.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='3'
                    UNION
                    SELECT 'เม.ย.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='4'
                    UNION
                    SELECT 'พ.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='5'
                    UNION
                    SELECT 'มิ.ย.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='6'
                    UNION
                    SELECT 'ก.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='7'
                    UNION
                    SELECT 'ส.ค.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='8'
                    UNION
                    SELECT 'ก.ย.' AS m,COUNT(DISTINCT hn) AS cc,IF(SUM(admdate)<>'',SUM(admdate),'0') AS dd
                    FROM tmp_admit_ipd
                    WHERE m='9' ";

        $opd_p = Yii::$app->db->createCommand($opd_p)->queryScalar();
        $opd_v = Yii::$app->db->createCommand($opd_v)->queryScalar();
        $ipd_a = Yii::$app->db->createCommand($ipd_a)->queryScalar();
        $ipd_d = Yii::$app->db->createCommand($ipd_d)->queryScalar();
        $m_opd = Yii::$app->db->createCommand($m_opd)->queryAll();
        $m_ipd = Yii::$app->db->createCommand($m_ipd)->queryAll();

        return $this->render('index', [
            'opd_p' => $opd_p,
            'opd_v' => $opd_v,
            'ipd_a' => $ipd_a,
            'ipd_d' => $ipd_d,
            'm_opd' => $m_opd,
            'm_ipd' => $m_ipd,
            'b_year' => $b_year]);
//        return $this->render('index');
    }
    public function actionSurvery(){
        return $this->render('survery');
    }
    public function actionLogin() {
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['site/index']);
            //return $this->render('index');
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            //return $this->render('index');
            $username = $model->username;
            $ip = \Yii::$app->getRequest()->getUserIP();

            $sql = " INSERT INTO `user_log` (`username`,`login_date`, `ip`) VALUES ('$username',NOW(), '$ip') ";
            \Yii::$app->db->createCommand($sql)->execute();

            return $this->redirect(['site/index']);
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
