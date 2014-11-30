yii2-gii-modelgen
=================

A tweaked model generator for the Gii module of the Yii 2 framework.

What this extension does
------------------------

This extension basicly add three features to gii's basic model template generator:

1. The generated models are splitted into a base model and a concrete model. Whenever the database structure is changed (e.g. through a migration) the base model can simply be overridden by the newly generated model. No custom code is touched. All custom code is written into the concrete model which is used throughout the application and extends the base model.
2. A new option is added to directly integrate the timestamp behavior into models by providing the column names of the created_at and updated_at column.
3. When generating models for all tables using the wildcard operator `*` the migration table is omitted.


Installation
------------

First this extension needs to included via composer by issuing the following console command within the root directory of your yii2 project:

~~~
composer require --prefer-dist "coksnuss/yii2-gii-modelgen"
~~~

Furthermore Gii needs to know of the newly available model template. This is archieved by modifying the corresponding configuration file

~~~
[...]
'class' => 'yii\gii\Module',
'generators' => [
    'model' => ['class' => 'common\gii\generators\model\Generator'],
],
[...]
~~~

Thats it.
