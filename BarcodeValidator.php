<?php
/**
 * @link      http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license   http://astwell.com/license/
 */

namespace lembadm\barcode;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\validators\Validator;

/**
 * Class BarcodeValidator
 *
 * @package lembadm\barcode
 */
class BarcodeValidator extends Validator
{
    /**
     * Barcode type
     */
    public $type;

    /**
     * Barcode type in model property
     */
    public $typeAttribute;

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
     * @var \yii\base\Model Barcode adapter
     */
    protected $adapter;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ( ! $this->type and ! $this->typeAttribute) {
            throw new InvalidConfigException( 'Not set barcode type' );
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute( $model, $attribute )
    {
        $this->getAdapter( $model )->validateAttribute( $model, $attribute );
    }

    /**
     * @inheritdoc
     */
    public function validateValue( $value )
    {
        return $this->getAdapter()->validateValue( $value );
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute( $model, $attribute, $view )
    {
        $attributeID = Html::getInputId($model, $attribute);
        $typeAttributeID = Html::getInputId($model, $this->typeAttribute);

        $view->registerJs('
            $("#' . $typeAttributeID . '").on("change", function(){
                var $type = $(this);
                var $code = $("#' . $attributeID . '");
                var $yiiActiveFormData = $code.closest("form").yiiActiveForm("data");

                $.each($yiiActiveFormData.attributes, function () {
                    if(this.id == "' . $attributeID . '") this.status = 2;
                });
            })
        ');

        if($this->type) {
            return $this->getAdapter($model)->clientValidateAttribute($model, $attribute, $view);
        }
        else {
            BarcodeAsset::register($view);
            $options = $this->getClientOptions($model, $attribute);
            return 'yii.validation.barcode(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
        }
    }

    /**
     * Returns the client side validation options.
     * @param \yii\base\Model $model the model being validated
     * @param string $attribute the attribute name being validated
     * @return array the client side validation options
     */
    protected function getClientOptions($model, $attribute)
    {
        $label = $model->getAttributeLabel($attribute);

        $format = function($message, $label){
            return Yii::$app->getI18n()->format($message, ['attribute' => $label], Yii::$app->language);
        };

        return [
            'skipOnEmpty'       => $this->skipOnEmpty,
            'typeAttribute'     => Html::getInputId( $model, $this->typeAttribute ),
            'messageLength'     => $format( $this->getAdapter( $model )->messageLength, $label ),
            'messageChecksum'   => $format( $this->getAdapter( $model )->messageChecksum, $label ),
            'messageCharacters' => $format( $this->getAdapter( $model )->messageCharacters, $label ),
        ];
    }

    /**
     * @param \yii\base\Model $model
     *
     * @return BarcodeAbstractType
     * @throws InvalidConfigException
     */
    protected function getAdapter( $model = null )
    {
        $type = $this->type ? $this->type : $model->{$this->typeAttribute};

        if ( ! $this->adapter) {
            $this->adapter = Yii::createObject( [
                'class'             => __NAMESPACE__ . '\\type\\' . $type,
                'messageLength'     => $this->messageLength,
                'messageChecksum'   => $this->messageChecksum,
                'messageCharacters' => $this->messageCharacters,
            ] );
        }

        return $this->adapter;
    }
}