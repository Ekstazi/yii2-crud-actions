<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 14:01
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\actions\traits\ModelTrait;
use ekstazi\crud\actions\traits\RenderTrait;
use yii\rest\Action;
use yii\web\Response;

/**
 * Class ViewAction view model action
 * @package ekstazi\crud\actions
 */
class ViewAction extends Action
{
    use ModelTrait,
        RenderTrait;

    /**
     * @var string model class name must be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string View file name
     */
    public $view = 'view';

    /**
     * @return Response
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        return $this->render($this->view, [
            'model' => $this->findModel(\Yii::$app->request->get()),
        ]);
    }
} 