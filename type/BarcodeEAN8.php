<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use lembadm\barcode\BarcodeAbstract;

class BarcodeEAN8 extends BarcodeAbstract
{
    /**
     * @var integer Allowed barcode lengths
     */
    protected $length = [7, 8];

    /**
     * @var string Allowed barcode characters
     */
    protected $characters = '0123456789';

    /**
     * @var string Checksum function
     */
    protected $checksum = '_gtin';

    /**
     * Overrides parent checkLength
     *
     * @param string $value Value
     * @return boolean
     */
    public function checkLength($value)
    {
        $this->setHasChecksum( !strlen($value) == 7 );

        return parent::checkLength($value);
    }
}