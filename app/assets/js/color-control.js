!function(e){e(document).ready(function(){var o=e("main.pos-c-body.color-body");o.length&&e.each(o,function(o,n){var n=e(n),i=n.find("[color-picker]"),r=JSON.parse(i.attr("color-picker"));r.container="#"+n.attr("id")+"-aside",r.inline=!0,r.defaultColor=i.find("input").attr("value"),i.poseidonColorPicker(r)})})}(window.jQuery);