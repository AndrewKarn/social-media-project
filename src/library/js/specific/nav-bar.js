"use strict";
const navBar = () => {
    const navBar = document.getElementById('js-nav-bar');
    navBar.addEventListener('click', evt => {
        if (evt.target.nodeName === "A") {
            document.querySelector('.selected').classList.remove('selected');
            evt.target.classList.toggle('selected');
        }
    });
    switch (window.location.pathname) {
        case '/user/registration':
        case '/user/registration/':
        case '/user/registration#':
            document.getElementById('js-nav-register').classList.add('selected');
            break;
        case '/':
        case '/home':
        case '/home/default':
        case '/home/default#':
        case '/home/default/':
            document.getElementById('js-nav-home').classList.add('selected');
            break;
        case '/user/login':
        case '/user/login#':
        case '/user/login/':
            document.getElementById('js-nav-login').classList.add('selected');
            break;
    }
};
document.addEventListener('DOMContentLoaded', navBar);