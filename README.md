yii2-crud-actions
=================

Set of classes for yii2 for rapid prototyping CRUD actions.
Example of using:

```php
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => <modelClass>,
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => <modelClass>,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => <modelClass>,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => <modelClass>,
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => <modelClass>
            ]
        ];
    }
```

For details, see the description of the api in [doc](http://ekstazi.github.io/yii2-crud-actions/doc/) folder

yii2-crud-actions
====================

Набор классов для yii2 для быстрого прототипирования CRUD-действий.
Пример использования:

```php
    public function actions()
    {
        return [
            'index' => [
                'class' => IndexAction::className(),
                'modelClass' => <modelClass>,
            ],
            'create' => [
                'class' => CreateAction::className(),
                'modelClass' => <modelClass>,
            ],
            'update' => [
                'class' => UpdateAction::className(),
                'modelClass' => <modelClass>,
            ],
            'delete' => [
                'class' => DeleteAction::className(),
                'modelClass' => <modelClass>,
            ],
            'view' => [
                'class' => ViewAction::className(),
                'modelClass' => <modelClass>
            ]
        ];
    }
```

Для подробностей смотрите описание api в папке [doc](http://ekstazi.github.io/yii2-crud-actions/doc/)