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
use lembadm\barcode\type\BarcodeAbstract;

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
        if(! $this->type or ! $this->typeAttribute) {
            throw new InvalidConfigException('Not set barcode type');
        }

        $class = __NAMESPACE__ . '\\' .  $this->type;

        try {
            $this->validator = new $class;
        }
        catch (ErrorException $e) {

        }
    }

    public function validateAttribute($model, $attribute)
    {
        $this->validator->validateAttribute($model, $attribute);
    }
}