<?php
/**
 * @link http://astwell.com/
 * @copyright Copyright (c) 2014 Astwell Soft
 * @license http://astwell.com/license/
 */

namespace lembadm\barcode;

use Yii;
use yii\base\ErrorException;
use yii\base\InvalidConfigException;
use yii\validators\Validator;

class BarcodeValidator extends Validator
{

    /* @var string */
    public $type;

    /* @var string */
    public $typeAttribute;

    /* @var BarcodeAbstract $validator */
    protected $validator;

    public function init()
    {
        if(! $this->type and ! $this->typeAttribute) {
            throw new InvalidConfigException('Not set barcode type');
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $this->getValidator($model)->validateAttribute($model, $attribute);
    }

    public function getValidator($model)
    {
        $class = __NAMESPACE__ . '\\type\\' . $model->{$this->type ?: $this->typeAttribute};

        try {
            $this->validator = new $class;
        }
        catch (ErrorException $e) {

        }

        return $this->validator;
    }
}