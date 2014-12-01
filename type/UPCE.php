<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\AbstractType;

class UPCE extends AbstractType
{
    protected $length = [6, 7, 8];

    protected $characters = '/^\d+$/';

    protected $checksum = 'GTIN';

    protected function validateLength($model, $attribute)
    {
        $this->checksum = !(strlen($model->{$attribute}) == 8)
            ? $this->checksum
            : null;

        parent::validateLength($model, $attribute);
    }
}