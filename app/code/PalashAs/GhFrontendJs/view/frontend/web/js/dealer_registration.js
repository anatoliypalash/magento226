define([
        "jquery", "Magento_Ui/js/modal/modal"
    ], function($){
        var DealerRegistrationModal = {
            initModal: function(config, element) {
                $dealerForm = $('.form-create-account').clone();
                $dealerForm.modal();
                $element = $(element);
                $element.click(function() {
                    $dealerForm.modal('openModal');
                });
            }
        };

        return {
            'dregistration': DealerRegistrationModal.initModal
        };
    }
);
