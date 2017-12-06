<?php

namespace app\controllers;

use app\models\User;
use yii\rest\ActiveController;

/**
 * Default controller for the `reporter` module
 */
class UsersController extends ActiveController
{
    public $modelClass = 'app\models\User';
}
