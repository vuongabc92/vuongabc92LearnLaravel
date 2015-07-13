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
                fields     = current.attr('data-required'),
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
                target     = $(current.attr('data-event-trigger')),
                events     = current.attr('data-event'),
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
                labels  = current.attr('data-ajax-form').split('|'),
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

                        that.showFormLabels(current, labels, messages);
                    }
                });

                return false;
            });
        },
        showFormLabels: function(currents, labels, messages){
            var current = this.element;
            $.each(labels, function(k, v) {
                var field  = current.find('[name^=' + v + ']'),
                    parent = field.parent('div'),
                    label  = parent.children('label');

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
                avatar128    = $('.avatar-128'),
                avatar64     = $('.avatar-64'),
                avatar40     = $('.avatar-40'),
                img          = chooseAvatar.children('img'),
                text         = chooseAvatar.children('b'),
                check        = chooseAvatar.children('i');

            current.on('submit', function(){
                return AIM.submit(this, {
                    onStart: function() {
                        img.show();
                        text.hide();
                        avatar128.css({opacity:0.5});
                        avatar64.css({opacity:0.5});
                        avatar40.css({opacity:0.5});
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
                            var image128 = json.data['128'],
                                image64  = json.data['64'],
                                image40  = json.data['40'];
                        
                            check.show(200);
                            setTimeout(function() {
                                check.hide(200);
                            }, 3000);
                        }

                        if (status === SETTING.AJAX_ERROR) {
                            $('.upload-avatar-messages').show();
                            $('.upload-avatar-messages').html(messages);
                        }
                        
                        avatar128.attr('src', image128).css({opacity:1});
                        avatar64.attr('src', image64).css({opacity:1});
                        avatar40.attr('src', image40).css({opacity:1});

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
