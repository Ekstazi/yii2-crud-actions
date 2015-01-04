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
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Admin controller support all crud actions except viewing
 * @package ekstazi\crud\controllers
 */
class AdminController extends Controller
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
            'index'=>[
                'class'=>IndexAction::className(),
                'modelClass'=>$this->modelClass,
            ],
            'create'=>[
                'class'=>CreateAction::className(),
                'modelClass'=>$this->modelClass,
                'redirectTo'=>['index'],
            ],
            'update'=>[
                'class'=>UpdateAction::className(),
                'modelClass'=>$this->modelClass,
                'redirectTo'=>['index'],
            ],
            'delete'=>[
                'class'=>DeleteAction::className(),
                'modelClass'=>$this->modelClass,
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

}