document.addEventListener('DOMContentLoaded', function() {
    ajax.navigation.GET(function() {

    });
});

var ajax = {
    signup: new Ajax('?go=signup', '[action="?go=signup"]'),
    login: new Ajax('?go=login', '[action="?go=login"]'),
    navigation: new Ajax()
};

function Ajax(url, form) {
    this.url = url;
    if (form)
        this.form = function() {
            return new FormData(document.querySelector(form));
        }
}

Ajax.prototype = {
    request: function(method) {
        var ajax = new XMLHttpRequest();
        ajax.open(method, this.url);
        if (this.type)
            ajax.setRequestHeader('Content-Type', this.type);
        return ajax;
    },

    GET: function(call) {
        var ajax = this.request('GET');
        ajax.onload = call;
        ajax.send(null);
        return ajax;
    },

    POST: function(call) {
        var ajax = this.request('POST');
        ajax.onload = call;
        ajax.send(this.form());
        return ajax;
    }
};