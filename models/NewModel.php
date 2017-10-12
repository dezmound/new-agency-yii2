<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "new".
 *
 * @property integer $id
 * @property integer $date_create
 * @property integer $date_update
 * @property string $short
 * @property string $full
 */
class NewModel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'new';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_news');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_create', 'date_update'], 'integer'],
            [['short', 'full'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'short' => 'Short',
            'full' => 'Full',
        ];
    }
}
