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
                    success: function(response){
                        var status   = response.status,
                            messages = response.messages;

                        if (status === SETTING.AJAX_ERROR) {
                            that.loading(false, img, text, check, false);
                        }
                        if (status === SETTING.AJAX_OK) {
                            that.loading(false, img, text, check, true);
                        }

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
                formElement = form.attr('data-ajax-form').split('|');

            current.on('click', function(){
                $.each(formElement, function(k, v) {
                    var field  = form.find('[name^=' + v + ']'),
                        label  = field.parent('div').children('label');

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
                        var status = response.status,
                            data   = response.data;

                        if (status === SETTING.AJAX_OK) {
                            target.find('option').remove();
                            target.append($('<option>', {value: '', text : selectCity}));
                            $.each(data, function (k, v) {
                                target.append($('<option>', {
                                    value: v.id,
                                    text : v.name
                                }));
                            });
                        }
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



$(document).ready(function(){

    //Reset list location when location dropdown is closed
    $('.location-dropdown').on('hide.bs.dropdown', function() {
        $('.search-location-form').find('[name^=location_keyword]').val('');
        $('.search-location-form').submit();
    });
    //End

});