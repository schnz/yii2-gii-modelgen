<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator yii\gii\generators\form\Generator */

require Yii::getAlias('@yii/gii/generators/model/form.php');

echo $form->field($generator, 'includeTimestampBehavior')->checkbox();
echo $form->field($generator, 'createdColumnName');
echo $form->field($generator, 'updatedColumnName');
