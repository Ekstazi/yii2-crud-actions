yii2-crud-actions
=================

Crud Actions for yii2


yii2-crud-actions
====================

Набор классов для yii2 для быстрого прототипирования CRUD-действий.
Примеры использования по отдельности:

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
