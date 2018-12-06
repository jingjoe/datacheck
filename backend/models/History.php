<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "history".
 *
 * @property integer $id
 * @property string $datetime
 * @property string $change
 * @property string $detail
 */
class History extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime'], 'safe'],
            [['change', 'detail'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime' => 'วันที่บันทึก',
            'change' => 'รายการที่เปลี่ยน',
            'detail' => 'รายละเอียด',
        ];
    }
}
