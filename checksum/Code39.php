<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode\checksum;

use lembadm\barcode\AbstractChecksum;

class Code39 extends AbstractChecksum
{
    protected function validateChecksum($value)
    {
        $check = [
            '0' =>  0, '1' =>  1, '2' =>  2, '3' =>  3, '4' =>  4, '5' =>  5, '6' =>  6,
            '7' =>  7, '8' =>  8, '9' =>  9, 'A' => 10, 'B' => 11, 'C' => 12, 'D' => 13,
            'E' => 14, 'F' => 15, 'G' => 16, 'H' => 17, 'I' => 18, 'J' => 19, 'K' => 20,
            'L' => 21, 'M' => 22, 'N' => 23, 'O' => 24, 'P' => 25, 'Q' => 26, 'R' => 27,
            'S' => 28, 'T' => 29, 'U' => 30, 'V' => 31, 'W' => 32, 'X' => 33, 'Y' => 34,
            'Z' => 35, '-' => 36, '.' => 37, ' ' => 38, '$' => 39, '/' => 40, '+' => 41,
            '%' => 42,
        ];
        $checksum = substr($value, -1, 1);
        $value = str_split(substr($value, 0, -1));
        $count = 0;

        foreach($value as $char) {
            $count += $check[$char];
        }

        return (($count % 43) == $check[$checksum]);
    }
}