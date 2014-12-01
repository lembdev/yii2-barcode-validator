<?php
/**
 * @link      http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license   http://astwell.com/license/
 */

namespace lembadm\barcode;

use Yii;
use yii\validators\Validator;

abstract class AbstractChecksum extends Validator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->message = $this->message ?: Yii::t('yii', '{attribute} checksum not valid');
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if (!$this->validateChecksum($value)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        BarcodeAsset::register($view);

        return null;
    }

    abstract protected function validateChecksum($value);
}