!function(c,a){var p,t,e,s;c&&(p=c.Section.prototype.attachEvents,t=c.Section.prototype.embed,e=c.Section.prototype.isContextuallyActive,s="poseidon-toggle-section",c.Section=c.Section.extend({attachEvents:function(){var e,i,t,n,o;s!==this.params.type?p.call(this):(n=(e=this).container,i=n.find("input.pos-toggle-checkbox"),t=this.params.id+"-status",n.find(".accordion-section-title, .customize-section-back").on("click keydown",function(t){c.utils.isKeydownButNotEnterEvent(t)||(t.preventDefault(),!0===i.prop("checked")&&(e.expanded()?e.collapse():e.expand()))}),n=new c.Setting(this.params.id+"-setting",!0===i.prop("checked")),(o=new c.Control(t,{active:!0,label:this.params.title,priority:0,section:this.params.id,setting:n,type:"checkbox",value:i.val()})).active.validate=function(){return!0===i.prop("checked")},c.control.add(o),o.active.set(!0),o.container.hide(),i.on("click",function(){var t=a(this),e=t.val();t.val(t.prop("checked")?"on":"off"),o.container.find("input").prop("checked",!0===t.prop("checked")),o.container.find("input").val(t.val()),o.setting.set(!0===t.prop("checked")),!0===t.prop("checked")&&"off"===e&&c.state("saved").set(!0)}))},embed:function(){t.call(this)},isContextuallyActive:function(){return s===this.params.type||e.call(this)}}))}(window.wp.customize,window.jQuery);