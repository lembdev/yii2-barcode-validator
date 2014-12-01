<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\validators\Validator;

class BarcodeValidator extends Validator
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
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
     * @var AbstractType
     */
    protected $validator;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if(! $this->type and ! $this->typeAttribute) {
            throw new InvalidConfigException('Not set barcode type');
        }

        $this->messageLength = $this->messageLength
            ?: Yii::t('yii', '{attribute} should have a length of {length} characters');

        $this->messageCharacters = $this->messageCharacters
            ?: Yii::t('yii', '{attribute} contains incorrect characters');

        $this->messageChecksum = $this->messageChecksum
            ?: Yii::t('yii', '{attribute} failed checksum validation');
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $this->getValidator($model)->validateAttribute($model, $attribute);
    }

    /**
     * @inheritdoc
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $attributeID = Html::getInputId($model, $attribute);
        $typeAttributeID = Html::getInputId($model, $this->typeAttribute);

        $view->registerJs('
            $("#' . $typeAttributeID . '").on("change", function(){
                var $type = $(this);
                var $code = $("#' . $attributeID . '");
                var $yiiActiveFormData = $code.closest("form").yiiActiveForm("data");

                $.each($yiiActiveFormData.attributes, function () {
                    if(this.id == "' . $attributeID . '") {
                        this.status = 2
                    }
                });

                //$code.trigger("blur.yiiActiveForm");
            })
        ');

        if($this->type) {
            return $this->getValidator($model)->clientValidateAttribute($model, $attribute, $view);
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

        $options = [
            'skipOnEmpty'   => $this->skipOnEmpty,
            'typeAttribute' => Html::getInputId($model, $this->typeAttribute),
        ];

        $options['messageLength'] = Yii::$app->getI18n()->format($this->messageLength, [
            'attribute' => $label
        ], Yii::$app->language);

        $options['messageChecksum'] = Yii::$app->getI18n()->format($this->messageChecksum, [
            'attribute' => $label
        ], Yii::$app->language);

        $options['messageCharacters'] = Yii::$app->getI18n()->format($this->messageCharacters, [
            'attribute' => $label
        ], Yii::$app->language);

        return $options;
    }

    protected function getValidator($model)
    {
        if( !$this->validator ) {
            $this->validator = \Yii::createObject([
                'class' => __NAMESPACE__ . '\\type\\' . $model->{$this->type ?: $this->typeAttribute},
                'messageLength'     => $this->messageLength,
                'messageChecksum'   => $this->messageChecksum,
                'messageCharacters' => $this->messageCharacters,
            ]);
        }

        return $this->validator;
    }
}