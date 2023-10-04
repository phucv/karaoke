$(document).ready(function () {
    $(document).on('change', '.e_chosen_product', chosenProduct);
    $(document).on('change', '.e_price, .e_discount, .e_quantity', totalService);
    $(document).on('click', '.e_remove_product', removeProduct);
})

function enterRoom(obj, data) {
    if (data.status !== undefined && !data.status) {
        notify(data.msg, "alert-danger");
    } else {
        if (data.msg !== undefined) {
            notify(data.msg, "alert-success");
        }
        createAjaxTable($(".manage-table"));
    }
}

function totalService() {
    let payContent = $(".e_pay_content");
    let total = 0;
    let stt = 1;
    $.each(payContent.find('.e_item:not(.hidden)'), function () {
        let item = $(this);
        let quantity = item.find(".e_quantity").val();
        quantity = quantity ? quantity : 0;
        let price = item.find(".e_price").val();
        price = price ? price : price;
        let money = quantity * price;
        item.find(".e_money").text(formatMoney(money, 0, ',', '.'));
        item.find(".e_stt").text(stt++);
        total += money;
    })
    payContent.find('.e_total .e_money').text(formatMoney(total, 0, ',', '.'));
    let discount = payContent.find(".e_discount").val();
    discount = discount ? discount : 0;
    let total_money = total - discount;
    payContent.find('.e_total_money').text(formatMoney(total_money, 0, ',', '.'));
}

function chosenProduct() {
    let obj = $(this);
    let value = obj.val();
    if (!value) return;
    let option = obj.find(":selected");
    let name = option.data('name');
    let unit = option.data('unit');
    let price = option.data('price');
    let payContent = obj.closest(".e_pay_content");
    let item = payContent.find('.e_item.hidden').clone().removeClass('hidden');
    item.find('.e_stt').text(payContent.find('.e_item:not(.hidden)').length + 1);
    item.find('.e_product').val(value);
    item.find('.e_name').text(name);
    item.find('.e_unit').text(unit);
    item.find('.e_price').val(price);
    payContent.find('.e_total').before(item);
    obj.val(0);
    initSelect2();
}

function removeProduct() {
    $(this).closest('tr').remove();
    totalService();
}

function payCallback(obj, data) {
    if (data.status !== undefined && !data.status) {
        notify(data.msg, "alert-danger");
    } else {
        if (data.msg !== undefined) {
            notify(data.msg, "alert-success");
        }
        createAjaxTable($(".manage-table"));
    }
    setTimeout(() => {
        let link = document.createElement('a');
        document.body.appendChild(link);
        link.href = data.url;
        link.target = "_blank";
        link.click();
    }, 300);
}