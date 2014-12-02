<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstractType;

class ITF14 extends BarcodeAbstractType
{
    /**
     * Allowed barcode lengths
     */
    protected $length = 14;

    /**
     * Allowed barcode characters
     */
    protected $characters = '0123456789';

    /**
     * Checksum function
     */
    protected $checksum = 'EAN';
}