<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 22:08
 */

namespace ekstazi\crud;


use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * Register translator for messages
 * @package ekstazi\crud
 */
class Bootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $i18n = \Yii::$app->i18n;
        if (
            !isset($i18n->translations[Constants::MSG_CATEGORY_NAME]) &&
            !isset($i18n->translations['ekstazi.*'])
        ) {
            $i18n->translations[Constants::MSG_CATEGORY_NAME] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@ekstazi/crud/messages',
                'fileMap' => [
                    'ekstazi/crud' => 'crud.php'
                ]
            ];
        }
    }

} 