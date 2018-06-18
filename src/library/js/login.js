"use strict";
function login () {
    const loginForm = document.querySelector('#js-login-form');
    loginForm.onsubmit = (e) => {
        e.preventDefault();
        //check if account is locked out
        if (localStorage.getItem('loginLockout') !== null) {
            const lockoutTimer = new Date(localStorage.getItem('loginLockout') * 1000);
            if (Date.now() < lockoutTimer.getTime()) {
                const raw = new Date(lockoutTimer - Date.now()),
                    message = 'Account locked for ' + raw.getMinutes() + ' minutes and ' + raw.getSeconds() + ' seconds.',
                    modal = accLockModal(message);
                modal.open();
            } else {
                post();
            }
        } else {
            post();
        }
    };

    function post() {
        let formObj = ZUtils.objectifyForm(loginForm);
        // room to do stuff to form
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
                localStorage.setItem('loginLockout', data.lockout);
            }
        });
    }

    function accLockModal (message) {
        //const existing = document.querySelector('div.tingle-modal');
        const modal = new tingle.modal({
            footer: false,
            closeMethods: ['overlay', 'button', 'escape'],
            closeLabel: "Close",
            // cssClass: [],
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