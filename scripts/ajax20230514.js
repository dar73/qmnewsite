try {
    (http = new XMLHttpRequest()), (http2 = new XMLHttpRequest());
} catch (t) {
    try {
        (http = new ActiveXObject("Msxml2.XMLHTTP")), (http2 = new ActiveXObject("Msxml2.XMLHTTP"));
    } catch (t) {
        try {
            (http = new ActiveXObject("Microsoft.XMLHTTP")), (http2 = new ActiveXObject("Microsoft.XMLHTTP"));
        } catch (t) {
            (http = !1), (http2 = !1);
        }
    }
}
function ChangeStatus(t, e, s) {
    var n = t + "&",
        r = parseInt(99999999 * Math.random());
    http.open("GET", n + "status=" + e + "&id=" + s + "&rand=" + r, !0),
        (http.onreadystatechange = function () {
            if (4 == http.readyState && "false" != http.responseText) {
                (results = http.responseText.split("~")), (result_len = results.length);
                var t = results[1],
                    e = (results[2], result_len >= 4 ? results[3] + "_" : "");
                document.getElementById(e + "status_" + s).innerHTML = t;
            }
        }),
        http.send(null);
}
function handleHttpStatusResponse() {}
function handleHttpSetResponse() {
    4 == http.readyState && ((results = http.responseText.split("~")), (str = results[1]), (div_nm = results[2]), results[0] && document.getElementById(div_nm) && (document.getElementById(div_nm).innerHTML = str));
}
function handleHttpSetValueResponse() {
    4 == http.readyState && ((results = http.responseText.split("~")), (str = results[1]), (ctrl_id = results[2]), results[0] && document.getElementById(ctrl_id) && (document.getElementById(ctrl_id).value = str));
}
function SetSessionLanguage(t, e) {
    var s = parseInt(99999999 * Math.random());
    http.open("POST", "./_set_session.php?response=SET_LANGUAGE&lang=" + e + "&rand=" + s, !0),
        (http.onreadystatechange = function () {
            4 == http.readyState && "false" != http.responseText && window.open("http://" + t, "_parent");
        }),
        http.send(null);
}
function SetHttpsSessionLanguage(t, e) {
    var s = parseInt(99999999 * Math.random());
    http.open("POST", "./_set_session.php?response=SET_LANGUAGE&lang=" + e + "&rand=" + s, !0),
        (http.onreadystatechange = function () {
            4 == http.readyState && "false" != http.responseText && window.open("https://" + t, "_parent");
        }),
        http.send(null);
}
function serverData() {}
(serverData.prototype.init = function () {
    try {
        this._xh = new XMLHttpRequest();
    } catch (n) {
        for (var t = new Array("MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"), e = !1, s = 0; s < t.length && !e; s++)
            try {
                (this._xh = new ActiveXObject(t[s])), (e = !0);
            } catch (t) {}
        return !!e;
    }
}),
    (serverData.prototype.busy = function () {
        return (estadoActual = this._xh.readyState), estadoActual && estadoActual < 4;
    }),
    (serverData.prototype.processes = function () {
        4 == this._xh.readyState && 200 == this._xh.status && (this.processesdo = !0);
    }),
    (serverData.prototype.send = function (t, e) {
        return this._xh || this.init(), !this.busy() && (this._xh.open("POST", t, !1), this._xh.send(e), 4 == this._xh.readyState && 200 == this._xh.status) && this._xh.responseText;
    });
