<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "event".
 *
 * @property integer $id
 * @property string $name
 * @property integer $date_create
 * @property integer $date_end
 * @property integer $status
 */
class EventModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_events');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date_create', 'date_end', 'status'], 'integer'],
            [['name'], 'string', 'max' => 128],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'date_create' => 'Date Create',
            'date_end' => 'Date End',
            'status' => 'Status',
        ];
    }
}
