<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstractType;

/**
 * Class EAN13
 *
 * The EAN 13 is composed by 12 data digits + 1 checksum digit.
 *
 * @link http://barcode-coder.com/en/ean-13-specification-102.html
 * @package lembadm\barcode\type
 */
class EAN13 extends BarcodeAbstractType
{
    /**
     * Allowed barcode lengths
     */
    protected $length = 13;

    /**
     * Allowed barcode characters
     */
    protected $characters = '0123456789';

    /**
     * Checksum function
     */
    protected $checksum = 'EAN';
}