"use strict";
var trackingEndpoint = ("https:" === document.location.protocol ? "https://" : "http://") + "us-central1-datalinq.cloudfunctions.net/visitors/tracking";
function Binox() {
    this.accountId = '';
    this.domain = '';
}
(function () {
    console.log(document.location)

    Binox.prototype.setAccount = function (accountId) {
        this.accountId = accountId;
    }
    Binox.prototype.setDomain = function (domain) {
        this.domain = domain;
    }
    Binox.prototype.trackPage = async function () {
        var url = new URL(trackingEndpoint)
        var params = { accountId: this.accountId, domain: this.domain, visit: btoa(window.location.href) }
        url.search = new URLSearchParams(params).toString();
        var resp = await fetch(url);
        resp = await resp.json();
        // document.cookie = "someCookieName=true; expires=Fri, 31 Dec 9999 23:59:59 GMT";
    }
})()