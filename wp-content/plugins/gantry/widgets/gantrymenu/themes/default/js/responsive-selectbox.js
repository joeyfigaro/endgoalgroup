/*!
 * @version   $Id: responsive-selectbox.js 8885 2013-03-28 17:38:51Z djamil $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

((function () {

    var ResponsiveDropdown = {
        cache: [],
        build: function() {
            var menu = document.getElement('ul.gf-menu'),
                container = document.getElement('.gf-menu-device-container');

            if (!menu || !container || menu.retrieve('roknavmenu:dropdown:select')) return;

            menu.store('roknavmenu:dropdown:select', true);

            var select = new Element('select').inject(container, 'top');

            ResponsiveDropdown.getChildren(menu, select, 0);

            //menu.setStyle('display', 'none');

            ResponsiveDropdown.attachEvent(select);
        },

        getChildren: function(menu, select, depth) {
            var children = menu.getChildren().flatten(),
                submenu, subitem, item, group, cache,
                itemtext, subtext,
                option, isActive;

            for (var i = 0, l = children.length; i < l; i++) {
                item = children[i].getElement('.item');
                if (!item) continue;

                cache = children[i].className.replace(/\s/g, '-');

                if (ResponsiveDropdown.cache.contains(cache)) continue;
                ResponsiveDropdown.cache.push(cache);

                subitem = item.getElement('em') || item.getElement('i');
                submenu = children[i].getElement('ul');
                group = children[i].getElement('ol');
                isActive = children[i].hasClass('active');

                itemtext = item.get('text').clean();
                subtext = subitem ? subitem.get('text').clean() : '';

                if (itemtext.length != subtext.length) itemtext = itemtext.substr(0, (itemtext.length - 1) - (subtext.length - 1));

                option = new Element('option', {value:item.get('href'), text:'-'.repeat(depth) + ' ' + itemtext}).inject(select);


                if (isActive) option.set('selected', 'selected');


                if (submenu){
                    if (submenu.getParent('.column')){
                        ResponsiveDropdown.getChildren(submenu.getParent('.dropdown').getElements(' > .column > ul'), select, depth + 1);
                    } else ResponsiveDropdown.getChildren(submenu, select, depth + 1);
                }

                if (group){
                    ResponsiveDropdown.getChildren(group, select, depth + 1);
                }
            }
        },

        attachEvent: function(select) {
            select.addEvent('change', function () {
                window.location.href = this.value;
            });
        }
    };

    window.addEvent('domready', ResponsiveDropdown.build);

    if (typeof ResponsiveMenu != 'undefined') {
        ResponsiveMenu.implement({
            mediaQuery:function (query) {
                var menu = document.getElement('.gf-menu'),
                    container = document.getElement('.gf-menu-device-container'),
                    slide = this.toggler.retrieve('roknavmenu:slide');

                if (!menu && !container) return;

                if (query == '(min-width: 768px)') {
                    menu.setStyle('display', 'inherit');
                    this.slide.wrapper.setStyle('display', 'none');
                    this.toggler.setStyle('display', 'none');
                } else {
                    menu.setStyle('display', 'none');
                    this.slide.wrapper.setStyle('display', 'inherit');
                    this.toggler.setStyle('display', 'block');
                }

                slide.hide();
                this.toggler.removeClass('active');
            }
        });
    }

})());
