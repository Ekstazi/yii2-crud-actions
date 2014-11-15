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
 * Class IndexAction
 * List models action
 * @package ekstazi\crud\actions
 */
class IndexAction extends Action
{

    /**
     * @var mixed expression for prepare data provider. It can be one of the followings:
     *
     * - a PHP callable that will be called to prepare a data provider that should return a collection of the models.
     *   The signature of the callable should be:
     *
     *   ```php
     *   function ($action, $model) {
     *     // $action is the action object currently running
     *     // $model is the searcher model
     *   }
     *   ```
     *
     *   The callable should return an instance of [[ActiveDataProvider]].
     *
     * - a string name of model method that will be called to get data provider.
     *   Should return an instance of [[ActiveDataProvider]]. Additionally the action object passed as first argument.
     *
     * - if not set, [[prepareDataProvider()]] will be used instead.
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

        return $this->controller->render($this->viewName, compact('searchModel', 'dataProvider'));
    }

    /**
     * Prepares the data provider that should return the requested collection of the models.
     * @return ActiveDataProvider
     */
    protected function prepareDataProvider(BaseActiveRecord $model)
    {
        if (!isset($this->prepareDataProvider))
            return new ActiveDataProvider([
                'query' => $model::find(),
            ]);

        if ($this->prepareDataProvider instanceof \Closure)
            return call_user_func($this->prepareDataProvider, $this, $model);

        if (is_string($this->prepareDataProvider) && $model->hasMethod($this->prepareDataProvider))
            return $model->{$this->prepareDataProvider}($this);

        throw new InvalidConfigException('Not supported configuration fore {{prepareDataProvider}} property');
    }
}