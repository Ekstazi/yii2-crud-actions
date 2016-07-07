<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:37
 */

namespace ekstazi\crud\actions;

use ekstazi\crud\Constants;
use ekstazi\crud\params\Finder;
use ekstazi\crud\params\RedirectTo;
use yii\base\Model;
use yii\web\NotFoundHttpException;


/**
 * Update model action
 * @package ekstazi\crud\actions
 */
class UpdateAction extends Action
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
    public $redirectTo;

    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string View file name
     */
    public $viewName = 'update';

    

    /**
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\NotFoundHttpException
     */
    public function run()
    {
        $model = $this->loadModel(\Yii::$app->request->get());

        $model->scenario = $this->scenario;

        $this->ensureAccess(['model' => $model]);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $redirectUrl = RedirectTo::build($this->redirectTo, $model);
            return $this->controller->redirect($redirectUrl);
        }

        return $this->controller->render($this->viewName, [
            'model' => $model
        ]);
    }

}