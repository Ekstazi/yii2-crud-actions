<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 15:28
 */

namespace ekstazi\crud\actions\traits;


use ekstazi\crud\Constants;
use yii\db\BaseActiveRecord;

/**
 * Class RedirectTrait
 * @package ekstazi\crud\actions
 */
trait RedirectTrait
{
    protected function redirect($route, BaseActiveRecord $model)
    {
        if (($last = array_pop($route)) == Constants::PK_TOKEN) {
            $route = $route + $model->getPrimaryKey(true);
        } else
            return $route = $route + [$last];

        return $this->controller->redirect($route);
    }
} 