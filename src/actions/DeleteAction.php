<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:54
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\params\RedirectTo;
use yii\web\ServerErrorHttpException;

/**
 * Delete model action
 * @property string $modelClass class name. Must be child of BaseActiveRecord
 * @package ekstazi\crud\actions
 */
class DeleteAction extends Action
{

    /**
     * @var mixed $redirectTo the route to redirect to. It can be one of the followings:
     *
     * - A PHP callable. The callable will be executed to get route. The signature of the callable
     *   should be:
     *
     * ```php
     * function ($model){
     *     // $model is the model object.
     * }
     * ```
     *
     * The callable should return route/url to redirect to.
     *
     * - An array. Treated as route.
     * - A string. Treated as url.
     */
    public $redirectTo = ['index'];

    /**
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model = $this->loadModel(\Yii::$app->request->get());
        
        $this->ensureAccess(['model' => $model]);

        if ($model->delete() === false) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }
        
        $redirectUrl = RedirectTo::build($this->redirectTo, $model);
        return $this->controller->redirect($redirectUrl);
    }
} 