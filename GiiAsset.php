<?php
namespace coksnuss\gii\modelgen;

use yii\web\AssetBundle;

/**
 * Extends default gii assets by an additional JS file.
 */
class GiiAsset extends AssetBundle
{
    public $sourcePath = '@coksnuss/gii/modelgen/assets';
    public $js = [
        'gii.js',
    ];
    public $depends = [
        'yii\gii\GiiAsset',
    ];
}
