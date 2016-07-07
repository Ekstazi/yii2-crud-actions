<?php

namespace ekstazi\crud;


use ekstazi\crud\helpers\Model;
use ekstazi\crud\params\Finder;
use ekstazi\crud\params\RedirectTo;
use ekstazi\crud\params\Saver;
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
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath'       => '@ekstazi/crud/messages',
                'fileMap'        => [
                    'ekstazi/crud' => 'crud.php'
                ]
            ];
        }
        $di = \Yii::$container;
        $di->set('ekstazi\crud\actions\CreateAction', [
            'redirectTo' => RedirectTo::byPk(['view']),
        ]);
        $di->set('ekstazi\crud\actions\UpdateAction', [
            'redirectTo' => RedirectTo::byPk(['view']),
        ]);
    }

} 