"use strict";
function login () {
    const loginForm = document.querySelector('#js-login-form');
    loginForm.onsubmit = (e) => {
        e.preventDefault();
        let formObj = ZUtils.objectifyForm(loginForm);
        //check if account is locked out
        if (localStorage.getItem('loginLockout') !== null) {
            const stored = JSON.parse(localStorage.getItem('loginLockout')),
                lockoutTimer = new Date(stored.lockout * 1000),
                lockedEmail = stored.email;
            if (Date.now() < lockoutTimer.getTime() && formObj.email === lockedEmail) {
                const raw = new Date(lockoutTimer - Date.now()),
                    message = 'Account locked for ' + raw.getMinutes() + ' minutes and ' + raw.getSeconds() + ' seconds.',
                    modal = accLockModal(message);
                modal.open();
            } else {
                post(formObj);
            }
        } else {
            post(formObj);
        }
    };

    function post(formObj) {
        const formJson = JSON.stringify(formObj);
        const request = new ZRequest({
            uri: 'user/login',
            httpMethod: 'POST',
            body: formJson,
            needAuth: false
        });
        request.request().then(data => {
            console.log(data);
            if (typeof data.lockout !== 'undefined') {
                localStorage.setItem('loginLockout', JSON.stringify(data.lockout));
            }
            if (data.loggedIn === true) {
                const headers = new Headers();
                //headers.append('Authorization', localStorage.getItem('jwt'));
                window.location = 'user/home';
            }
        });
    }

    function accLockModal (message) {
        //const existing = document.querySelector('div.tingle-modal');
        const modal = new tingle.modal({
            footer: false,
            closeLabel: "Close",
            onOpen: function () {
                console.log(message);
            },
            onClose: function () {
                modal.close();
                const existing = document.querySelector('div.tingle-modal');
                existing.remove();
            }
        });
        modal.setContent('<h1>' + message + '</h1>');
        return modal;
    }
}
document.addEventListener('DOMContentLoaded', login);