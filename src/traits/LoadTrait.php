<?php

namespace ekstazi\crud\traits;

use yii\base\ModelEvent;
use yii\db\ActiveRecord;

/**
 * Class LoadTrait
 * @var $this ActiveRecord|LoadTrait
 * @package ekstazi\crud
 */
trait LoadTrait
{
    /**
     * @var bool
     */
    private $wasLoaded = false;

    /**
     * CONSTANT
     * @return string
     */
    public static function EVENT_BEFORE_LOAD()
    {
        return 'beforeLoad';
    }

    /**
     * CONSTANT
     * @return string
     */
    public static function EVENT_AFTER_LOAD()
    {
        return 'afterLoad';
    }

    /**
     * This method is invoked before load data starts.
     * The default implementation raises a `beforeLoad` event.
     * You may override this method to do preliminary checks before load data.
     * Make sure the parent implementation is invoked so that the event can be raised.
     * @return boolean whether the loading data should be executed. Defaults to true.
     * If false is returned, the data will not loaded.
     */
    public function beforeLoad()
    {
        $event = new ModelEvent();
        $this->trigger(self::EVENT_BEFORE_LOAD(), $event);
        return $event->isValid;
    }

    /**
     * This method is invoked after loading data.
     * The default implementation raises an `afterLoad` event.
     * You may override this method to do postprocessing after loading data.
     * Make sure the parent implementation is invoked so that the event can be raised.
     */
    public function afterLoad()
    {
        $this->trigger(self::EVENT_AFTER_LOAD());
    }

    /**
     * @param $data
     * @param null $formName
     * @return bool
     */
    public function load($data, $formName = null)
    {
        if (!$this->beforeLoad()) {
            return false;
        }

        $this->wasLoaded = parent::load($data, $formName);
        $this->afterLoad();
        return $this->wasLoaded;
    }

    /**
     * @return boolean
     */
    public function isWasLoaded()
    {
        return $this->wasLoaded;
    }

    /**
     * @param boolean $wasLoaded
     */
    public function setWasLoaded($wasLoaded)
    {
        $this->wasLoaded = $wasLoaded;
    }

}