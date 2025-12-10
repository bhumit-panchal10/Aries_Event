// !function () { "use strict"; var t, a, e; sessionStorage.getItem("defaultAttribute") && (t = document.documentElement.attributes, a = {}, Object.entries(t).forEach(function (t) { var e; t[1] && t[1].nodeName && "undefined" != t[1].nodeName && (e = t[1].nodeName, a[e] = t[1].nodeValue) }), sessionStorage.getItem("defaultAttribute") !== JSON.stringify(a) ? (sessionStorage.clear(), window.location.reload()) : ((e = {})["data-layout"] = sessionStorage.getItem("data-layout"), e["data-sidebar-size"] = sessionStorage.getItem("data-sidebar-size"), e["data-layout-mode"] = sessionStorage.getItem("data-layout-mode"), e["data-layout-width"] = sessionStorage.getItem("data-layout-width"), e["data-sidebar"] = sessionStorage.getItem("data-sidebar"), e["data-sidebar-image"] = sessionStorage.getItem("data-sidebar-image"), e["data-layout-direction"] = sessionStorage.getItem("data-layout-direction"), e["data-layout-position"] = sessionStorage.getItem("data-layout-position"), e["data-layout-style"] = sessionStorage.getItem("data-layout-style"), e["data-topbar"] = sessionStorage.getItem("data-topbar"), e["data-preloader"] = sessionStorage.getItem("data-preloader"), e["data-body-image"] = sessionStorage.getItem("data-body-image"), Object.keys(e).forEach(function (t) { e[t] && document.documentElement.setAttribute(t, e[t]) }))) }();


/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Version: 2.2.0
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Layout Js File
*/

(function () {

    'use strict';

    if (sessionStorage.getItem('defaultAttribute')) {

        var attributesValue = document.documentElement.attributes;
        var CurrentLayoutAttributes = {};
        Object.entries(attributesValue).forEach(function (key) {
            if (key[1] && key[1].nodeName && key[1].nodeName != "undefined") {
                var nodeKey = key[1].nodeName;
                CurrentLayoutAttributes[nodeKey] = key[1].nodeValue;
            }
        });
        if (sessionStorage.getItem('defaultAttribute') !== JSON.stringify(CurrentLayoutAttributes)) {
            sessionStorage.clear();
            window.location.reload();
        } else {
            var isLayoutAttributes = {};
            isLayoutAttributes['data-layout'] = sessionStorage.getItem('data-layout');
            isLayoutAttributes['data-sidebar-size'] = sessionStorage.getItem('data-sidebar-size');
            isLayoutAttributes['data-layout-mode'] = sessionStorage.getItem('data-layout-mode');
            isLayoutAttributes['data-layout-width'] = sessionStorage.getItem('data-layout-width');
            isLayoutAttributes['data-sidebar'] = sessionStorage.getItem('data-sidebar');
            isLayoutAttributes['data-sidebar-image'] = sessionStorage.getItem('data-sidebar-image');
            isLayoutAttributes['data-layout-direction'] = sessionStorage.getItem('data-layout-direction');
            isLayoutAttributes['data-layout-position'] = sessionStorage.getItem('data-layout-position');
            isLayoutAttributes['data-layout-style'] = sessionStorage.getItem('data-layout-style');
            isLayoutAttributes['data-topbar'] = sessionStorage.getItem('data-topbar');
            isLayoutAttributes['data-preloader'] = sessionStorage.getItem('data-preloader');
            isLayoutAttributes['data-body-image'] = sessionStorage.getItem('data-body-image');

            Object.keys(isLayoutAttributes).forEach(function (x) {
                if (isLayoutAttributes[x] && isLayoutAttributes[x]) {
                    document.documentElement.setAttribute(x, isLayoutAttributes[x]);
                }
            });
        }
    }

})();
