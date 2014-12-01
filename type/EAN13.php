<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\AbstractType;

class EAN13 extends AbstractType
{
    protected $length = 13;

    protected $characters = '/^\d+$/';

    protected $checksum = 'GTIN';
}