"use strict";
const registration = () => {
    const regisForm = document.getElementById('js-register-form');
    if (regisForm !== undefined) {
        regisForm.onsubmit = e => {
            e.preventDefault();
            let formObj = FrontUtils.objectifyForm(regisForm);
            // room to do stuff to form
            const formJson = JSON.stringify(formObj);
            console.log(formJson);
            const request = new ZRequest({
                uri: 'user/register',
                httpMethod: 'POST',
                body: formJson,
                needAuth: false
            });
            request.request().then(data => {
                if (data.invalidForm !== undefined) {
                    const existing = document.getElementById('js-validation-errors'),
                        table = document.getElementById('js-registration-table');
                    if (existing !== undefined && existing !== null) {
                        existing.style.display = 'none';
                        existing.remove();
                    }
                    data.invalidForm.forEach(arr => {
                         const tr = table.insertRow(0),
                             td = tr.insertCell(0);
                         td.setAttribute('colspan', '2');
                         // eventually will highlight invalid forms
                         let text = '<p>Invalid input ' + arr[0] + ': ' + arr[1] + '</p>';
                         text = text.replace('. ', '<br>');
                         text = text.replace(': ', '<br>');
                         td.innerHTML = text;
                         td.classList.add('validation-errors');
                    });
                }
                console.log(data);
            })
        };

    }

};
window.onload = registration;