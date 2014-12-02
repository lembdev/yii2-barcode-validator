<?php
/**
 * @link      http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license   http://astwell.com/license/
 */

namespace lembadm\barcode;

use Yii;
use yii\validators\RegularExpressionValidator;
use yii\validators\Validator;

/**
 * Class BarcodeAbstractType
 *
 * @package lembadm\barcode
 */
abstract class BarcodeAbstractType extends Validator
{
    /**
     * @var string user-defined error message used when the barcode length not valid
     */
    public $messageLength;

    /**
     * @var string user-defined error message used when the barcode contains wrong characters
     */
    public $messageCharacters;

    /**
     * @var string user-defined error message used when the barcode checksum is wrong
     */
    public $messageChecksum;

    /**
     * @var integer Allowed barcode lengths
     */
    protected $length;

    /**
     * @var string Allowed barcode characters
     */
    protected $characters;

    /**
     * @var string Callback to checksum function
     */
    protected $checksum;

    /**
     * @var \yii\validators\RegularExpressionValidator
     */
    protected $validatorLength;

    /**
     * @var \yii\validators\RegularExpressionValidator
     */
    protected $validatorCharacters;

    /**
     * @var \lembadm\barcode\BarcodeChecksum
     */
    protected $validatorChecksum;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->messageLength = $this->messageLength
            ?: Yii::t( 'yii', '{attribute} should have a length of {length} characters' );

        $this->messageCharacters = $this->messageCharacters
            ?: Yii::t( 'yii', '{attribute} contains incorrect characters' );

        $this->messageChecksum = $this->messageChecksum
            ?: Yii::t( 'yii', '{attribute} failed checksum validation' );

        if ($this->length) {
            $this->validatorLength = Yii::createObject( [
                'class'   => RegularExpressionValidator::className(),
                'message' => Yii::$app->getI18n()->format($this->messageLength, ['length' => $this->length], Yii::$app->language),
                'pattern' => '/^.{' . $this->length . '}$/',
            ] );
        }

        if ($this->characters) {
            $this->validatorCharacters = Yii::createObject( [
                'class'   => RegularExpressionValidator::className(),
                'message' => $this->messageCharacters,
                'pattern' => '/^[' . $this->characters . ']+$/',
            ] );
        }

        if ($this->checksum) {
            $this->validatorChecksum = Yii::createObject( [
                'class'   => BarcodeChecksum::className(),
                'message' => $this->messageChecksum,
                'method'  => $this->checksum,
            ] );
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute( $model, $attribute )
    {
        $this->validateLength( $model, $attribute );
        $this->validateCharacters( $model, $attribute );
        $this->validateChecksum( $model, $attribute );
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $return = '';

        if($this->length) {
            $return .= $this->validatorLength->clientValidateAttribute($model, $attribute, $view);
        }

        if($this->characters) {
            $return .= $this->validatorCharacters->clientValidateAttribute($model, $attribute, $view);
        }

        if($this->checksum) {
            $return .= $this->validatorChecksum->clientValidateAttribute($model, $attribute, $view);
        }

        return $return;
    }

    /**
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateLength( $model, $attribute )
    {
        if ($this->length) {
            $this->validatorLength->validateAttribute( $model, $attribute );
        }
    }

    /**
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateCharacters( $model, $attribute )
    {
        if ($this->characters) {
            $this->validatorCharacters->validateAttribute( $model, $attribute );
        }
    }

    /**
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateChecksum( $model, $attribute )
    {
        if ($this->checksum) {
            $this->validatorChecksum->validateAttribute( $model, $attribute );
        }
    }
}