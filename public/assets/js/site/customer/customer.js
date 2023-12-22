function addCustomer(form, data) {
    let callback = form.data("callback");
    if (callback) {
        if (window[callback]) {
            window[callback](form, data);
        }
    } else {
        defaultCallbackSubmit(form, data);
    }
}