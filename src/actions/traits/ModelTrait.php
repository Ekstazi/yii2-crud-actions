<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 10:52
 */

namespace ekstazi\crud\actions\traits;

use ekstazi\crud\Constants;
use yii\db\BaseActiveRecord;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;


/**
 * Class ModelTrait used for finding model
 * @package ekstazi\crud
 */
trait ModelTrait
{

    //public $redirectTo;

    /**
     * @var string model class name must be child of BaseActiveRecord
     */
    public $modelClass;

    /**
     * Find model by its pk from params
     * @param array $params Params for query
     * @throws BadRequestHttpException if error on fetching params for pk occured
     * @throws NotFoundHttpException if model not exists
     * @return \yii\db\BaseActiveRecord found model
     */
    public function findModel($params)
    {
        /** @var BaseActiveRecord $class */
        $class = $this->modelClass;
        $pk = $class::primaryKey();

        $columns = [];
        $missing = [];

        foreach ($pk as $key) {
            if (array_key_exists($key, $params)) {
                if (is_array($params[$key])) {
                    throw new BadRequestHttpException(\Yii::t('yii', 'Invalid data received for parameter "{param}".', [
                        'param' => $key,
                    ]));

                } else {
                    $columns[$key] = $params[$key];
                    unset($params[$key]);
                }

            } else
                $missing[] = $key;
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException(\Yii::t('yii', 'Missing required parameters: {params}', [
                'params' => implode(', ', $missing),
            ]));
        }

        if (($model = $class::findOne($columns)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t(Constants::MSG_CATEGORY_NAME, 'The requested page does not exist.'));
        }
    }

}