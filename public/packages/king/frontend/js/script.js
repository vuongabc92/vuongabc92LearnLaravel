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
            var current = this.element,
                    listId = current.attr('data-required'),
                    idArr = listId.split('|');

            current.on('submit', function() {
                var empty = false;
                $.each(idArr, function(k, v) {
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
                event      = current.attr('data-event'),
                eventSplit = event.split('|'),
                eventLast  = eventSplit[0],
                eventFirst = eventSplit[1];
            
            current.on(eventFirst, function(){
                switch(eventLast) {
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