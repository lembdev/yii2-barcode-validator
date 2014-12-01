<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\checksum;

use Yii;
use yii\helpers\Json;
use lembadm\barcode\AbstractChecksum;

class GTIN extends AbstractChecksum
{
    protected function validateChecksum($value)
    {
        $sum = 0;
        $barcode = substr($value, 0, -1);
        $length = strlen($barcode) - 1;

        for ($i = 0; $i <= $length; $i++) {
            $sum += (($i % 2) === 0)
                ? $barcode[ $length - $i ] * 3
                : $barcode[ $length - $i ];
        }

        $calc = $sum % 10;

        $checksum = ($calc === 0) ? 0 : (10 - $calc);

        return !($value[ $length + 1 ] != $checksum);
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        parent::clientValidateAttribute($model, $attribute, $view);

        $options = [
            'skipOnEmpty' => $this->skipOnEmpty,
            'message' => Yii::$app->getI18n()->format($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
            ], Yii::$app->language),
        ];

        return 'yii.validation.barcodeChecksumGTIN(value, messages, ' . Json::encode($options) . ');';
    }

}