<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "token".
 *
 * @property string $token
 * @property integer $auth_id
 * @property integer $expire_at
 */
class Token extends \yii\db\ActiveRecord
{
    const TIME_EXPIRED = 7200;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_tokens');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_id', 'expire_at'], 'integer'],
            [['token'], 'string', 'max' => 255],
            [['expire_at'], 'default', 'value' => time() + Token::TIME_EXPIRED],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'auth_id' => 'Auth ID',
            'expire_at' => 'Expire At',
        ];
    }
}
