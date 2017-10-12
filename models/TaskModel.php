<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $begin
 * @property integer $end
 * @property string $description
 */
class TaskModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_tasks');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin', 'end'], 'integer'],
            [['description'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'begin' => 'Begin',
            'end' => 'End',
            'description' => 'Description',
        ];
    }
}
