define([
    'jquery'
], function ($) {
    'use_strict';
    
    return function () {
        $.validator.addMethod(
            'validate-ua-phone-number',
            function (phone_number) {
                if ((phone_number.substring(0,3) == '+38') && phone_number.match(/([+]?\d{1,2}[.-\s]?)?(\d{3}[.-]?){2}\d{4}/g) ){
                    return true;
                } else {
                    return false;
                }
            },
            $.mage.__('Wrong number format')
        );
        
    }
});
