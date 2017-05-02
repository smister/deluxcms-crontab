<?php

namespace deluxcms\crontab\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%crontabs}}".
 *
 * @property integer $id
 * @property string $description
 * @property integer $type
 * @property string $min
 * @property string $hour
 * @property string $day
 * @property string $month
 * @property string $week
 * @property string $command
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Crontabs extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%crontabs}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'status'], 'integer'],
            [['command'], 'required'],
            [['description'], 'string', 'max' => 100],
            [['min', 'hour', 'day', 'month', 'week'], 'string', 'max' => 32],
            [['command'], 'string', 'max' => 254],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => '描述',
            'type' => '类型',
            'min' => '分钟',
            'hour' => '小时',
            'day' => '天',
            'month' => '月',
            'week' => '周',
            'command' => '命令',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
