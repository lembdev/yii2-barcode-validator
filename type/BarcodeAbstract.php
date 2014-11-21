<?php
/**
 * @link      http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license   http://astwell.com/license/
 */

namespace lembadm\barcode\type;

use Yii;
use yii\validators\Validator;

abstract class BarcodeAbstract extends Validator
{
    public $msgStrNum;
    public $msgLength;
    public $msgChars;
    public $msgChecksum;

    /**
     * @var integer|array|string Allowed barcode lengths
     */
    protected $length;

    /**
     * @var string Allowed barcode characters
     */
    protected $characters;

    /**
     * @var string|array Callback to checksum function
     */
    protected $checksum;

    /**
     * @var boolean Is a checksum value included?
     */
    protected $hasChecksum = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->msgStrNum   = $this->msgStrNum   ?: Yii::t('yii', '{attribute} must be a string or number');
        $this->msgLength   = $this->msgLength   ?: Yii::t('yii', '{attribute} should have a length of {length} characters');
        $this->msgChars    = $this->msgChars    ?: Yii::t('yii', '{attribute} must consist only of characters {characters}');
        $this->msgChecksum = $this->msgChecksum ?: Yii::t('yii', '{attribute} failed checksum validation');
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if (!is_string($value) or !is_numeric($value)) {
            $this->addError($model, $attribute, $this->message);

            return;
        }

        if ($this->checkLength($value)) {
            $this->addError($model, $attribute, $this->msgLength, ['length' => $this->getLength()]);
        }

        if ($this->checkChars($value)) {
            $this->addError($model, $attribute, $this->msgChars, ['characters' => $this->getCharacters()]);
        }

        if ($this->isHasChecksum()) {
            $this->addError($model, $attribute, $this->msgChecksum);
        }
    }

    /**
     * Checks the length of a barcode
     *
     * @param  string $value The barcode to check for proper length
     *
     * @return boolean
     */
    public function checkLength($value)
    {
        $return = false;
        $valueLength = strlen((string)$value);
        $maxLength = $this->getLength();

        if (is_array($maxLength)) {
            foreach ($maxLength as $value) {
                if ($valueLength == $value) {
                    $return = true;
                }
                if ($value == -1) {
                    $return = true;
                }
            }
        } elseif ($maxLength == $valueLength) {
            $return = true;
        } elseif ($maxLength == -1) {
            $return = true;
        }

        return $return;
    }

    /**
     * Returns the allowed barcode length
     *
     * @return array|int|string
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Returns the allowed characters
     *
     * @return string
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * Checks for allowed characters within the barcode
     *
     * @param  string $value The barcode to check for allowed characters
     *
     * @return boolean
     */
    public function checkChars($value)
    {
        return preg_match("#^[" . $this->getCharacters() . "]+$#", (string)$value);
    }

    /**
     * Returns the checksum function name
     *
     * @return array|string
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Returns if barcode uses checksum
     *
     * @return boolean
     */
    public function isHasChecksum()
    {
        return $this->hasChecksum;
    }

    /**
     * Sets the checksum validation
     *
     * @param boolean $hasChecksum
     *
     * @return $this
     */
    public function setHasChecksum($hasChecksum)
    {
        $this->hasChecksum = (boolean)$hasChecksum;

        return $this;
    }
}