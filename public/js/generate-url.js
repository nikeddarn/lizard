(function($) {
    $.fn.generateUrl = function(options) {
        let UrlGenerator = {
            options: {
                urlField: null,
                emptyOnly: true,
                bindType: 'change'
            },

            init: function(options) {
                this.setOptions(options);
                this.bindEvents();
            },

            setOptions: function(options) {
                options = $.extend(this.options, options);

                if (!this.isJQuery(options.urlField)) {
                    options.urlField = $(options.urlField);
                    if (options.urlField.length === 0) {
                        throw new Error('Option "urlField" must be a jQuery object or selection');
                    }
                }

                if (typeof(options.emptyOnly) !== 'boolean') {
                    throw new Error('Option "emptyOnly" must be a boolean value');
                }

                options.space = '-';

                this.options = options;
            },

            isJQuery: function(element) {
                return element instanceof jQuery;
            },

            bindEvents: function() {
                let urlField = this.options.urlField;

                if (!this.options.emptyOnly || urlField.val() === '') {
                    let self = this;
                    this.options.source.on(this.options.bindType, function() {
                        let oldValue = urlField.val();
                        let newValue = self.convert();

                        if (oldValue !== newValue) {
                            urlField.val(newValue);
                            self.burnEvent({
                                type: 'urlchanged',
                                url: newValue
                            });
                        }
                    });
                }
            },

            convert: function() {
                let space = this.options.space;
                let text = this.options.source.val().toLowerCase();
                let url = '';
                let replacement = {
                    'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
                    'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
                    'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
                    'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
                    'я': 'ya'
                };

                for (let i = 0; i < text.length; i++) {
                    if(/[а-яё]/.test(text.charAt(i))) {
                        url += replacement[text.charAt(i)];
                    } else if (/[a-z0-9]/.test(text.charAt(i))) {
                        url += text.charAt(i);
                    } else {
                        url += space;
                    }
                }

                url = url
                    .replace(/-{2,}/g, '-')
                    .replace(/^-|-$/g, '');

                return url;
            },

            burnEvent: function(data) {
                let event = jQuery.Event(data.type, {
                    sourceField: this.options.source,
                    urlField: this.options.urlField,
                    url: data.url
                });
                this.options.source.trigger(event);
            }
        };

        options.source = this;
        UrlGenerator.init(options);

        return this;
    }
})(jQuery);