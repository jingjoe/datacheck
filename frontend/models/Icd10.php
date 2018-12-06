<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "l_icd10".
 *
 * @property integer $id
 * @property string $icd10
 * @property string $descriptions
 * @property string $valid
 * @property string $icd10who
 * @property string $icd10tm
 * @property string $icd10tmd
 */
class Icd10 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_icd10';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['icd10'], 'string', 'max' => 8],
            [['descriptions'], 'string', 'max' => 250],
            [['valid', 'icd10who', 'icd10tm', 'icd10tmd'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'icd10' => 'รหัสโรค',
            'descriptions' => 'รายละเอียด',
            'valid' => 'เหตุผล',
            'icd10who' => 'ICD-10-WHO',
            'icd10tm' => 'ICD-10-TM',
            'icd10tmd' => 'ICD-10-TMD',
        ];
    }

    /**
     * @inheritdoc
     * @return Icd10Query the active query used by this AR class.
     */
    public static function find()
    {
        return new Icd10Query(get_called_class());
    }
}
