define([
    'jquery',
    'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'mage/cookies',
    'mage/translate',
    'jquery/ui',
], function ($, alert) {
    'use strict';

    $.widget('palash.askquestion_widget', {
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
            if ($.mage.cookies.get(this.options.cookieName)) {
                alert({
                    title: $.mage.__('Error'),
                    content: $.mage.__('It is not possible to post more than one question in 2 minutes.')
                });

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
            var self = this;
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
                        var date = new Date();
                        date.setTime(date.getTime() + (120 * 1000));
                        $.mage.cookies.set(self.options.cookieName, 1, {expires: date});
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
    });

    return $.palash.askquestion_widget;
});
