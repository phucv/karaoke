function addCustomerSale(form, data) {
    if (data.status != undefined && data.status == 0) {
        notify(data.msg, "alert-danger");
    } else {
        if (data.msg != undefined) {
            notify(data.msg, "alert-success");
        }
        let dataCustomer = data.data;
        if (dataCustomer) {
            let des = dataCustomer.phone ? dataCustomer.phone : dataCustomer.email;
            if (des) des = " - " + des;
            let customer = $('.e_customer');
            customer.append("<option value='" + dataCustomer.id + "'>" + dataCustomer.name + des + "</option>");
            customer.val(dataCustomer.id);
            initSelect2();
        }
    }
}