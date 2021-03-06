<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 12.11.2014
 * Time: 23:36
 */

namespace ekstazi\crud\actions;


use ekstazi\crud\Constants;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\BaseActiveRecord;

/**
 * Class ModelFinder
 * Try to find record by its class name and params.
 *
 * The typical usage of ModelFinder is as follows,
 *
 * ```php
 * $finder=ModelFinder::create($modelClass);
 * if (!$finder->load($params))
 *    throw new BadRequestHttpException($finder->getError());
 *
 * if (!$finder->exists())
 *    throw new NotFoundHttpException('The requested page does not exist.');
 *
 * $model=$finder->model;
 * ```
 *
 * @package ekstazi\crud\actions
 */
class ModelFinder extends Model
{
    /**
     * @var array params from user
     */
    public $params;

    /**
     * @var string modelclass name
     */
    public $modelClass;


    /**
     * @var array searched pk
     */
    protected $_pk;

    /**
     * @var BaseActiveRecord founded model
     */
    protected $_model;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['params', 'validatePassed', 'skipOnEmpty' => false],
        ];
    }

    /**
     * Try to load params and validate
     * @param array $params
     * @return bool whether the validation succeeds
     */
    public function load($params = [], $formName = null)
    {
        $this->params = $params;
        return $this->validate();
    }

    /**
     * Create finder instance
     * @param $modelClass
     * @return static
     */
    public static function create($modelClass)
    {
        /** @var BaseActiveRecord $modelClass */
        $model = new static;
        $model->modelClass = $modelClass;
        return $model;
    }

    /**
     * Validator for params
     * @param $attr
     * @param array $opts
     */
    public function validatePassed($attr, $opts = [])
    {
        $params = $this->$attr;

        $missing = $invalid = $columns = [];

        /** @var BaseActiveRecord $class */
        $class = $this->modelClass;
        $pk = $class::primaryKey();
        $keys = count($pk) > 1 ? $pk : ['id'];


        foreach ($keys as $key) {

            // if no param
            if (!array_key_exists($key, $params)) {
                $missing[] = $key;

                // or param is array
            } elseif (is_array($params[$key])) {
                $invalid[] = $key;

                // all good
            } else {
                $columns[$key] = $params[$key];
            }
        }

        if (count($missing)) {
            $this->addError($attr, \Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'Missing required parameters: {params}',
                ['params' => implode(', ', $missing)]
            ));
            return;
        }

        if (count($invalid)) {
            $this->addError($attr, \Yii::t(
                Constants::MSG_CATEGORY_NAME,
                'Invalid data received for parameters "{params}".',
                ['params' => implode(', ', $invalid)]
            ));
            return;
        }

        $this->_pk = $columns;
    }

    /**
     * Get error message
     * @return string
     */
    public function getError()
    {
        return $this->getFirstError('params');
    }

    /**
     * Get founded model
     * @return BaseActiveRecord
     */
    public function getModel()
    {
        if (!isset($this->_pk))
            throw new InvalidCallException('Invalid usage. You must call this method only if load method succeeds');

        if (isset($this->_model))
            return $this->_model;

        $class = $this->modelClass;
        return $this->_model = $class::findOne($this->_pk);
    }

}