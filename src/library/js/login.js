"use strict";
function login () {
    const loginForm = document.querySelector('#js-login-form'),
        loginBtn = document.getElementById('js-login-btn');
    // loginBtn.addEventListener('click', e => {
    //     e.preventDefault();
    // });

    if (loginForm !== undefined) {
        loginForm.onsubmit = (e) => {
            e.preventDefault();
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
            })
        }
    } else {
        console.log('loginForm not found\.');
    }
}
document.addEventListener('DOMContentLoaded', login);