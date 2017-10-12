<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `news` module
 */
class NewsController extends ActiveController
{
    public $modelClass = 'app\models\NewModel';
}
