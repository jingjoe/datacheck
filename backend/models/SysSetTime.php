<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sys_set_time".
 *
 * @property integer $id
 * @property string $event_time
 * @property integer $days
 */
class SysSetTime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'set_datetime';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date'], 'safe'],
            [['days'], 'required'],
            [['days'], 'integer'],
            [['time'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'วันที่',
            'time' => 'เวลา',
            'days' => 'ทุกๆ(วัน)',
        ];
    }
}
