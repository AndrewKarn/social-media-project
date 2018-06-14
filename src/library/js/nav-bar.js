"use strict";
const navBar = () => {
    const navBar = document.getElementById('js-nav-bar');
    navBar.addEventListener('click', evt => {
        if (evt.target.nodeName === "A") {
            document.querySelector('.selected').classList.remove('selected');
            evt.target.classList.toggle('selected');
        }
    });
};
document.addEventListener('DOMContentLoaded', navBar);