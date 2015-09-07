/**
 *  @name Required
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'required';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current    = this.element,
                fields     = current.data('required'),
                fieldArray = fields.split('|'),
                empty      = false;

            current.on('submit', function() {
                $.each(fieldArray, function(k, v) {
                    if ($('#' + v).val().trim() === '') {
                        $('#' + v).focus();
                        empty = true;

                        return false;
                    }
                });

                if (empty) {
                    return false;
                }
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Trigger event
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'event-trigger';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current    = this.element,
                target     = $(current.data('event-trigger')),
                events     = current.data('event'),
                eventArray = events.split('|'),
                firstEvent = eventArray[0],
                lastEvent  = eventArray[1];

            current.on(firstEvent, function(){
                switch(lastEvent) {
                    case 'click':
                        target.click();
                        break;
                    case 'submit':
                        target.submit();
                        break;
                }
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Ajax form
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'ajax-form';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current = this.element,
                that    = this,
                labels  = current.data('ajax-form').split('|'),
                submit  = current.find(':submit'),
                img     = submit.children('img'),
                text    = submit.children('b'),
                check   = submit.children('i');

            current.on('submit', function(){
                $.ajax({
                    type: current.attr('method'),
                    url: current.attr('action'),
                    data: current.serialize(),
                    beforeSend: function(){
                        that.loading(true, img, text, check);
                    },
                    error: function(response) {
                        var messages = response.responseJSON.messages;

                        that.loading(false, img, text, check, false);
                        that.showFormLabels(labels, messages);
                    },
                    success: function(response){
                        var messages = response.messages;
                        that.loading(false, img, text, check, true);
                        that.showFormLabels(labels, messages);
                    }
                });

                return false;
            });
        },
        showFormLabels: function(labels, messages){
            var current = this.element;
            $.each(labels, function(k, v) {
                var field  = current.find('[name^=' + v + ']'),
                    label  = field.parent('div').children('label');

                if (messages.hasOwnProperty(v)) {
                    var errorHtml = '<span class="_fwfl _tr5">' + messages[v][0] + '</span>'
                    label.html(errorHtml);
                } else {
                    var originalText = label.attr('data-title');
                    label.html(originalText);
                }
            });
        },
        loading: function(start, img, text, check, success) {
            if (start) {
                img.show();
                text.hide();
            } else {
                img.hide();
                text.show();
                if (success) {
                    check.show(200);
                    setTimeout(function(){
                        check.hide(200);
                    }, 3000);
                }
            }
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));


/**
 *  @name Upload Avatar
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'upload-avatar';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current      = this.element,
                chooseAvatar = $('.choose-avatar-btn'),
                avatarBig    = $('.avatar-big'),
                avatarMedium = $('.avatar-medium'),
                avatarSmall  = $('.avatar-small'),
                img          = chooseAvatar.children('img'),
                text         = chooseAvatar.children('b'),
                check        = chooseAvatar.children('i');

            current.on('submit', function(){
                return AIM.submit(this, {
                    onStart: function() {
                        img.show();
                        text.hide();
                        avatarBig.css({opacity:0.5});
                        avatarMedium.css({opacity:0.5});
                        avatarSmall.css({opacity:0.5});
                    },
                    onComplete: function(response){
                        var json     = $.parseJSON(response),
                            status   = json.status,
                            messages = json.messages;

                        $('.upload-avatar-messages').html('');
                        $('.upload-avatar-messages').hide();
                        img.hide();
                        text.show();
                        if (status === SETTING.AJAX_OK) {
                            var imageBig     = json.data['big'],
                                imageMedium  = json.data['medium'],
                                imageSmall   = json.data['small'];

                            check.show(200);
                            setTimeout(function() {
                                check.hide(200);
                            }, 2000);

                            avatarBig.attr('src', imageBig);
                            avatarMedium.attr('src', imageMedium);
                            avatarSmall.attr('src', imageSmall);
                        }

                        if (status === SETTING.AJAX_ERROR) {
                            $('.upload-avatar-messages').show();
                            $('.upload-avatar-messages').html(messages);
                        }

                        avatarBig.css({opacity:1});
                        avatarMedium.css({opacity:1});
                        avatarSmall.css({opacity:1});

                    }
                });
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Reset Form
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'reset-form';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current     = this.element,
                form        = $(current.data('reset-form')),
                formElement = form.attr('data-ajax-form');

            //For save product form
            if (formElement === undefined) {
                formElement = form.attr('data-save-product');
            }

            current.on('click', function(){
                $.each(formElement.split('|'), function(k, v) {
                    var field = form.find('[name^=' + v + ']'),
                        label = field.parent('div').children('label');

                    label.html(label.attr('data-title'));
                });
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));


/**
 *  @name Select Box Change
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'get-area';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current    = this.element,
                actionUrl  = current.data('get-area'),
                target     = $(current.data('target')),
                selectCity = current.data('text');

            current.on('change', function(){
                $.ajax({
                    type: 'GET',
                    url: actionUrl.replace('0', $(this).val()),
                    beforeSend: function(){},
                    success: function(response){
                        var data   = response.data;

                        target.find('option').remove();
                        target.append($('<option>', {value: '', text: selectCity}));
                        $.each(data, function(k, v) {
                            target.append($('<option>', {
                                value: v.id,
                                text: v.name
                            }));
                        });
                    }
                });
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Upload Cover
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'upload-cover';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current      = this.element,
                chooseCover  = $('.choose-cover-btn'),
                coverMedium  = $('.cover-medium'),
                coverBig     = $('.cover-big'),
                img          = chooseCover.children('img'),
                text         = chooseCover.children('b'),
                check        = chooseCover.children('i');

            current.on('submit', function(){
                return AIM.submit(this, {
                    onStart: function() {
                        img.show();
                        text.hide();
                        coverMedium.css({opacity: 0.5});
                        coverBig.css({opacity: 0.5});
                    },
                    onComplete: function(response){
                        var json     = $.parseJSON(response),
                            status   = json.status,
                            messages = json.messages;

                        img.hide();
                        text.show();

                        if (status === SETTING.AJAX_OK) {

                            $('.upload-cover-messages').hide();
                            check.show(200);
                            setTimeout(function() {
                                check.hide(200);
                            }, 2000);

                            coverMedium.attr('src', json.data['medium']);
                            coverBig.css('background-image', 'url(' + json.data['big'] + ')');
                        }

                        if (status === SETTING.AJAX_ERROR) {
                            $('.upload-cover-messages').show();
                            $('.upload-cover-messages').html(messages);
                        }

                        coverMedium.css({opacity: 1});
                        coverBig.css({opacity: 1});
                    }
                });
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Search Location
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'search-location';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current = this.element,
                url     = current.attr('action'),
                target  = $(current.data('search-location'));

            current.on('submit', function(){
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: current.serialize(),
                    beforeSend: function() {
                    },
                    success: function(response) {
                        var status = response.status,
                                data = response.data;

                        if (status === SETTING.AJAX_OK) {
                            target.find('li').remove();
                            $.each(data, function(k, v) {
                                var li = SETTING.LOCATION_LI;
                                li = li.replace('__VALUE', v.id);
                                li = li.replace('__NAME', v.name);
                                li = li.replace('__COUNT', v.count_store);
                                target.append(li);
                            });
                        }
                    }
                });

                return false;
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Select Location
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'select-location';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current     = this.element,
                url         = current.data('select-location'),
                fromTarget  = current.data('from-to-target').split('|'),
                from        = $(fromTarget[0]),
                target      = $(fromTarget[1]);

            from.on('click', 'li', function(){
                var id   = $(this).attr('data-value'),
                    name = $(this).find('.location-name').text();

                $.ajax({
                    type: 'GET',
                    url: url.replace('0', id),
                    beforeSend: function() {
                    },
                    success: function(response) {
                        if (response.status === SETTING.AJAX_OK) {
                            target.text(name);
                        }
                    }
                });

                return true;
            });
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Product Image
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'product-image';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current = this.element,
                loading = $('.product-img-loading');

            current.on('submit', function(){
                return AIM.submit(this, {
                    onStart: function() {
                        loading.show();
                    },
                    onComplete: function(response){
                        var json           = $.parseJSON(response),
                            status         = json.status,
                            messages       = json.messages,
                            productImg     = SETTING.PRODUCT_IMG,
                            productImgEdit = SETTING.PRODUCT_IMG_EDIT;

                        if (status === SETTING.AJAX_OK) {
                            productImg = productImg.replace('__SRC', json.data['thumb']);
                            $('.product-img-' + json.data['order']).css('border', 'solid 3px #000');
                            $('.product-img-' + json.data['order']).html(productImg + productImgEdit);
                            $('#product-image-' + json.data['order']).val(json.data['original']);
                            current.find('#current-image').val(json.data['original']);
                        }

                        if (status === SETTING.AJAX_ERROR) {
                            $('.add-product-image-error').html(messages);
                        }

                        loading.hide();
                    }
                });
            });

            return false;
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Save Product
 *  @description
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'save-product';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current = this.element,
                that    = this,
                labels  = current.data('save-product').split('|'),
                submit  = current.find(':submit'),
                img     = submit.children('img'),
                text    = submit.children('b'),
                check   = submit.children('i');

            current.on('submit', function(){
                $.ajax({
                    type: current.attr('method'),
                    url: current.attr('action'),
                    data: current.serialize(),
                    beforeSend: function(){
                        that.loading(true, img, text, check);
                    },
                    error: function(response) {
                        var messages = response.responseJSON.messages;

                        that.loading(false, img, text, check, false);
                        that.showFormLabels(labels, messages);
                    },
                    success: function(response){
                        var messages = response.messages,
                            data     = response.data;

                        that.loading(false, img, text, check, true);
                        current[0].reset();
                        $('.add-product-image').css('border', '3px dashed #d5d5d5');
                        $('.add-product-image').html('<i class="fa fa-plus"></i>');
                        $('.product-image-hidden').val('');
                        $('#add-product-modal').modal('hide');

                        that.showFormLabels(labels, messages);
                        that.refreshProduct(data);
                    }
                });

                return false;
            });
        },
        showFormLabels: function(labels, messages){
            var current = this.element;
            $.each(labels, function(k, v) {
                var field = current.find('[name^=' + v + ']'),
                    label = field.parent('div').find('label');

                if (messages.hasOwnProperty(v)) {
                    var errorHtml = '<span class="_fwfl _tr5">' + messages[v] + '</span>'
                    label.html(errorHtml);
                } else {
                    var originalText = label.attr('data-title');
                    label.html(originalText);
                }
            });
        },
        loading: function(start, img, text, check, success) {
            if (start) {
                img.show();
                text.hide();
            } else {
                img.hide();
                text.show();
                if (success) {
                    check.show(200);
                    setTimeout(function(){
                        check.hide(200);
                    }, 3000);
                }
            }
        },
        refreshProduct: function(data){
            var id = data.id,
                name = data.name,
                price = data.price,
                oldPrice = data.old_price,
                image = data.image,
                productElement = $('.' + id);

                productElement.find('.product-image').find('img').attr('src', image);
                productElement.find('.product-name').html(name);
                productElement.find('.product-price').find('b').html(price);
                productElement.find('.product-old-price').find('b').html(oldPrice);
        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Product Form Edit
 *  @description Display edit product modal with available data
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'edit-product-form';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current    = this.element,
                modal      = $('#add-product-modal'),
                form       = $('#save-product-form'),
                fields     = form.attr('data-save-product').split('|'),
                modalTitle = $('#addProductModalLabel');

            current.on('click', function(e){

                e.preventDefault();
                modalTitle.html(modalTitle.data('edit-title'));

                $.ajax({
                    type: 'GET',
                    url: current.attr('href'),
                    beforeSend: function(){},
                    error: function() {},
                    success: function(response) {
                        var data           = response.data,
                            productImg     = SETTING.PRODUCT_IMG,
                            productImgEdit = SETTING.PRODUCT_IMG_EDIT,
                            productRep     = '';

                        $.each(fields, function(k, v) {
                            form.find('[name^=' + v + ']').val(data[v]);
                        });

                        for (var i = 1; i <= 4; i++) {
                            if (data['images']['image_' + i] !== '') {
                                productRep = productImg.replace('__SRC', data['images']['image_' + i]);
                                $('.product-img-' + i).css('border', 'solid 3px #000');
                                $('.product-img-' + i).html(productRep + productImgEdit);
                            }
                        }

                        modal.modal('show');
                    }
                });
            });

        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Pin product
 *  @description User pin product
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'pin-product';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current   = this.element,
                productId = current.parents('.product').data('product-id');

            current.on('click', function(e){
                $.ajax({
                    type: 'POST',
                    url: SETTING.PIN_URI,
                    data:{_token: SETTING.CSRF_TOKEN, product_id: productId} ,
                    error: function(){},
                    success: function(response){
                        var data     = response.data,
                            isPinned = data.is_pinned,
                            totalPin = data.total_pin;
                        if (isPinned) {
                            current.addClass('pinned');
                        } else {
                            current.removeClass('pinned');
                        }
                        current.children('b').html(totalPin);
                    }
                });
            });

        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));

/**
 *  @name Delete product image
 *  @description User pin product
 *  @version 1.0
 *  @options
 *    option
 *  @events
 *    event
 *  @methods
 *    init
 *    publicMethod
 *    destroy
 */
