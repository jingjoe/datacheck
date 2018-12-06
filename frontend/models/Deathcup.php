<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "l_death_cup".
 *
 * @property integer $id
 * @property string $hospcode
 * @property string $hosname
 * @property string $cid
 * @property string $fname
 * @property string $lname
 * @property string $sex
 * @property string $age
 * @property string $nation
 * @property string $death_y
 * @property string $death_m
 * @property string $death_d
 * @property string $cause_death
 * @property string $death_place
 * @property string $address_death
 * @property string $inform_y
 * @property string $inform_m
 * @property string $inform_d
 * @property string $current_address
 * @property string $report_prov
 * @property string $report_y
 * @property string $report_m
 */
class Deathcup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'l_death_cup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['hospcode', 'hosname', 'cid', 'fname', 'lname', 'sex', 'age', 'nation', 'death_y', 'death_m', 'death_d', 'cause_death', 'death_place', 'address_death', 'inform_y', 'inform_m', 'inform_d', 'current_address', 'report_prov', 'report_y', 'report_m'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hospcode' => 'HOSCODE',
            'hosname' => 'HOSNAME',
            'cid' => 'CID',
            'fname' => 'ชื่อ',
            'lname' => 'นามสกุล',
            'sex' => 'เพศ',
            'age' => 'อายุ/ปี',
            'nation' => 'เชื่อชาติ',
            'death_y' => 'ปีที่ตาย',
            'death_m' => 'เดือนที่ตาย',
            'death_d' => 'วันที่ตาย',
            'cause_death' => 'สาเหตุการตาย',
            'death_place' => 'สถานที่ตาย',
            'address_death' => 'ที่ตาย',
            'inform_y' => 'ปีที่แจ้ง',
            'inform_m' => 'เดือนที่แจ้ง',
            'inform_d' => 'วันที่แจ้ง',
            'current_address' => 'ที่อยู่ปัจจุบัน',
            'report_prov' => 'รหัสอำเถอ',
            'report_y' => 'ปี่ที่รายงาน',
            'report_m' => 'เดือนที่รายงาน',
        ];
    }
}
