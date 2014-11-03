<?php
/**
 * Created by PhpStorm.
 * User: Ekstazi
 * Date: 02.11.2014
 * Time: 23:48
 */
Yii::$app->on(\yii\base\Application::EVENT_AFTER_ACTION,function(){
    unlink(__DIR__."/ru/yii.php");
    chdir(__DIR__);
    foreach (glob(__DIR__."/ru/ekstazi/*.php") as $file)
    {
        rename($file,__DIR__.'/ru/'.basename($file));
    }
    echo "All jobs done\n";
});
return [
    'sourcePath' => dirname(__DIR__),
    'messagePath' => __DIR__,
    'languages' => ['ru'],
    'translator' => '\Yii::t',
    'sort' => false,
    'removeUnused' => true,
    'overwrite' => true,
    'only' => ['*.php'],
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
    ],
    'format' => 'php'
];