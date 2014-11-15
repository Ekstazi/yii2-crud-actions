<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:56
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\Constants;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use yii\web\Response;

/**
 * Class CreateAction
 * Create model action
 * @property string $modelClass class name. Must be child of BaseActiveRecord
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
     *   should be `function ($model)`, where `$model` is model object.
     *
     * - An array. Treated as route. If last value of route array is @_pk_ then replaced to appropriate pk of model.
     * - A string. Treated as redirect url
     */
    public $redirectTo = ['view', Constants::PK_TOKEN];

    /**
     * @var mixed Ф method to save model. Can be one of the followings:
     *
     * - If not set model will be saved with {{BaseActiveRecord::save}} method
     * - A PHP callable. The callable will be executed for saving model. The signature of callable should be
     *   `function($model)` where `$model` is model object to save. Should return of saving operation
     * - A string containing method name in model. This method will be called for save.
     *   Should return of saving operation
     *
     */
    public $saveMethod;

    /**
     * @return Response
     */
    public function run()
    {
        $this->ensureAccess();
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass([
            'scenario' => $this->scenario
        ]);


        if ($model->load(\Yii::$app->request->post()) && $this->saveModel($this->saveMethod, $model)) {
            return $this->redirect($this->redirectTo, $model);
        } else {
            return $this->controller->render($this->viewName, compact('model'));
        }
    }
}