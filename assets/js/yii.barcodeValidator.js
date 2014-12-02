/**
 * Created by lemb on 22.11.14.
 */

yii.validation.barcode = function (value, messages, options) {
    if (options.skipOnEmpty && yii.validation.isEmpty(value)) {
        return;
    }

    var length;
    var characters = /^\d+$/;
    var checksum = 'EAN';
    var type = $('#' + options.typeAttribute)
        .off('change.barcodeValidator')
        .on('change.barcodeValidator', function(){ yii.validation.barcode(value, messages, options) })
        .val();

    if(type == 'Code39') {
        characters = /^[0-9 A-Z\-\+&\/]+$/;
        checksum = 'Code39';
    }

    if(type == 'EAN8') {
        length = 8;
    }

    if(type == 'EAN12') {
        length = 12;
    }

    if(type == 'EAN13') {
        length = 13;
    }

    if(type == 'ITF14') {
        length = 14;
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

    if(checksum && checksum == 'EAN') {
        yii.validation.barcodeChecksumEAN(value, messages, {message: options.messageChecksum});
    }

    if(checksum && checksum == 'Code39') {
        yii.validation.barcodeChecksumCode39(value, messages, { message: options.messageChecksum });
    }
};

yii.validation.barcodeChecksumEAN = function (value, messages, options) {
    var sum = 0;
    var odd = true;
    var code = parseInt(value.slice(-1));

    for (var i = 11; i > -1; i--) {
        sum += (odd ? 3 : 1) * parseInt(value.charAt(i));
        odd = !odd;
    }

    if (code != ((10 - sum % 10) % 10)) {
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