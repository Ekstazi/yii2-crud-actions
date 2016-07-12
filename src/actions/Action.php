<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 03.11.2014
 * Time: 22:47
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\Constants;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecordInterface;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Action is the base class for action classes that implement CRUD.
 * @package ekstazi\crud\actions
 */
class Action extends \yii\base\Action
{
    /**
     * @var string class name of the model which will be handled by this action.
     * The model class must implement [[ActiveRecordInterface]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var callable a PHP callable that will be called when running an action to determine
     * if the current user has the permission to execute the action. If not set, the access
     * check will not be performed. The signature of the callable should be as follows,
     *
     * ```php
     * function ($model = null) {
     *     // $model is the requested model instance.
     *     // If null, it means no specific model (e.g. IndexAction)
     * }
     * ```
     *
     * If callable return false then perform standard access control filter behavior
     * (like in [[\yii\filters\AccessControl]]).
     */
    public $checkAccess;

    public $viewParams = [];

    /**
     * @inheritdoc
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!isset($this->modelClass))
            throw new InvalidConfigException('The "modelClass" property must be set.');
    }

    /**
     * Ensure this action is allowed for current user
     * @param array $params Params to be passed to {$this->checkAccess}
     * @throws ForbiddenHttpException
     */
    protected function ensureAccess($params = [])
    {
        if (!isset($this->checkAccess))
            return;

        $params['action'] = $this;

        if (call_user_func($this->checkAccess, $params))
            return;

        $user = \Yii::$app->user;

        if ($user->getIsGuest())
            $user->loginRequired();
        else
            throw new ForbiddenHttpException(\Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'You are not allowed to perform this action.'
            ));

    }

    protected function loadModel($params)
    {
        $finder = ModelFinder::create($this->modelClass);

        if (!$finder->load($params))
            throw new BadRequestHttpException($finder->getError());

        $model = $finder->getModel();
        if (!$model) {
            throw new NotFoundHttpException(\Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'The requested page does not exist.'
            ));
        }
        return $model;
    }

    protected function render($view, $params = [])
    {
        if ($this->viewParams instanceof \Closure) {
            $this->viewParams = call_user_func($this->viewParams, $this);
        }

        return $this->controller->render($view, array_merge($this->viewParams, $params));
    }
}