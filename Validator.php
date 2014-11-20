<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode;

use yii\validators\Validator;

class BarcodeValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        $this->addError($model, $attribute, 'The country must be either "USA" or "Web".');
    }
}