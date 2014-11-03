<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 03.11.2014
 * Time: 21:02
 */

namespace ekstazi\crud\controllers;


use ekstazi\crud\actions\CreateAction;
use ekstazi\crud\actions\DeleteAction;
use ekstazi\crud\actions\IndexAction;
use ekstazi\crud\actions\UpdateAction;
use ekstazi\crud\actions\ViewAction;
use yii\web\Controller;

/**
 * Class CrudController
 * controller for crud actions like generated by gii
 * @package ekstazi\crud\controllers
 */
class CrudController extends Controller
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
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => $this->modelClass,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => $this->modelClass,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => $this->modelClass,
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => $this->modelClass
            ]
        ];
    }
} 