<?php
namespace coksnuss\gii\modelgen\generators\model;

use Yii;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use yii\db\Schema;

class Generator extends \yii\gii\generators\model\Generator
{
    public $ns = 'app\models\base';
    public $queryNs = 'app\models\query';
    public $includeTimestampBehavior = false;
    public $createdColumnName = 'created_at';
    public $updatedColumnName = 'updated_at';

    private $_useDatetimeValue = false;


    public function init()
    {
        parent::init();

        if (Yii::$app instanceof \yii\web\Application) {
            Yii::$app->controller->layout = '@coksnuss/gii/modelgen/views/layouts/generator';
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['createdColumnName', 'updatedColumnName'],  'filter', 'filter' => 'trim'],
            [['includeTimestampBehavior'], 'boolean'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return array_merge(parent::requiredTemplates(), ['child_model.php']);
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), [
            'includeTimestampBehavior',
            'createdColumnName',
            'updatedColumnName',
        ]);
    }

   /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'includeTimestampBehavior' => 'Automatically includes a timestamp behavior to set the created_at and
                updated_at fields automatically. This option will also modify the rules accordingly.',
            'createdColumnName' => 'The column name of the field that is set to the current time when a new record is
                added.',
            'updatedColumnName' => 'The column name of the field that is set to the current time when a new record is
                added or an existing record is updated.',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $files = parent::generate();
        $db = $this->getDbConnection();

        foreach ($this->getTableNames() as $tableName) {
            $modelClassName = $this->generateClassName($tableName);
            $params = [
                'tableName' => $tableName,
                'className' => $modelClassName,
            ];
            $files[] = new CodeFile(
                Yii::getAlias('@' . str_replace('\\', '/', $this->getChildNs())) . '/' . $modelClassName . '.php',
                $this->render('child_model.php', $params)
            );
        }

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function generateRules($table)
    {
        $rules = parent::generateRules($table);

        if ($this->includeTimestampBehavior)
        {
            // Assume same datatype for created_at/updated_at columns
            $this->_useDatetimeValue = isset($table->columns[$this->createdColumnName]) &&
                $table->columns[$this->createdColumnName]->type === Schema::TYPE_DATETIME;

            foreach ($rules as $i => $rule) {
                if (
                    strpos($rule, "'{$this->createdColumnName}'") === false &&
                    strpos($rule, "'{$this->updatedColumnName}'") === false ||
                    strpos($rule, "'exist', " !== false)
                ) {
                    continue;
                }

                list($ruleFields, $ruleName) = eval("return {$rule};");

                if (($key = array_search($this->createdColumnName, $ruleFields)) !== false) {
                    unset($ruleFields[$key]);
                }
                if (($key = array_search($this->updatedColumnName, $ruleFields)) !== false) {
                    unset($ruleFields[$key]);
                }
                if (empty($ruleFields)) {
                    unset($rules[$i]);
                } else {
                    $newRuleFields = "['" . implode("', '", $ruleFields) . "']";
                    $rules[$i] = preg_replace('#^\[\[[^\]]+\]#', '[' . $newRuleFields, $rule);
                }
            }
        }

        return $rules;
    }

    /**
     * @inheritdoc
     */
    protected function generateRelations()
    {
        $relations = parent::generateRelations();

        // Set namespaces from base models to concrete models
        foreach ($relations as $className => &$rel) {
            foreach ($rel as $relClassName => &$relation) {
                $relation[0] = preg_replace('#\(([^:]+)::className\(\)#', '(\\' . $this->getChildNs() . '\\\$1::className()', $relation[0]);
                $relation[1] = '\\' .  $this->getChildNs() . '\\' . $relation[1];
            }
        }

        return $relations;
    }

    /**
     * @inheritdoc
     */
    protected function getTableNames()
    {
        $tableNames = parent::getTableNames();

        if (($key = array_search('migration', $tableNames)) !== false) {
            unset($tableNames[$key]);
            return array_values($tableNames);
        }

        return $tableNames;
    }

    /**
     * @return string The namespace for the child class which is used by the
     * developers for non-automatically generated code.
     */
    public function getChildNs()
    {
        return StringHelper::dirname($this->ns);
    }

    public function isNonStandardTableName($tableName, $className)
    {
        return $this->generateTableName($tableName) !==
            '{{%' .
            Inflector::camel2id(StringHelper::basename($className), '_') .
            '}}';
    }

    public function getTimestampBehavior()
    {
        if ($this->createdColumnName === 'created_at' &&
            $this->updatedColumnName === 'updated_at' &&
            !$this->_useDatetimeValue
        ) {
            return '\yii\behaviors\TimestampBehavior::class';
        }

        $config = ['class' => "\yii\behaviors\TimestampBehavior::class"];

        if ($this->createdColumnName !== 'created_at') {
            $config['createdAtAttribute'] = "'$this->createdColumnName'";
        }

        if ($this->updatedColumnName !== 'updated_at') {
            $config['updatedAtAttribute'] = "'$this->updatedColumnName'";
        }

        if ($this->_useDatetimeValue) {
            $config['value'] = "date('Y-m-d H:i:s')";
        }

        array_walk($config, function (&$v, $k) { $v = sprintf("'%s' => %s", $k, $v); });

        $config = "\n               " .
            implode(",\n               ", array_values($config)) .
            "\n           ";

        return "'timestamp' => [${config}]";
    }
}
