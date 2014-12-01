<?php
/**
 * This is the template for generating the query class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */

echo "<?php\n";
?>

namespace <?= $generator->queryNs ?>;

use Yii;

/**
 * This is the ActiveQuery class for table "<?= $generator->generateTableName($tableName) ?>".
 * It can be used to define custom scopes.
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->baseQueryClass, '\\') . "\n" ?>
{
}
