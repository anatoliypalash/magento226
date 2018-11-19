define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/alert',
    'mage/cookies',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('palash.askquestion_widget', $.mage.alert, {
        options: {
            cookieName: 'ask_question_timestamp'
        },

        /** @inheritdoc */
        _create: function () {
            $(this.element).submit(this.submitForm.bind(this));
        },

        /**
         * Validate request and submit the form if able
         */
        submitForm: function () {
            if (!this.validateForm()) {
                return;
            }
           
            this.ajaxSubmit();
        },

        /**
         * Validate request form
         */
        validateForm: function () {
            return $(this.element).validation().valid();
        },

        /**
         * Submit request via AJAX. Add form key to the post data.
         */
        ajaxSubmit: function () {
            var formData = new FormData($(this.element).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));
            formData.append('ask_question_timestamp', $.mage.cookies.get(this.options.cookieName));
            formData.append('isAjax', 1);

            $.ajax({
                url: $(this.element).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /** @inheritdoc */
                success: function (response) {
                    alert({
                        title: $.mage.__(response.status),
                        content: $.mage.__(response.message)
                    });

                    if (response.status === 'Success') {
                        $.mage.cookies.set(this.options.cookieName, new Date().getTime());
                    }
                },

                /** @inheritdoc */
                error: function () {
                    alert({
                        title: $.mage.__('Error'),
                        content: $.mage.__('Your request can not be submitted right now.')
                    });
                }
            });
        },

        /**
         * Clear that `ask_question_timestamp` cookie
         */
        clearCookie: function () {
            $.mage.cookies.clear(this.options.cookieName);
        }
    });

    return $.palash.askquestion_widget;
});
