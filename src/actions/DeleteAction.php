<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:54
 */

namespace ekstazi\crud\actions;


use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteAction
 * Delete model actions
 * @property string $modelClass class name. Must be child of BaseActiveRecord
 * @package ekstazi\crud\actions
 */
class DeleteAction extends Action
{

    /**
     * @var mixed $redirectTo the route to redirect to. It can be one of the followings:
     *
     * - A PHP callable. The callable will be executed to get route. The signature of the callable
     *   should be `function ($model)`, where `$model` is model object.
     *
     * - An array. Treated as route. If last value of route array is @_pk_ then replaced to appropriate pk of model.
     * - A string. Treated as redirect url
     */
    public $redirectTo = ['index'];

    /**
     * @return Response
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model = $this->findModel(\Yii::$app->request->get());
        $this->ensureAccess(compact('model'));

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        $this->redirect($this->redirectTo, $model);
    }
} 