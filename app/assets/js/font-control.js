!function(n){n(document).ready(function(){var e=n("main.pos-c-body.font-body");e.length&&n.each(e,function(e,t){const i=n(t),l=i.find("input"),p=i.find("select"),d=n("#style-"+i.attr("id"));p.on("change",function(e){var t=p.find(":selected"),n=(t.parent("optgroup").attr("data-type"),t.parent("optgroup").attr("data-url")),a=i.find("link");let o=t.text(),r=t.val();a.length&&a.remove(),""!==o&&(r=""==r?'-apple-system, BlinkMacSystemFont, "Helvetica Neue", sans-serif':r,""!==n&&i.append('<link href="'+n.replace("_FAMILY_",r)+'" rel="stylesheet" />'),o=-1<o.indexOf(" ")?"'"+o+"'":o,d.html(":root{"+l.prop("value")+":"+o+"}"))})})})}(window.jQuery);