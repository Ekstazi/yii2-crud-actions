<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:56
 */

namespace ekstazi\crud\actions;


use yii\base\Model;
use yii\db\BaseActiveRecord;

/**
 * Create model action
 * @package ekstazi\crud\actions
 */
class CreateAction extends Action
{

    /**
     * @var string View file name
     */
    public $viewName = 'create';

    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var mixed $redirectTo the route to redirect to. It can be one of the followings:
     *
     * - A PHP callable. The callable will be executed to get route. The signature of the callable
     *   should be:
     *
     * ```php
     * function ($model){
     *     // $model is the model object.
     * }
     * ```
     *
     * The callable should return route/url to redirect to.
     *
     * - An array. Treated as route.
     * - A string. Treated as url.
     */
    public $redirectTo;

    /**
     * @var callable a PHP callable that will be called to save model. If not set,
     * {{BaseActiveRecord::save}} will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function($model){
     *     // $model is the model object to save.
     * }
     * ```
     *
     * The callable should return status of saving operation
     */
    public $saveMethod;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->redirectTo === null)
            $this->redirectTo = \ekstazi\crud\helpers\Model::redirectUrl(['view']);
    }

    /**
     * @throws \yii\web\ForbiddenHttpException
     */
    public function run()
    {
        $this->ensureAccess();

        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass(['scenario' => $this->scenario]);

        if ($model->load(\Yii::$app->request->post()) && $this->saveModel($this->saveMethod, $model)) {
            return $this->redirect($this->redirectTo, $model);
        }

        return $this->controller->render($this->viewName, [
            'model' => $model
        ]);
    }
}