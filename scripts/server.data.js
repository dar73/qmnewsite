function serverData() {}

serverData.prototype.init = function() {
    try {
        this._xh = new XMLHttpRequest
    } catch (n) {
        for (var t = new Array("MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"), e = !1, s = 0; s < t.length && !e; s++) try {
            this._xh = new ActiveXObject(t[s]), e = !0
        } catch (t) {}
        return !!e
    }
}, serverData.prototype.busy = function() {
    return estadoActual = this._xh.readyState, estadoActual && estadoActual < 4
}, serverData.prototype.processes = function() {
    4 == this._xh.readyState && 200 == this._xh.status && (this.processesdo = !0)
}, serverData.prototype.send = function(t, e) {
    return this._xh || this.init(), !this.busy() && (this._xh.open("POST", t, !1), this._xh.send(e), 4 == this._xh.readyState && 200 == this._xh.status) && this._xh.responseText
};