/**
 * @version $Id: gantry-widgets.js 60855 2014-05-15 21:42:30Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

var GantryWidgets = {
    init: function() {
        GantryWidgets.dropdown();
        GantryWidgets.id = document.id('override_id').get('value').toInt();
        GantryWidgets.name = document.id('override_name').get('value');
        GantryWidgets.newPageHook();
        if (GantryWidgets.id != -1) {
            GantryWidgets.overrideWP();
            GantryWidgets.overrideCheckboxes();
            GantryWidgets.increase();
        } else {
            wpWidgets.save = GantryWidgets.wpDefaultSave;
            //function( widget, del, animate, order ){ wpWidgets.saveCopy( widget, del, animate, order ); GantryWidgets.refreshMultiselect(widget); };
            //wpWidgets.saveOrder = function( sidebarId ){ wpWidgets.saveOrderCopy( sidebarId ); GantryWidgets.refreshMultiselect('#widgets-right'); };
        }
        GantryWidgets.refreshMultiselect('#widgets-right');
        GantryWidgets.notices();

        new Tips('.rok-tips', {title: 'data-tips'});
        (function($){
            $('a.widget-action').live('click', function(){
                new Tips('.rok-tips', {title: 'data-tips'});
            });
        })(jQuery);
    },

    wpDefaultSave: function( widget, del, animate, order ) {
        (function($){
            var sidebarId = widget.closest('div.widgets-sortables').attr('id'),
                data = widget.find('form').serialize(), a;

            widget = $(widget);
            $('.spinner', widget).show();

            a = {
                action: 'save-widget',
                savewidgets: $('#_wpnonce_widgets').val(),
                sidebar: sidebarId
            };

            if ( del ) {
                a.delete_widget = 1;
            }

            GantryWidgets.refreshMultiselect(widget);

            data += '&' + $.param(a);

            $.post( ajaxurl, data, function(r) {
                var id;

                if ( del ) {
                    if ( ! $('input.widget_number', widget).val() ) {
                        id = $('input.widget-id', widget).val();
                        $('#available-widgets').find('input.widget-id').each(function(){
                            if ( $(this).val() === id ) {
                                $(this).closest('div.widget').show();
                            }
                        });
                    }

                    if ( animate ) {
                        order = 0;
                        widget.slideUp('fast', function(){
                            $(this).remove();
                            wpWidgets.saveOrder();
                        });
                    } else {
                        widget.remove();
                    }
                } else {
                    $('.spinner').hide();
                    if ( r && r.length > 2 ) {
                        $( 'div.widget-content', widget ).html( r );
                        wpWidgets.appendTitle( widget );
                        $( document ).trigger( 'widget-updated', [ widget ] );
                    }
                }
                if ( order ) {
                    wpWidgets.saveOrder();
                }
                GantryWidgets.refreshMultiselect(widget);
            });
        })(jQuery);
    },

    dropdown: function() {
        var inside = document.id('overrides-inside'), first = document.id('overrides-first'), delay = null;
        var slide = new Fx.Slide('overrides-inside', {
            duration: 100,
            onStart: function() {
                var width = document.id('overrides-actions').getSize().x - 4;
                inside.setStyle('width', width);
                this.wrapper.setStyle('width', width + 4);
            },
            onComplete: function() {
                if (inside.getStyle('margin-top').toInt() !== 0) first.removeClass('slide-down');
            }
        }).hide();
        inside.setStyle('display', 'block');

        var enterFunction = function() {
            if (inside.hasClass('slidedown')) {
                slide.slideIn();
                first.addClass('slide-down');
            }
        };

        var leaveFunction = function() {
            if (inside.hasClass('slideup')) {
                slide.slideOut();
            }
        };


        document.getElements('#overrides-inside, #overrides-toggle').addEvents({
            'mouseenter': function() {
                $clear(delay);
                inside.removeClass('slideup').addClass('slidedown');
                first.addClass('slide-down');
                enterFunction();
            },
            'mouseleave': function() {
                $clear(delay);
                inside.removeClass('slidedown').addClass('slideup');
                delay = leaveFunction.delay(300);
            }
        });

        GantryWidgets.dropdownActions();

    },
    dropdownActions: function() {
        var dropdown = document.id('overrides-actions'), tools = document.id('overrides-toolbar'), first = document.id('overrides-first');
        var toggle = document.id('overrides-toggle');
        if (tools) {
            var add = tools.getElement('.button-add'), del = tools.getElement('.button-del'), edit = tools.getElement('.button-edit');
            if (edit) {
                edit.addEvent('click', function() {
                    if (first.getElement('input')) {
                        first.getElement('input').empty().dispose();
                        toggle.removeClass('hidden');
                        return;
                    }
                    toggle.addClass('hidden');
                    var input = new Element('input', {'type': 'text', 'class': 'add-edit-input', 'value': first.get('text').clean().trim()});
                    input.addEvent('keydown', function(e) {
                        if (e.key == 'esc') {
                            this.empty().dispose();
                            toggle.removeClass('hidden');
                        }
                        else if (e.key == 'enter') {
                            e.stop();
                            var list = document.id('overrides-inside').getElements('a');
                            var index = list.get('text').indexOf(this.value);
                            if (index != -1) {
                                this.highlight('#ff4b4b', '#fff');
                                return;
                            }

                            var value = this.value;
                            new Request.HTML({
                                url: AdminURI,
                                onRequest: function() {
                                    var ajaxloading = document.id('overrides-toolbar').getElement('.ajax-loading');
                                    if (ajaxloading) ajaxloading.setStyles({'display': 'block', 'visibility': 'visible'});
                                },
                                onSuccess: function() {
                                    var ajaxloading = document.id('overrides-toolbar').getElement('.ajax-loading');
                                    if (ajaxloading) ajaxloading.setStyles({'display': 'none', 'visibility': 'hidden'});

                                    index = list.get('text').indexOf(first.get('text').clean().trim());
                                    if (index != -1) list[index].set('text', value);
                                    input.empty().dispose();
                                    toggle.removeClass('hidden');
                                    first.getElement('a').set('text', value);
                                }
                            }).post({
                                'action': 'gantry_admin',
                                'model': 'widgets',
                                'gantry_action': 'save-info',
                                'savewidgets': document.id('_wpnonce_widgets').get('value'),
                                'override_id': document.id('override_id').get('value'),
                                'override_name': value
                            });
                        }
                    });
                    input.inject(first, 'top').focus();
                });
            }
        }
    },
    newPageHook: function() {
        var add = document.getElements('.button-add')[0];
        if (add) {
            var ajax = new Request({
                url: AdminURI,
                onRequest: function() {
                    var ajaxloading = document.id('overrides-toolbar').getElement('.ajax-loading');
                    if (ajaxloading) ajaxloading.setStyles({'display': 'block', 'visibility': 'visible'});
                },
                onSuccess: function(response) {
                    var url = response.clean().trim();
                    if (url.length) window.location = url;
                }
            });
            add.addEvent('click', function(e) {
                e.stop();
                ajax.post({
                    'action': 'gantry_admin',
                    'model': 'widgets',
                    'gantry_action': 'create-new',
                    'savewidgets': document.id('_wpnonce_widgets').get('value')
                });
            });
        }
    },
    increase: function() {
        document.getElements('input.multi_number').each(function(input) {
            var value = input.get('value').toInt() || false;
            if ($chk(value) && value < GantryWidgets.id * 10000) input.set('value', (GantryWidgets.id * 10000) + value);
        });

        document.getElements('input[name=widget-id]').filter(function(item) {
            return !item.get('value').contains("__i__");
        }).each(function(item) {
            var number = item.getNext('input[name=widget_number]');
            var id_base = item.getNext('input[name=id_base]').get('value');
            var widget = item.getParent('.widget');
            var value = number.get('value').toInt();
            if (value < GantryWidgets.id * 10000){
                var newValue = (GantryWidgets.id * 10000) + value + 500;
                var newId = widget.get('id').replace(/(\d+)$/, newValue);
                widget.set('id', newId);
                number.set('value', newValue);
                var split = newId.split('_').slice(1);
                item.set('value', split.join("_"));
                item.getParent('form').getElements('.widget-content input, .widget-content label, .widget-content select, .widget-content textarea').each(function(field) {
                    var name = field.get('name'), id = field.get('id'), forr = field.get('for');
                    if (id) field.set('id', id.replace(/\d+/, newValue));
                    if (name) field.set('name', name.replace(/\d+/, newValue));
                    if (forr) field.set('for', forr.replace(/\d+/, newValue));
                });
            }

        });
    },

    hookClick: function(topbar){
        (function($){
            if (!topbar) topbar = $('#widgets-right').children('.widgets-holder-wrap').children('.sidebar-name');
            else topbar = $(topbar);

            topbar.click(function(){
                var c = $(this).siblings('.widgets-sortables'), p = $(this).parent('.widgets-holder-wrap') || $(this).parent();
                if ( !p.hasClass('closed') ) {
                    c.sortable('disable');
                    p.addClass('closed');
                } else {
                    p.removeClass('closed');
                    c.sortable('enable').sortable('refresh');
                }
            });
        })(jQuery);
    },

    unHookClick: function(topbar){
        (function($){
            if (!topbar) topbar = $('#widgets-right').children('.widgets-holder-wrap').children('.sidebar-name');
            else topbar = $(topbar);

            topbar.unbind('click');
        })(jQuery);
    },

    overrideCheckboxes: function() {
        GantryWidgets.unHookClick();
        document.getElements('.override-checkbox').each(function(check) {
            var h3 = check.getParent('h3');
            var wrapper = check.getParent('.widgets-holder-wrap');
            var siblings = wrapper.getElements('.widgets-sortables').filter(function(sibling) {
                return sibling != check.getParent('.widgets-sortables');
            });
            var arrow = check.getParent('.sidebar-name').getElement('.sidebar-name-arrow');
            check.inject(h3, 'before');
            document.getElements(h3, arrow).addEvent('click', function(e) {
                if (!check.get('checked') && e) e.stop();
                else if (!check.get('checked') && wrapper.hasClass('closed')) {
                    (function($) {
                        $(siblings).sortable('disable');
                        wrapper.addClass.delay(1, wrapper, 'closed');
                    })(jQuery);
                } else {
                    (function($) {
                        if (!wrapper.hasClass('closed') ) {
                            $(siblings).sortable('disable');
                            wrapper.addClass.delay(1, wrapper, 'closed');
                        } else {
                            $(siblings).sortable('enable').sortable('refresh');
                            wrapper.removeClass.delay(1, wrapper, 'closed');
                        }
                        $(siblings).sortable('refresh');
                    })(jQuery);
                }
            });
            check.addEvent('click', function() {
                var massPost = [];
                var widgets = wrapper.getElement('.widgets-sortables').getChildren().filter(function(widget) {
                    return widget.get('id');
                });
                if (this.checked) {
                    arrow.setStyle('display', 'block');
                    GantryWidgets.hookClick(arrow.getParent('.sidebar-name'));
                    widgets.each(function(widget) {
                        (function($) {
                            var w = $(widget) ;
                            massPost.push(wpWidgets.save(w, 0, 0, 1, true)._parseQueryString());
                        })(jQuery);
                    });
                    if (!widgets.length) wpWidgets.saveOrder();
                }
                else {
                    arrow.setStyle('display', 'none');
                    GantryWidgets.unHookClick(arrow.getParent('.sidebar-name'));
                    widgets.each(function(widget) {
                        (function($) {
                            var w = $(widget) ;
                            massPost.push(wpWidgets.save(w, 1, 0, 1, true)._parseQueryString());
                        })(jQuery);
                    });
                    if (!widgets.length) wpWidgets.saveOrder();
                }

                if (massPost.length) {
                    var data = JSON.encode(massPost);
                    var post = {
                        'action': 'gantry_admin',
                        'model': 'widgets',
                        'gantry_action': 'widgets-mass-actions',
                        'savewidgets': document.id('_wpnonce_widgets').get('value'),
                        'override_id': GantryWidgets.id,
                        'override_name': document.id('override_name').get('value'),
                        'data': data
                    };

                    if (!this.checked) post['delete_widgets'] = 1;

                    new Request({
                        url: AdminURI,
                        onSuccess: function(response) {
                            var id;
                            (function($) {
                                wpWidgets.saveOrder();
                            })(jQuery);
                        }
                    }).post(post);
                }

                h3.fireEvent('click');

            });

            if (!check.get('checked')) {
                if (check.checked) arrow.setStyle('display', 'block');
                else arrow.setStyle('display', 'none');

                h3.fireEvent('click');
            } else {
                GantryWidgets.hookClick(arrow.getParent('.sidebar-name'));
            }
        });
    },

    refreshMultiselect: function(widget){
        if (!jQuery.fn.multiselect) return;
        if (!widget) widget = jQuery(document);
        jQuery('[data-select-variations]', widget).multiselect({
            maxHeight: 260,
            enableFiltering: true,
            filterBehavior: 'both',
            enableCaseInsensitiveFiltering: true
        });
    },

    overrideWP: function() {
        GantryWidgets.WPsave();
        GantryWidgets.WPsaveOrder();
    },

    WPsave: function() {
        wpWidgets.save = function(widget, del, animate, order, massSave) {
            new Tips('.rok-tips', {title: 'data-tips'});

            (function($) {
                sb = $(widget).closest('div.widgets-sortables').attr('id');
                data = $(widget).find('form').serialize();
            })(jQuery);

            var feedback_spinner = document.getElements(widget[0]).getParent('.widgets-holder-wrap').getElement('.ajax-feedback'),
                regular_spinner = document.getElements(widget[0]).getParent('.widgets-holder-wrap').getElement('.spinner');
            if (feedback_spinner.length > 1 || feedback_spinner[0] != null) feedback_spinner.setStyles({'visibility': 'visible', display: 'inline-block'});
            if (regular_spinner.length > 1 || regular_spinner[0] != null) regular_spinner.setStyles({'visibility': 'visible', display: 'inline-block'});


            feedback_spinner = document.getElements(widget[0]).getElement('.ajax-feedback');
            regular_spinner = document.getElements(widget[0]).getElement('.spinner');
            if (feedback_spinner.length > 1 || feedback_spinner[0] != null) feedback_spinner.setStyles({'visibility': 'visible', display: 'inline-block'});
            if (regular_spinner.length > 1 || regular_spinner[0] != null) regular_spinner.setStyles({'visibility': 'visible', display: 'inline-block'});

            var checks = document.getElements('.override-checkbox').filter(function(check) {
                return check.checked;
            }).get('id');

            GantryWidgets.refreshMultiselect(widget);

            post = {
                'action': 'gantry_admin',
                'model': 'widgets',
                'gantry_action': 'widgets-save',
                'savewidgets': document.id('_wpnonce_widgets').get('value'),
                'override_id': GantryWidgets.id,
                'override_name': document.id('override_name').get('value'),
                'overridden_sidebars': checks.join(','),
                'sidebar': sb
            };


            if (del) post['delete_widget'] = 1;

            data += '&' + (function($) { return $.param(post); })(jQuery);

            if (massSave){
                GantryWidgets.refreshMultiselect(widget);
                return data;
            } else {
                return new Request({
                    url: AdminURI,
                    onSuccess: function(response) {
                        var id;
                        (function($) {
                            if (del) {
                                if (!$('input.widget_number', widget).val()) {
                                    id = $('input.widget-id', widget).val();
                                    $('#available-widgets').find('input.widget-id').each(function(){
                                        if ($(this).val() == id)
                                            $(this).closest('div.widget').show();
                                    });
                                }

                                if (animate) {
                                    order = 0;
                                    widget.slideUp('fast', function() {
                                        $(this).remove();
                                        wpWidgets.saveOrder();
                                    });
                                } else {
                                    widget.remove();
                                    if (wpWidgets.resize) wpWidgets.resize();
                                }
                            } else {
                                $('.ajax-feedback, .spinner').css('visibility', 'hidden');
                                if (response && response.length > 2) {
                                    $('div.widget-content', widget).html(response);
                                    wpWidgets.appendTitle(widget);
                                    if (wpWidgets.fixLabels) wpWidgets.fixLabels(widget);
                                }
                            }
                            if (order) wpWidgets.saveOrder();
                        })(jQuery);

                        window.fireEvent('gantry-widgets', [this, data]);
                        GantryWidgets.refreshMultiselect(widget);
                    }
                }).post(data._parseQueryString());
            }
        };
    },

    WPsaveOrder: function() {
        wpWidgets.saveOrder = function(sb, del) {
            new Tips('.rok-tips', {title: 'data-tips'});

            //if (sb) document.id(sb).closest('div.widgets-holder-wrap').find('img.ajax-feedback').css('visibility', 'visible');
            var checks = document.getElements('.override-checkbox').filter(function(check) {
                return check.checked;
            }).get('id');
            var post = {
                'action': 'gantry_admin',
                'model': 'widgets',
                'gantry_action': 'widgets-order',
                'savewidgets': document.id('_wpnonce_widgets').get('value'),
                'override_id': GantryWidgets.id,
                'override_name': document.id('override_name').get('value'),
                'overridden_sidebars': checks.join(','),
                'sidebars': []
            };

            if (del) post['delete_widget'] = 1;

            document.getElements('div.widgets-sortables').each(function(item) {
                var children = item.getChildren().filter(function(div) {
                    return div.get('id');
                });
                post['sidebars['+item.get('id')+']'] = children.get('id').join(',');
            });

            new Request({
                url: AdminURI,
                onRequest: function() {
                    if (sb){
                        (
                            document.id(sb).getParent('.widgets-holder-wrap').getElement('img.ajax-feedback') ||
                            document.id(sb).getParent('.widgets-holder-wrap').getElement('.spinner')
                        ).setStyles({'visibility': 'visible', display: 'block'});
                    }
                },
                onSuccess: function() {
                    var spinners = document.getElements('img.ajax-feedback, .spinner');
                    spinners.setStyles({'visibility': 'hidden', display: 'none'});
                    if (wpWidgets.resize) wpWidgets.resize();
                }
            }).post(post);
        };
    },
    notices: function() {
        var notices = document.getElements('.gantry-notice');
        if (notices.length) {
            notices.each(function(notice) {
                var close = notice.getElement('.close');
                if (close) {
                    var fx = new Fx.Tween(notice, {duration: 200, link: 'ignore', onComplete: function() {
                        if (document.id(notice)) notice.dispose();
                    }});
                    close.addEvent('click', fx.start.pass(['opacity', 0], fx));
                }
            });
        }

        var deletOverride = document.getElements('.overrides-button.button-del');
        deletOverride.addEvent('click', function(e) {
            var del = confirm(GantryLang['are_you_sure']);
            if (!del) e.stop();
        });
    }
};

String.implement({

    _parseQueryString: function(){
        var vars = this.split(/[&;]/), res = {};
        if (vars.length) vars.each(function(val){
            var index = val.indexOf('='),
                keys = index < 0 ? [''] : val.substr(0, index).match(/[^\]\[]+/g),
                value = val.substr(index + 1).replace(/\+/g, " "),
                obj = res;

            value = decodeURIComponent(value);
            keys.each(function(key, i){
                key = decodeURIComponent(key);
                var current = obj[key];
                if(i < keys.length - 1)
                    obj = obj[key] = current || {};
                else if($type(current) == 'array')
                    current.push(value);
                else
                    obj[key] = $defined(current) ? [current, value] : value;
            });
        });

        return res;
    }
});

window.addEvent('domready', GantryWidgets.init);
