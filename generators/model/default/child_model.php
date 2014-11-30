<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */

echo "<?php\n";
?>

namespace <?= $generator->getChildNs() ?>;

use Yii;

/**
 * This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
 *
 * Check the base class at <?= $generator->ns . '\\' . $className ?> in order to
 * see the column names and relations.
 */
class <?= $className ?> extends <?= '\\' . ltrim($generator->ns, '\\') . '\\' . $className . "\n" ?>
{

}
