<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstractType;

/**
 * Class Code39
 *
 * This barcode has a variable length. It supports digits, upper cased alphabetical characters and 7 special characters
 * like whitespace, point and dollar sign. It can have an optional checksum which is calculated with modulo 43.
 * This standard is used worldwide and common within the industry.
 *
 * @package lembadm\barcode\type
 */
class Base extends BarcodeAbstractType
{
    /**
     * Allowed barcode characters
     */
    protected $characters = '*';
}