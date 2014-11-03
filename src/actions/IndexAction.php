<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 13:59
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\actions\traits\RenderTrait;
use ekstazi\crud\Constants;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\web\Response;

/**
 * Class IndexAction show models
 * @package ekstazi\crud\actions
 */
class IndexAction extends Action
{
    use RenderTrait;

    /**
     * @var string class name. Mast be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * @var string Name of method wich try to use for getting DataProvider.
     * If no method exists in model then use Model::find to create provider
     */
    public $methodName = 'search';

    /**
     * @var string View file name
     */
    public $viewName = 'index';

    /**
     * @return Response
     * @throws InvalidConfigException
     */
    public function run()
    {
        /** @var BaseActiveRecord $searchModel */
        $searchModel = new $this->modelClass;
        $methodName = $this->methodName;

        if (isset($methodName) && method_exists($searchModel, $methodName)) {
            $dataProvider = $searchModel->$methodName(\Yii::$app->request->queryParams);

        } elseif ($searchModel instanceof BaseActiveRecord) {
            $dataProvider = new ActiveDataProvider([
                'query' => $searchModel::find()
            ]);

        } else {
            throw new InvalidConfigException(\Yii::t(Constants::MSG_CATEGORY_NAME,'Only BaseActiveRecord supported as IndexAction::$modelClass property'));
        }

        return $this->render($this->viewName, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
} 