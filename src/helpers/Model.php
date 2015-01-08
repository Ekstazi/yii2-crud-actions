<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 03.01.2015
 * Time: 17:45
 */

namespace ekstazi\crud\helpers;


use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Helper for model's options in [[\ekstazi\crud\actions\Action]]
 * @package ekstazi\crud\helpers
 */
class Model
{
    /**
     * Return a PHP callable that map model method
     * @param $methodName
     * @return callable
     */
    public static function method($methodName)
    {
        return function (\yii\base\Model $model) use ($methodName) {
            if (!$model->hasMethod($methodName))
                throw new InvalidConfigException(
                    "Unknown method: '{$methodName}' for model class '" . get_class($model) . "'"
                );

            // call method with additional passed params
            return call_user_func_array([$model,$methodName], array_slice(func_get_args(), 1));
        };
    }

    /**
     * Return a PHP callable that map model id to route
     * @param $route mixed
     * @return callable
     */
    public static function redirectUrl($route)
    {
        return function (ActiveRecord $model) use ($route) {
            $pk = $model->getPrimaryKey();
            if (is_array($pk))
                $route = $route + $pk;
            else
                $route['id'] = $pk;

            return $route;
        };
    }
} 