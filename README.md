Yii2 Barcode validator
======================
Barcode validator

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist lembadm/yii2-barcode-validator "*"
```

or add

```
"lembadm/yii2-barcode-validator": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your model validation rules  :

```php
<?php

class Product extends ActiveRecord
{

    public function rules()
    {
        return [
            // Barcode [[barcode]]
            ['barcode', 'required'],
            ['barcode', 'string', 'max' => 255],
            ['barcode', BarcodeValidator::className(), 'typeAttribute' => 'code_type'],

            // Barcode type [[code_type]]
            ['code_type', 'required'],
        ];
    }

}
```