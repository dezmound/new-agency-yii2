<?php

namespace app\controllers;

use yii\rest\ActiveController;

/**
 * Default controller for the `news` module
 */
class EventsController extends ActiveController
{
    public $modelClass = 'app\models\EventModel';
}
