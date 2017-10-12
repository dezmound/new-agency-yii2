<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reporter".
 *
 * @property integer $id
 * @property string $full_name
 * @property string $short_name
 * @property integer $task_id
 * @property integer $birthday
 */
class ReporterModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reporter';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_reporter');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'birthday'], 'integer'],
            [['full_name'], 'string', 'max' => 128],
            [['short_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full_name' => 'Full Name',
            'short_name' => 'Short Name',
            'task_id' => 'Task ID',
            'birthday' => 'Birthday',
        ];
    }
}
