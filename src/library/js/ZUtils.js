"use strict";
const ZUtils = {
    objectifyForm: form => {
        const formData = new FormData(form);
        let obj = {};
        for (let entry of formData.entries()) {
            obj[entry[0]] = entry[1];
        }
        return obj;
    },
    appendModal: modal => {
        document.body.append(modal);
    }
};