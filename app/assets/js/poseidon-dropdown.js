!function(n){function t(o,e){var t=this;t.$el=o,t.options=e,void 0!==(o=t.$el.attr("data-dropdown"))&&!1!==o&&(t.$target=n("#"+o),t.$target.length&&(t.options.fog_class="pos-c-dropdown-fog",t.$el.on("click",n.proxy(t.clickEvent,t))))}t.prototype.$el=null,t.prototype.$fog=null,t.prototype.$target=null,t.prototype.options=null,t.prototype.clickEvent=function(o){o.preventDefault();o=this;o.$target.hasClass(o.options.opened)?(o.$target.removeClass(o.options.opened),o.$el.removeClass(o.options.opened),o.deleteFog()):(o.createFog(),o.$el.addClass(o.options.opened),o.$target.addClass(o.options.opened),o.scrollTo())},t.prototype.createFog=function(){var e=this,o=e.$el.parent();e.options.fog&&(e.$fog=n(document.createElement("div")),e.$fog.addClass(e.options.fog_class),o.append(e.$fog),e.$fog.addClass(e.options.opened),e.$fog.on("click",function(o){o.preventDefault(),e.$target.removeClass(e.options.opened),e.$el.removeClass(e.options.opened),e.deleteFog()}))},t.prototype.deleteFog=function(){var o=this;o.options.fog&&(o.$fog.removeClass(o.options.opened),setTimeout(function(){o.$fog.remove(),o.$fog=null},o.options.delay))},t.prototype.scrollTo=function(){var o=this,e=o.$el.closest(o.options.container),t=o.$el.parent();e.length&&setTimeout(function(){e.animate({scrollTop:-1*(t.offset().top-e.offset().top+e.scrollTop())})},o.options.delay)};var e={init:function(o){if(!this.length)return!1;var e={container:"ul.open",delay:200,fog:!0,opened:"opened"};return this.each(function(){o&&n.extend(e,o),new t(n(this),e)})}};n.fn.poseidonDropdown=function(o){return e[o]?e[o].apply(this,Array.prototype.slice.call(arguments,1)):"object"!=typeof o&&o?(n.error("Method "+o+" does not exist on poseidonDropdown"),!1):e.init.apply(this,arguments)}}(window.jQuery);