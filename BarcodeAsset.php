<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode;

use yii\web\AssetBundle;

class BarcodeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/lembadm/yii2-barcode-validator/assets';
    public $js = [
        'js/yii.barcodeValidator.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}