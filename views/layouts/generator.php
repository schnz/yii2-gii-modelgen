<?php
coksnuss\gii\modelgen\GiiAsset::register($this);

$decorator = $this->beginContent('@yii/gii/views/layouts/generator.php');
echo $content;
$this->endContent();
?>
