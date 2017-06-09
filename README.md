Yii2-filter
==========

Модуль позволит добавлять опции для любой вашей модели в админке, а снаружи фильтровать результаты выдачи по выбранным опциям (в том числе ajax).

Функционал:

* Добавление фильтров (они же опции)
* Присвоение разных фильтров разным моделям (по значению поля)
* Управление вариантами опций
* Фильтрация ActiveQuery по значению фильтра
* Набор виджетов

![options](https://cloud.githubusercontent.com/assets/8104605/15528166/cfe6a88c-225a-11e6-8667-133de2da2dbe.png)

Установка
---------------------------------
Выполнить команду

```
php composer require dvizh/yii2-filter "@dev"
```

Или добавить в composer.json

```
"dvizh/yii2-filter": "@dev",
```

И выполнить

```
php composer update
```

Далее, мигрируем базу:

```
php yii migrate --migrationPath=vendor/dvizh/yii2-filter/src/migrations
```

Подключение и настройка
---------------------------------
В конфигурационный файл приложения добавить модуль filter, настроив его

```php
    'modules' => [
        //...
        'filter' => [
            'class' => 'dvizh\filter\Module',
            'relationFieldName' => 'category_id', //Наименование поля, по значению которого будут привязыватья опции
            //callback функция, которая возвращает варианты relationFieldName
            'relationFieldValues' =>
                function() {
                    //Пример с деревом:
                    $return = [];
                    $categories = \common\models\Category::find()->all();
                    foreach($categories as $category) {
                       if(empty($category->parent_id)) {
                            $return[] = $category;
                            foreach($categories as $category2) {
                                if($category2->parent_id == $category->id) {
                                    $category2->name = ' --- '.$category2->name;
                                    $return[] = $category2;
                                }
                            }
                       }
                    }
                    return \yii\helpers\ArrayHelper::map($return, 'id', 'name');
                },
        ],
        //...
    ]
```

Управление фильтрами: ?r=filter/filter

Для модели, с которой работает фильтр, добавить поведение:

```php
    function behaviors() {
        return [
            'filter' => [
                'class' => 'dvizh\filter\behaviors\AttachFilterValues',
            ],
        ];
    }
```

Чтобы иметь возможность также фильтровать результаты Find, подменяем Query в модели:

```php
    public static function Find()
    {
        $return = new ProductQuery(get_called_class());
        return $return;
    }
```

В ProductQuery должно быть это поведение:

```php
    function behaviors()
    {
       return [
           'filter' => [
               'class' => 'dvizh\filter\behaviors\Filtered',
           ],
       ];
    }
```

Использование
---------------------------------
Получить опции и их значения из модели, в которой есть поведение AttachFilterValues:
```php
<?php if($filters = $model->getOptions()) {?>
    <?php foreach($filters as $filter_name => $filter_values) { ?>
        <p>
            <strong><?=$filter_name;?></strong>: <?=implode(', ', $filter_values);?>
        </p>
    <?php } ?> 
<?php } ?>
```

Получить значения одной опции по коду:
```php
<?=implode(', ', $model->getOption('code'));?>
```

Чтобы отфильтровать результаты подбора поделей, приняв во внимание данные, отправленные виджетом FilterPanel, добавьте вызов filtered:

```php
$productsFind = Product::find()->where(['category_id' => 11]);

if(Yii::$app->request->get('filter')) {
    $productsFind = $productsFind->filtered();
}

$products = $productsFind->all();
```

Выбрать все записи по значению опции:

```php
$productsFind = Product::find()->option('power', 100)->all(); //Все записи с power=100
$productsFind = Product::find()->option('power', 100, '>')->all(); //Все записи с power>100
$productsFind = Product::find()->option('power', 100, '<')->all(); //Все записи с power<100
```

Виджеты
---------------------------------

Блок выбора значений для опций модели $model (опция будет выведена, только если к данной модели через поле relationFieldName привязаны какие-то опции)

```php
<?=\dvizh\filter\widgets\Choice::widget(['model' => $model]);?>
```

Вывод блока с фильтрами (галочки, радиобаттоны и т.д.). Передается идентификатор, к которому привязаны фильтры по полю relationFieldName (чаще всего - ID категории)

```php
<?=\dvizh\filter\widgets\FilterPanel::widget(['itemId' => $model->id]);?>
```
* itemId - значение relationFieldName

Чтобы фильтрация заработала, необходимо добавить в цепочку вызовов AQ:

```php
if(Yii::$app->request->get('filter')) {
    $products = $products->filtered();
}
```

Чтобы FilterPanel работал по ajax, необходимо сконфигурировать его следующим образом:

```php
<?=\dvizh\filter\widgets\FilterPanel::widget(['itemId' => $model->id, 'findModel' => $query, 'ajaxLoad' => true, 'resultHtmlSelector' => '#productsList']);
```

Где resultHtmlSelector - это CSS селектор элемента, в котором выводятся продукты на странице, findModel - экземпляр AQ продуктов.