;
(function($, window, undefined) {
    var pluginName = 'delete-product-image';

    function Plugin(element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn[pluginName].defaults, options);
        this.init();
    }

    Plugin.prototype = {
        init: function() {
            var current   = this.element;

            current.on('click', function(e){
                alert(':D');
            });

        },
        destroy: function() {
            $.removeData(this.element[0], pluginName);
        }
    };

    $.fn[pluginName] = function(options, params) {
        return this.each(function() {
            var instance = $.data(this, pluginName);
            if (!instance) {
                $.data(this, pluginName, new Plugin(this, options));
            } else if (instance[options]) {
                instance[options](params);
            } else {
                window.console && console.log(options ? options + ' method is not exists in ' + pluginName : pluginName + ' plugin has been initialized');
            }
        });
    };

    $.fn[pluginName].defaults = {
        option: 'value'
    };

    $(function() {
        $('[data-' + pluginName + ']')[pluginName]();
    });

}(jQuery, window));


$(document).ready(function(){

    /**
     * Bind event close dropdown of bootstap to
     * reset location dropdown to orginal
     */
    $('.location-dropdown').on('hide.bs.dropdown', function() {
        $('.search-location-form').find('[name^=location_keyword]').val('');
        $('.search-location-form').submit();
    });

    /**
     * Bind event close modal of bootstrap to
     * reset product modal to original
     */
    $('#add-product-modal').on('hidden.bs.modal', function (e) {
        resetProductModal();
    });

    /**
     * Close product modal when reset product form
     */
    $('.add-product-reset-btn').on('click', function(e){
        $('#add-product-modal').modal('hide');
    });

    /**
     * Reset save product modal when it close
     *
     * 1. Delete temporary product image
     * 2. Reset form
     * 3. Reset hidden input
     * 4. Clear image on DOM
     *
     * @returns void
     */
    function resetProductModal() {

        ajaxDeleteTempProductImg();

        $('.add-product-reset-btn').click();
        $('#save-product-form')[0].reset();
        $('#save-product-form').find('#product-id').val('');
        $('.product-image-hidden').val('');
        $('.add-product-image').css('border', '3px dashed #d5d5d5');
        $('.add-product-image').html('<span class="_fwfl _fh"><i class="fa fa-plus"></i></span>');
        var modalTitle = $('#addProductModalLabel');
        modalTitle.text(modalTitle.data('add-title'));
    }

    /**
     * Ajax delete temporary product image
     *
     * @returns void
     */
    function ajaxDeleteTempProductImg() {

        for (var i = 1; i<= 4; i++){
            if ($('#product-image-' + i).val() !== '') {
                $.ajax({
                    type: 'GET',
                    data: $('.product-image-hidden').serialize(),
                    url: $('#reset-product-image').val(),
                    beforeSend: function(){},
                    success: function(){}
                });

                break;
            }
        };
    }
});