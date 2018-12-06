<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;

// Add Models Drugcatalogue
use backend\models\Drugcatalogue;


/**
 * This is the model class for table "fileimport".
 *
 * @property integer $id
 * @property string $file
 * @property string $token_upload
 * @property string $create_date
 * @property string $modify_date
 */
class Fileimport extends \yii\db\ActiveRecord
{

    public $uploadPath = 'drugcat';
    const DOC_PATH = 'drugcat';
    public static function tableName()
    {
        return 'fileimport';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false],
            [['create_date', 'import_date'], 'safe'],
            [['token_upload'], 'string', 'max' => 100],
        ];
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_date',
                'updatedAtAttribute' => 'import_date',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'file' => 'Approved Drugcatalogue File',
            'token_upload' => 'Token Upload',
            'create_date' => 'Import Date',
            'import_date' => 'วันเวลานำเข้า'
        ];
    }
// Function upload files.

    public static function getDocPath() {
        return Yii::getAlias('@webroot') . '/' . self::DOC_PATH;
    }

    public static function getDocUrl() {
        return Url::base(true) . '/' . self::DOC_PATH;
    }
    
 //function get drugcatalogue ฟิว statue by PK id   
      public function getStatusfile() {
        return @$this->hasOne(Drugcatalogue::className(), ['file_id' => 'id']);
    }

    public function getStatusname() {
        return @$this->statusfile->status;
    }
}
