"use strict";
const FrontUtils = new ZUtils();

const load = () => {
    const loginForm = document.querySelector('#js-login-form');

    if (localStorage) {
        let token = localStorage.getItem('jwt');
        // NEED TO implement sending token

    } else {

    }

    if (loginForm !== undefined) {
        loginForm.onsubmit = (e) => {
            e.preventDefault();
            let formObj = FrontUtils.objectifyForm(loginForm);
            // room to do stuff to form
            const formJson = JSON.stringify(formObj);
            const request = new ZRequest({
                uri: 'user/login',
                httpMethod: 'POST',
                body: formJson,
                needAuth: true
            });
            request.request().then(data => {
                console.log(data);
            })

        }
    } else {
        console.log('loginForm not found\.');
    }
};
window.onload = load;