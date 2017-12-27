<?php
/**
 * Created by PhpStorm.
 * User: dez
 * Date: 26.12.17
 * Time: 13:05
 */

namespace app\models;

use yii\base\Model;


/**
 * This is the model class for table "new".
 *
 * @property string $short
 * @property string $full
 * @property string $theme
 */
class TaskNewModel extends Model {

    public $short = '';
    public $full = '';
    public $theme = '';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['short', 'full', 'theme'], 'string'],
        ];
    }

    public function formName(){
        return 'new';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'short' => 'Short',
            'full' => 'Full',
            'theme' => 'Theme'
        ];
    }
}