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
use yii\base\InvalidParamException;
use yii\db\BaseActiveRecord;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class Action extends \yii\base\Action
{
    /**
     * @var string class name of the model which will be handled by this action.
     * The model class must implement [[ActiveRecordInterface]].
     * This property must be set.
     */
    public $modelClass;

    /**
     * @var mixed A rule to check access. It can be one of the followings:
     *
     * - If not set, the access check will not be performed.
     * - A PHP callable. The callable will be executed to get access status. The signature of callable
     *   should be `function ($params)`, where `$params` is array of form
     *   `[action=><action object currently running>, model=><model object, can be null(e.g IndexAction)>]`.
     * - If string then used as role name and check access by role
     */
    public $checkAccess;

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
     * @param mixed $route the route to redirect to. It can be one of the followings:
     *
     * - A PHP callable. The callable will be executed to get route. The signature of the callable
     *   should be `function ($model)`, where `$model` is model object.
     * - An array. Treated as route. If last value of route array is @_pk_ then replaced to appropriate pk of model.
     * - A string. Treated as redirect url
     *
     * @param BaseActiveRecord $model
     * @return mixed
     */
    protected function redirect($route, BaseActiveRecord $model)
    {
        // if callable
        if ($route instanceof \Closure) {
            $route = call_user_func($route, $model);

            // if pk token found in route
        } elseif (is_array($route) && end($route) == Constants::PK_TOKEN) {
            array_pop($route);

            $pk = $model->getPrimaryKey();
            if (is_array($pk))
                $route = $route + $pk;
            else
                $route['id'] = $pk;
        }

        return $this->controller->redirect($route);
    }

    /**
     * Find model by its pk from params
     * @param array $params Params for query
     *
     * @throws BadRequestHttpException if error on fetching params for pk occured
     * @throws NotFoundHttpException if model not exists
     *
     * @return BaseActiveRecord found model
     */
    public function findModel($params)
    {
        $finder = ModelFinder::create($this->modelClass);

        if (!$finder->load($params))
            throw new BadRequestHttpException($finder->getError());

        if (!$finder->exists())
            throw new NotFoundHttpException(\Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'The requested page does not exist.'
            ));

        return $finder->model;
    }

    /**
     * Ensure this action allowed for current user
     * @param array $params Params to be passed to {$this->checkAccess}
     * @throws ForbiddenHttpException
     */
    protected function ensureAccess($params = [])
    {
        if (!isset($this->checkAccess))
            return;

        $params['action'] = $this;

        $user = \Yii::$app->user;

        $allowed = ($this->checkAccess instanceof \Closure) ?
            call_user_func($this->checkAccess, $params) :
            $user->can($this->checkAccess, $params);

        if ($allowed)
            return;

        if ($user->getIsGuest())
            $user->loginRequired();
        else
            throw new ForbiddenHttpException(\Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'You are not allowed to perform this action.'
            ));
    }

    /**
     * @param $saveMethod mixed method to save model. Can be one of the followings:
     *
     * - A null. Model will be saved with {{BaseActiveRecord::save}} method
     * - A PHP callable. The callable will be executed for saving model. The signature of callable should be
     *   `function($model)` where `$model` is model object to save. Should return of saving operation
     * - A string containing method name in model. This method will be called for save.
     *   Should return of saving operation
     *
     * @param BaseActiveRecord $model
     * @return bool|mixed
     */
    protected function saveModel($saveMethod, BaseActiveRecord $model)
    {
        if (!isset($saveMethod))
            return $model->save();

        if ($saveMethod instanceof \Closure)
            return call_user_func($saveMethod, $model);

        if (is_string($saveMethod) && $model->hasMethod($saveMethod))
            return $model->$saveMethod();

        throw new InvalidParamException('Unsupported type of {{$saveMethod}} parameter');
    }
}