<?php

namespace ekstazi\crud\params;


use yii\db\BaseActiveRecord;

class RedirectTo
{

    public static function byPk($route)
    {
        return function (BaseActiveRecord $model) use ($route) {
            $pk = $model->getPrimaryKey();
            if (is_array($pk))
                $route = $route + $pk;
            else
                $route['id'] = $pk;

            return $route;
        };
    }

    public static function callback($function)
    {
        return $function;
    }

    public static function route($route)
    {
        return $route;
    }

    public static function build($redirectTo, $model)
    {
        return (is_callable($redirectTo))
            ? call_user_func($redirectTo, $model)
            : $redirectTo;
    }
}