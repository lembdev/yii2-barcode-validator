/**
 * Created by lemb on 22.11.14.
 */

yii.validation.barcode = function (value, messages, options) {
    if (options.skipOnEmpty && yii.validation.isEmpty(value)) {
        return;
    }

    var $type = $('#' + options.typeAttribute);
    var type = $type.val();

    var length = null;
    var characters = /^\d+$/;
    var checksum = 'GTIN';

    if(type == 'Code39' || type == 'Code93') {
        characters = /^[0-9 A-Z\-\+&\/]+$/;
        checksum = (type == 'Code39') ? 'Code39' : 'Code93';
    }

    if(type.slice(0, 3) == 'EAN') {
        length = (type == 'EAN8') ? '7,8' : type.match(/\d+$/)[0];
        checksum = (type.match(/\d+$/)[0] < 8) ? null : checksum;
        checksum = (value.length == 7) ? null : checksum;
    }

    if(length) {
        yii.validation.regularExpression(value, messages, {
            not: false,
            pattern: new RegExp("^.{" + length + "}$", "g"),
            message: options.messageLength.replace(/\{length}/g, length)
        })
    }

    if(characters) {
        yii.validation.regularExpression(value, messages, {
            not: false,
            pattern: characters,
            message: options.messageCharacters
        })
    }

    if(checksum) {
        switch (checksum) {
            case 'GTIN':
                yii.validation.barcodeChecksumGTIN(value, messages, { message: options.messageChecksum });
                break;
            case 'Code39':
                yii.validation.barcodeChecksumCode39(value, messages, { message: options.messageChecksum });
                break;
            case 'Code93':
                yii.validation.barcodeChecksumCode93(value, messages, { message: options.messageChecksum });
        }
    }

    $type
        .off('change.barcodeValidator')
        .on('change.barcodeValidator', function(){
            yii.validation.barcode(value, messages, options)
        });
};

yii.validation.barcodeChecksumGTIN = function (value, messages, options) {
    if(value.length <= 7) {
        return;
    }

    var sum = 0;
    var code = parseInt( value.slice(-1));
    var length = value.length - 1;

    for(var i = 0; i <= length; i++) {
        sum += ((i % 2) === 0)
            ? code * 3
            : code;
    }

    var calc = sum % 10;
    var checksum = (calc === 0) ? 0 : (10 - calc);

    if(code != checksum) {
        yii.validation.addMessage(messages, options.message, value);
    }
};

yii.validation.barcodeChecksumCode39 = function (value, messages, options) {
    var check = {
        '0': 0,  '1': 1,  '2': 2,  '3': 3,  '4': 4,  '5': 5,  '6': 6,
        '7': 7,  '8': 8,  '9': 9,  'A': 10, 'B': 11, 'C': 12, 'D': 13,
        'E': 14, 'F': 15, 'G': 16, 'H': 17, 'I': 18, 'J': 19, 'K': 20,
        'L': 21, 'M': 22, 'N': 23, 'O': 24, 'P': 25, 'Q': 26, 'R': 27,
        'S': 28, 'T': 29, 'U': 30, 'V': 31, 'W': 32, 'X': 33, 'Y': 34,
        'Z': 35, '-': 36, '.': 37, ' ': 38, '$': 39, '/': 40, '+': 41,
        '%': 42
    };

    var checksum = value.slice(-1);
    var code = value.slice(0, -1);
    var count = 0;

    for(var i = 0; i <= code.length; i++) {
        count += check[ value[i] ];
    }

    if((count % 43) == check[checksum] ) {
        yii.validation.addMessage(messages, options.message, value);
    }
};

yii.validation.barcodeChecksumCode93 = function (value, messages, options) {
    var check = {
        '0': 0,  '1': 1,  '2': 2,  '3': 3,  '4': 4,  '5': 5,  '6': 6,
        '7': 7,  '8': 8,  '9': 9,  'A': 10, 'B': 11, 'C': 12, 'D': 13,
        'E': 14, 'F': 15, 'G': 16, 'H': 17, 'I': 18, 'J': 19, 'K': 20,
        'L': 21, 'M': 22, 'N': 23, 'O': 24, 'P': 25, 'Q': 26, 'R': 27,
        'S': 28, 'T': 29, 'U': 30, 'V': 31, 'W': 32, 'X': 33, 'Y': 34,
        'Z': 35, '-': 36, '.': 37, ' ': 38, '$': 39, '/': 40, '+': 41,
        '%': 42, '!': 43, '"': 44, '§': 45, '&': 46
    };
    var checksum = value.slice(-2);
    var code = value.slice(0, -2);
    var count = 0;
    var length = code.length % 20;

    for(var i = 0; i <= code.length; i++) {
        length = (length == 0) ? 20 : length;
        count += check[ value[i] ] * length;
        --length;
    }

    /*
     $checksum = substr($value, -2, 2);
     $value = str_split(substr($value, 0, -2));
     $count = 0;
     $length = count($value) % 20;

     foreach($value as $char) {
         $length = ($length == 0) ? 20 : $length;
         $count += $check[$char] * $length;
         --$length;
     }

     $check   = array_search(($count % 47), $check);
     $value[] = $check;
     $count   = 0;
     $length  = count($value) % 15;

     foreach($value as $char) {
         $length = ($length == 0) ? 15 : $length;
         $count += $check[$char] * $length;
         --$length;
     }

     $check .= array_search(($count % 47), $check);

     return ($check == $checksum);
     */

};