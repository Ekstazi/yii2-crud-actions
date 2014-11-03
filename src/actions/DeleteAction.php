<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:54
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\actions\traits\ModelTrait;
use ekstazi\crud\actions\traits\RedirectTrait;
use yii\base\Action;
use yii\web\Response;

/**
 * Class DeleteAction delete model actions
 * @package ekstazi\crud\actions
 */
class DeleteAction extends Action
{
    use ModelTrait,
        RedirectTrait;

    /**
     * @var string model class name must be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var array Url to redirect after delete
     * Additional token @_pk_ in url will be replaced with model pk
     */
    public $redirectTo=['index'];

    /**
     * @return Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model=$this->findModel(\Yii::$app->request->get());
        $model->delete();
        return $this->redirect($this->redirectTo,$model);
    }
} 