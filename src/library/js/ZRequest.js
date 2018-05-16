"use strict";
/*
    init = {
        uri: '/example/uri',
        method: 'POST',
        body: string | int | json | array | obj,

    }
 */

const ZRequest = function(init) {
    this.root = 'http://www.zoes-social-media-project.com/';
    this.uri = '';
    this.setUri(init.uri);
    this.jwt = this.setJWT();
    this.setHttpMethod(init.httpMethod);
    if (this.httpMethod !== undefined) {
        this.setBody(init.body);
    }
    this.headers = this.setDefaultHeaders();
};

ZRequest.prototype.constructor = ZRequest;

ZRequest.prototype.request = function () {
    return fetch(this.getReqUri(), this.buildFetchInit()).then(response => {
        const jwt = response.headers.get('authorization');
        localStorage.setItem('jwt', jwt);
        return response.json();
    });
};

ZRequest.prototype.buildFetchInit = function () {
    let init = {};
    init.method = this.getHttpMethod();
    init.headers = this.getHeaders();
    if (this.isBody()) {
        init.body = this.getBody();
    }
    return init;
};

ZRequest.prototype.setUri = function (uri) {
    this.uri = uri;
};

ZRequest.prototype.getUri = function () {
    return this.uri;
};

ZRequest.prototype.getReqUri = function () {
    return new Request(this.root + this.getUri())
};

// TODO implement a Request([ name, type ]) here to add optional
ZRequest.prototype.setDefaultHeaders = function () {
    const headers = new Headers(),
        jwt = this.getJWT();
    if (jwt) {
        headers.append('Authorization', jwt);
    }
    if (this.isBody()) {
        headers.append('Content-Type', 'application/json');
    }
    return headers;
};

ZRequest.prototype.getHeaders = function () {
    return this.headers;
};

ZRequest.prototype.setJWT = function () {
    return localStorage.getItem('jwt');
};

ZRequest.prototype.getJWT = function () {
    return this.jwt;
};

ZRequest.prototype.isBody = function () {
    return this.getBody() !== undefined;
};

ZRequest.prototype.setBody = function (data = undefined) {
    this.body = data;
};

ZRequest.prototype.getBody = function () {
    return this.body;
};

ZRequest.prototype.setHttpMethod = function (httpMethod) {
    this.httpMethod = httpMethod !== undefined ? httpMethod : 'GET';
};

ZRequest.prototype.getHttpMethod = function () {
    return this.httpMethod;
};
