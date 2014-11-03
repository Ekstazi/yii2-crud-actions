<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:56
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\actions\traits\RedirectTrait;
use ekstazi\crud\actions\traits\RenderTrait;
use ekstazi\crud\Constants;
use yii\base\Action;
use yii\db\BaseActiveRecord;
use yii\web\Response;

/**
 * Class CreateAction create model
 * @package ekstazi\crud\actions
 */
class CreateAction extends Action
{
    use RedirectTrait,
        RenderTrait;

    /**
     * @var string Model class name child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string View file name
     */
    public $viewName = 'create';

    /**
     * @var array url to redirect after create
     * Additional token @_pk_ in url will be replaced with model pk
     */
    public $redirectTo = ['view',Constants::PK_TOKEN];

    /**
     * @return Response
     */
    public function run()
    {
        /** @var BaseActiveRecord $model */
        $model = new $this->modelClass;

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($this->redirectTo, $model);
        } else {
            return $this->render($this->viewName, [
                'model' => $model,
            ]);
        }
    }

}