<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:37
 */

namespace ekstazi\crud\actions;

use ekstazi\crud\actions\traits\ModelTrait;
use ekstazi\crud\actions\traits\RedirectTrait;
use ekstazi\crud\actions\traits\RenderTrait;
use ekstazi\crud\Constants;
use yii\base\Action;


/**
 * Class UpdateAction - update model action
 * @package ekstazi\crud\actions
 */
class UpdateAction extends Action
{
    use ModelTrait,
        RedirectTrait,
        RenderTrait;

    /**
     * @var string model class name must be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var array url to redirect after update
     * Additional token @_pk_ in url will be replaced with model pk
     */
    public $redirectTo = ['view',Constants::PK_TOKEN];

    /**
     * @var string View file name
     */
    public $viewName = 'update';

    /**
     * @return \yii\web\Response
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model = $this->findModel(\Yii::$app->request->get());

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->redirectTo,$model);
        } else {
            return $this->render($this->viewName, [
                'model' => $model,
            ]);
        }
    }

}