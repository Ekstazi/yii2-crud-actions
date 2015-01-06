<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 14:01
 */

namespace ekstazi\crud\actions;


/**
 * View model action
 * @package ekstazi\crud\actions
 */
class ViewAction extends Action
{

    /**
     * @var string View file name
     */
    public $viewName = 'view';

    /**
     * @var callable a PHP callable that will be called to return the model corresponding
     * to the specified primary key value. If not set, [[findModelByPk()]] will be used instead.
     * The signature of the callable should be:
     *
     * ```php
     * function ($params) {
     *     // $params is the params from request
     * }
     * ```
     *
     * The callable should return the model found. Otherwise the not found exception will be thrown.
     */
    public $findModel;

    /**
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model = $this->findModel($this->findModel, \Yii::$app->request->get());
        $this->ensureAccess(['model' => $model]);

        return $this->controller->render($this->viewName, [
            'model' => $model
        ]);
    }
} 