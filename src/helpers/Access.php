<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 03.01.2015
 * Time: 11:52
 */

namespace ekstazi\crud\helpers;

/**
 * Helper for [[Action::$checkAccess]] option
 * Class Access
 * @package ekstazi\crud\helpers
 */
class Access
{
    /**
     * Return a PHP callable that check user permission by role
     * @param $roleName
     * @return callable
     */
    public static function checkRole($roleName)
    {
        return function ($params) use ($roleName) {
            \Yii::$app->user->can($roleName, $params);
        };
    }

}