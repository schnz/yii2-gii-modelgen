yii2-gii-modelgen
=================

A tweaked model generator for the Gii module of the Yii 2 framework.
The generator re-uses Gii-native code wherever possible and thus has a high chance of retaining all the latest changes of Gii without the need for an update. When checked for the last time, the generator retains the full featureset of Gii 2.0.8.

What this extension does
------------------------

This extension basically adds three features to gii's basic model template generator:

1. The generated models are splitted into a base model and a concrete model. Whenever the database structure is changed (e.g. through a migration) the base model can simply be overridden by the newly generated model. No custom code is touched. All custom code is written into the concrete model which is used throughout the application and extends the base model.
2. A new option is added to directly integrate the timestamp behavior into models by providing the column names of the created_at and updated_at column.
3. When generating models for all tables using the wildcard operator `*` the migration table is omitted.
4. ~~In addition to the model class, it is possible to generate a query class.~~ (_This feature is now natively supported by Gii_)

In addition, the template files were slightly adapted:

1. The `tableName()` function within the generated model classes is only generated if it is required, i.e., if default implementation of `ActiveRecord::tableName()` is insufficient.
2. When generating a query class, it does not contain any boilerplate functions.

Installation
------------

First this extension needs to be included via composer by issuing the following console command within the root directory of your yii2 project:

~~~
composer require --prefer-dist "coksnuss/yii2-gii-modelgen"
~~~

Furthermore Gii needs to know of the newly available model template. This is achieved by modifying the corresponding configuration file

~~~
[...]
'class' => 'yii\gii\Module',
'generators' => [
    'model' => ['class' => 'coksnuss\gii\modelgen\generators\model\Generator'],
],
[...]
~~~

Thats it.
