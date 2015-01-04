<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:59
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\Constants;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\Response;

/**
 * List models action
 * @package ekstazi\crud\actions
 */
class IndexAction extends Action
{

    /**
     * @var callable a PHP callable that will be called to prepare a data provider that
     * should return a collection of the models. If not set, [[prepareDataProvider()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($model) {
     *     // $model is the model object
     * }
     * ```
     *
     * The callable should return an instance of [[ActiveDataProvider]], or throw an exception
     * if not valid params passed.
     */
    public $prepareDataProvider;

    /**
     * @var string View file name
     */
    public $viewName = 'index';

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        $this->ensureAccess();

        /* @var $searchModel \yii\db\BaseActiveRecord */
        $searchModel = new $this->modelClass;
        $dataProvider = $this->prepareDataProvider($searchModel);

        return $this->controller->render($this->viewName, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @param $searchModel BaseActiveRecord
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider($searchModel)
    {
        if ($this->prepareDataProvider !== null) {
            return call_user_func($this->prepareDataProvider, $searchModel);
        }

        return new ActiveDataProvider([
            'query' => $searchModel::find(),
        ]);
    }
}