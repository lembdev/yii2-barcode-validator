<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\AbstractType;

class Code39 extends AbstractType
{
    protected $characters = '/^[0-9 A-Z\-\+&\/]+$/';

    protected $checksum = 'Code39';
}