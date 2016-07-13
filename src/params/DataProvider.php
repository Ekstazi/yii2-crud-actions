<?php

namespace ekstazi\crud\params;


use yii\db\ActiveRecord;

class DataProvider
{
    public static function callback($callback)
    {
        return $callback;
    }

    public static function bySearchMethod($methodName)
    {
        return function ($model) use ($methodName) {
            return call_user_func([$model, $methodName]);
        };
    }
}