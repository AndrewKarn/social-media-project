"use strict";
const registration = () => {
    const regisForm = document.getElementById('js-register-form');
    if (regisForm !== undefined) {
        regisForm.onsubmit = e => {
            e.preventDefault();
            const prevValidationErrs = document.querySelectorAll('.validation-errors');
            if (prevValidationErrs.length >= 1) {
                prevValidationErrs.forEach(err => {
                    err.remove();
                });
            }
            let formObj = ZUtils.objectifyForm(regisForm);
            // room to do stuff to form
            const formJson = JSON.stringify(formObj);
            console.log(formJson);
            const request = new ZRequest({
                uri: 'user/registration',
                httpMethod: 'POST',
                body: formJson,
                needAuth: false
            });
            request.request().then(data => {
                const existing = document.getElementById('js-validation-errors'),
                    table = document.getElementById('js-registration-table');
                if (existing !== undefined && existing !== null) {
                    existing.style.display = 'none';
                    existing.remove();
                }
                if (data.invalidForm !== undefined) {
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
                         // TODO implement registration email confirmation
                    });
                } else if (data.message !== undefined) {
                    const tr = table.insertRow(0),
                        td = tr.insertCell(0);
                    td.setAttribute('colspan', '2');
                    let text = '<p style="font-style: italic; text-align: center;">' + data.message + '</p>';
                    td.innerHTML = text;
                }
                console.log(data);
            })
        };

    }

};
window.onload = registration;