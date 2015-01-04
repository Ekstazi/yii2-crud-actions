<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 03.11.2014
 * Time: 21:02
 */

namespace ekstazi\crud\controllers;

use ekstazi\crud\actions\IndexAction;
use ekstazi\crud\actions\ViewAction;
use yii\web\Controller;

/**
 * Controller for viewing and listing models
 * @package ekstazi\crud\controllers
 */
class FrontController extends Controller
{
    /**
     * @var string model class name must be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => $this->modelClass,
            ]
        ];
    }
} 