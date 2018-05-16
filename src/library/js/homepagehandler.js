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
                uri: 'user/default',
                httpMethod: 'POST',
                body: formJson
            });
            request.request().then(data => {
                    console.log(data);
                })
                .catch(error => {
                    console.log(error);
                });
            // const loginRequest = new Request('http://www.zoes-social-media-project.com/user/login/');
            // const loginHeaders = new Headers();
            // loginHeaders.append('Content-Type', 'application/json');
            // // loginHeaders.append('Authorization', 'Bearer: zoe');
            // const loginInit = {
            //     method: 'POST',
            //     headers: loginHeaders,
            //     body: formJson
            // };
            // console.log(loginInit);
            // fetch(loginRequest, loginInit)
            //     .then(resp => {
            //         return resp.json();
            //     })
            //     .then( data => {
            //         console.log(data);
            //     })
        }
    } else {
        console.log('loginForm not found\.');
    }
};
window.onload = load;