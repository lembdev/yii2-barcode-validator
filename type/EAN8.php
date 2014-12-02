<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstractType;

/**
 * Class EAN8
 *
 * The EAN8 is composed by 7 data digits + 1 checksum digit.
 * The EAN8 is derived from EAN13. It is based on the same tables as EAN 13 and has the same checksum calculation.
 *
 * @link http://barcode-coder.com/en/ean-8-specification-101.html
 * @package lembadm\barcode\type
 */
class EAN8 extends BarcodeAbstractType
{
	/**
	 * Allowed barcode lengths
	 */
	protected $length = 8;

	/**
	 * Allowed barcode characters
	 */
	protected $characters = '0123456789';

	/**
	 * Checksum function
	 */
	protected $checksum = 'EAN';
}