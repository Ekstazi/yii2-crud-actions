<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 17:11
 */

namespace ekstazi\crud\actions\traits;


trait RenderTrait
{
    public $viewParams = [];

    public function render($view, $params = [])
    {
        return $this->controller->render($view, array_merge($this->viewParams, $params));
    }
} 