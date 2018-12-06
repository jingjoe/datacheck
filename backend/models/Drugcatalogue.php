<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "drugcatalogue".
 *
 * @property integer $id
 * @property string $hospdrugcode
 * @property string $productcat
 * @property string $tmtid
 * @property string $specprep
 * @property string $genericname
 * @property string $trandename
 * @property string $dfscode
 * @property string $dosageform
 * @property string $strength
 * @property string $content
 * @property string $unitprice
 * @property string $distributor
 * @property string $manufacturer
 * @property string $ised
 * @property string $ndc24
 * @property string $packsize
 * @property string $packprice
 * @property string $updateflag
 * @property string $datechange
 * @property string $dateupdate
 * @property string $dateeffective
 * @property string $ised_approved
 * @property string $ndc24_approved
 * @property string $date_approved
 * @property string $ised_status
 * @property string $date_import
 */
class Drugcatalogue extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'drugcatalogue';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_import'], 'safe'],
            [['hospdrugcode'], 'string', 'max' => 9],
            [['productcat', 'ised', 'updateflag'], 'string', 'max' => 1],
            [['tmtid'], 'string', 'max' => 6],
            [['specprep', 'ised_status', 'ised_approved'], 'string', 'max' => 2],
            [['genericname', 'trandename'], 'string', 'max' => 200],
            [['dfscode', 'distributor'], 'string', 'max' => 255],
            [['strength', 'dosageform', 'manufacturer','file_name'], 'string', 'max' => 100],
            [['content', 'packsize'], 'string', 'max' => 50],
            [['ndc24', 'ndc24_approved'], 'string', 'max' => 24],
            [['packprice'], 'string', 'max' => 20],
            [['unitprice','datechange', 'dateupdate', 'dateeffective', 'date_approved','file_id','status'], 'string', 'max' => 10],
        ];
    }
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date_import',
                'updatedAtAttribute' => 'date_import',
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hospdrugcode' => 'รหัสยาหน่วยบริการ',
            'productcat' => 'ประเภทยาและเวชภัณฑ์',
            'tmtid' => 'รหัส TMT',
            'specprep' => 'ประเภทการจัดเตรียม',
            'genericname' => 'ชื่อสามัญ',
            'trandename' => 'ชื่อยาทางการค้า',
            'dfscode' => 'รหัสรูปแบบยา',
            'dosageform' => 'รูปแบบยา',
            'strength' => 'ขนาดความแรงยา',
            'content' => 'ขนาดบรรจุ',
            'unitprice' => 'ราคาขายต่อหน่วย',
            'distributor' => 'บริษัทผู้จัดจำหน่าย',
            'manufacturer' => 'บริษัทผู้ผลิต',
            'ised' => 'ยาใน/นอกบัญชี',
            'ndc24' => 'รหัสยา 24 หลัก',
            'packsize' => 'ขนาดบรรจุ',
            'packprice' => 'ราคาขายต่อแพ็ค',
            'updateflag' => 'สถานะการปรับปรุง',
            'datechange' => 'วันที่ปรับปรุง',
            'dateupdate' => 'วันที่อับเดท',
            'dateeffective' => 'วันที่การปรับปรุงมีผล',
            'ised_approved' => 'สถานะการตรวจสอบ',
            'ndc24_approved' => 'ตรวจสอบรหัส 24 หลัก',
            'date_approved' => 'วันตรวจสอบ',
            'ised_status' => 'สถานะบัญชียา',
            'date_import' => 'วันนำเข้าข้อมูล',
            'file_id'=> 'ไฟล์ไอดี',
            'file_name' => 'ชื่อไฟล์',
            'status' => 'สถานะ',
        ];
    }
}
