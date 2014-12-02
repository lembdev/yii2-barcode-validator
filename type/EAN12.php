<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstractType;

/**
 * Class EAN12
 *
 * The EAN13 is composed by 11 data digits + 1 checksum digit.
 *
 * @package lembadm\barcode\type
 */
class EAN12 extends BarcodeAbstractType
{
    /**
     * Allowed barcode lengths
     */
    protected $length = 12;

    /**
     * Allowed barcode characters
     */
    protected $characters = '0123456789';

    /**
     * Checksum function
     */
    protected $checksum = 'EAN';
}