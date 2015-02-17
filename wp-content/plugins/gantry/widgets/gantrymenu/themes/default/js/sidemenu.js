/*!
 * @version   $Id: sidemenu.js 14307 2013-10-08 15:44:26Z djamil $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

((function(){

    var isTouch = (function(){ return !!('ontouchstart' in window); })(),
        isiPad = navigator.userAgent.match(/iPad/i) != null;

    var SideMenu = this.SideMenu = new Class({
        initialize: function(){
            this.build();
            this.mediaQuery(RokMediaQueries.getQuery());
            this.attachEvents();
            this.mediaQuery(RokMediaQueries.getQuery());
        },

        build: function(){
            if (this.toggler) return this.toggler;
            this.toggler = new Element('div.gf-menu-toggle').inject(document.body);
            this.container = document.getElement('.gf-menu-device-container');
            this.wrapper = new Element('div.gf-menu-device-container-wrapper').inject(this.container);

            this.container = new Element('div.gf-menu-device-wrapper-sidemenu').wraps(this.container);

            this.menu = document.getElement('.gf-menu');
            this.originalPosition = this.menu.getParent();
            this.open = false;

            (3).times(function(){
                new Element('span.icon-bar').inject(this.toggler);
            }, this);

            this.container.inject(document.body);

            return this.toggler;
        },

        attachEvents: function(){
            var click = this.toggler.retrieve('roknavmenu:click', function(event){
                    event.preventDefault().stopPropagation();
                    this.toggle.call(this, event, this.toggler);
                }.bind(this));

            this.toggler.addEvent('click', click);

            this.touchEvents = {
                click: function(event){ event.preventDefault(); },
                touchstart: function(){ this.store('touched', true); },
                touchcancel: function(event){ event.preventDefault(); this.store('touched', false); },
                touchmove: function(){ this.store('touched', false); },
                touchend: function(event){
                    console.log(this);
                    if (!this.retrieve('touched', false)) event.preventDefault();
                    else location.href = this.get('href');
                }
            };

            try {
                RokMediaQueries.on('(max-width: 767px)', this.mediaQuery.bind(this));
                RokMediaQueries.on('(min-width: 768px)', this.mediaQuery.bind(this));
            }
            catch(error) { if (typeof console != 'undefined') console.error('Error [Responsive Menu] while trying to add a RokMediaQuery "match" event', error); }
        },

        attachTouchEvents: function(){
            if (isTouch && !isiPad){
                $$('.responsive-type-panel .item').removeEvents().forEach(function(item){
                    Object.forEach(this.touchEvents, function(fn, evt){
                        item.addEventListener(evt, fn, false);
                    });
                }, this);
            }
        },

        detachTouchEvents: function(){
            if (isTouch && !isiPad){
                $$('.responsive-type-panel .item').forEach(function(item){
                    Object.forEach(this.touchEvents, function(fn, evt){
                        item.removeEventListener(evt, fn, false);
                    });
                }, this);
            }
        },

        toggle: function(event, toggler){
            this.container[!this.open ? 'addClass' : 'removeClass']('gf-sidemenu-size-left');
            document.body[!this.open ? 'addClass' : 'removeClass']('gf-sidemenu-size-marginleft');
            toggler[!this.open ? 'addClass' : 'removeClass']('active');

            this.open = !this.open;
            /*var slide = toggler.retrieve('roknavmenu:slidehor');

            toggler[slide.open ? 'removeClass' : 'addClass']('active');
            slide[slide.open ? 'slideOut' : 'slideIn']();*/
        },

        mediaQuery: function(query){
            var menu = this.menu,
                container = this.wrapper;
            //slide = this.toggler.retrieve('roknavmenu:slidehor');

            if (!menu && !container) return;
            if (query != '(min-width: 768px)' && query != '(max-width: 767px)') return;

            if (query == '(min-width: 768px)'){
                menu.inject(this.originalPosition);
                this.detachTouchEvents();
                /*this.slide.wrapper.setStyle('display', 'none');*/
                this.toggler.setStyle('display', 'none');
            } else {
                menu.inject(container);
                this.attachTouchEvents();
                /*this.slide.wrapper.setStyle('display', 'inherit');*/
                this.toggler.setStyle('display', 'block');
            }

            //slide.hide();
            this.toggler.removeClass('active');
        }
    });

    window.addEvent('domready', function(){
        this.RokNavMenu = new SideMenu();
    });

})());
