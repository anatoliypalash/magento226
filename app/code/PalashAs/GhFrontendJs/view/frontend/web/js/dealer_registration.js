define([
        "jquery", "Magento_Ui/js/modal/modal"
    ], function($){
        var DealerRegistrationModal = {
            initModal: function(config, element) {
                $dealerForm = $('#dealer-registration-form');
                $dealerForm.modal();
                $element = $(element);
                $element.click(function() {
                    $dealerForm.modal('openModal');
                    $dealerForm.trigger('contentUpdated');
                });
            }
        };

        return {
            'dregistration': DealerRegistrationModal.initModal
        };
    }
);
