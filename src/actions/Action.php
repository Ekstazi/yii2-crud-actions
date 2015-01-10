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
     * Redirect to route passed as param
     * @param mixed $route the route to redirect to. It can be one of the followings:
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
     *
     * @param ActiveRecordInterface $model
     * @return mixed
     */
    protected function redirect($route, ActiveRecordInterface $model)
    {
        // if callable
        if ($route instanceof \Closure)
            $route = call_user_func($route, $model);

        return $this->controller->redirect($route);
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

    /**
     * @param callable $saveMethod a PHP callable that will be called to save model. If not set,
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
     *
     * @param ActiveRecordInterface $model
     * @return bool|mixed
     */
    protected function saveModel($saveMethod, ActiveRecordInterface $model)
    {
        if ($saveMethod !== null)
            return call_user_func($saveMethod, $model);

        return $model->save();
    }

    /**
     * Find model by its pk from params otherwise the not found exception will be thrown
     * @param callable $findModel a PHP callable that will be called to return the model corresponding
     * to the specified primary key value. If not set, [[findModelByPk()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($params) {
     *     // $params is the params from request
     * }
     * ```
     *
     * The callable should return the model found.
     *
     * @param array $params Params for query
     *
     * @throws BadRequestHttpException if error on fetching params for pk occured
     * @throws NotFoundHttpException if model not exists
     *
     * @return ActiveRecordInterface found model
     */
    protected function findModel($findModel, $params)
    {
        $model = ($findModel === null) ?
            $this->findModelByPk($params) :
            $model = call_user_func($findModel, $params);

        if (!$model)
            throw new NotFoundHttpException(\Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'The requested page does not exist.'
            ));

        return $model;
    }

    /**
     * Extract pk from params and find model
     * @param $params
     * @return \yii\db\BaseActiveRecord
     * @throws BadRequestHttpException
     */
    protected function findModelByPk($params)
    {
        $finder = ModelFinder::create($this->modelClass);

        if (!$finder->load($params))
            throw new BadRequestHttpException($finder->getError());

        return $finder->getModel();
    }

}