$(document).ready(function () {
    $(document).on("change", ".e_product", chosenProduct);
    $(document).on("change", ".e_product_quantity input, .e_product_purchase_price input, .e_discount_amount input", calculatorRowTotal);
    $(document).on("change", ".e_discount_amount_total", calculatorTotal);
    $(document).on("click", ".e_remove_purchase", removePurchase);
});

function chosenProduct () {
    let obj = $(this);
    let form = obj.closest('form');
    let productId = obj.val();
    if (!productId) return;
    let option = obj.find(":selected");
    let name = option.data('name');
    let code = option.data('code');
    let unit = option.data('unit');
    let purchasePrice = option.data('purchase_price');

    let itemTemplate = form.find('.e_item_purchase.hidden');
    let item = itemTemplate.clone();
    item.removeClass("hidden");
    item.find('.e_product_id').val(productId);
    item.find('.e_product_name').text(name);
    item.find('.e_product_code').text(code);
    item.find('.e_product_unit').text(unit);
    item.find('.e_product_purchase_price input').val(purchasePrice);
    itemTemplate.before(item);
    sortSTT();
    obj.val(0);
    option.prop('disabled', true);
    initSelect2();
}

function calculatorRowTotal() {
    let obj = $(this);
    let row = obj.closest('.e_item_purchase');
    let quantity = parseInt(row.find('.e_product_quantity input').val());
    let purchasePrice = parseInt(row.find('.e_product_purchase_price input').val());
    let discountAmount = row.find('.e_discount_amount input').val();
    discountAmount = discountAmount === undefined ? 0 : parseInt(discountAmount);
    if (discountAmount > purchasePrice) discountAmount = purchasePrice;
    row.find('.e_value_total input').val(quantity * (purchasePrice - discountAmount));
    calculatorTotal();
}

function removePurchase() {
    let item = $(this).closest('.e_item_purchase');
    let productId = item.find('.e_product_id').val();
    item.closest('form').find('.e_product').find('option[value="' + productId + '"]').prop('disabled', false);
    $(this).closest('.e_item_purchase').remove();
    initSelect2();
    sortSTT();
}

function sortSTT() {
    let stt = 1;
    $.each($(".e_item_purchase:not(.hidden)"), function () {
        $(this).find('.e_stt').text(stt++);
    });
}

function calculatorTotal() {
    let form = $('form.e_ajax_submit');
    let total = 0;
    $.each(form.find(".e_item_purchase:not(.hidden) .e_value_total input"), function () {
        total += parseInt($(this).val());
    });
    form.find('.e_grand_total').text(total);
    let discountTotal = parseInt(form.find('.e_discount_amount_total').val());
    if (discountTotal > total) discountTotal = total;
    form.find('.e_total').text(total - discountTotal);
}

function saleCallback(obj, data) {
    if (data.status !== undefined && !data.status) {
        notify(data.msg, "alert-danger");
    } else {
        let url_redirect = null;
        if (data.url_redirect !== undefined) {
            url_redirect = data.url_redirect;
        }
        if (data.msg !== undefined) {
            notify(data.msg, "alert-success", url_redirect);
        }
    }
    setTimeout(() => {
        let link = document.createElement('a');
        document.body.appendChild(link);
        link.href = data.url;
        link.target = "_blank";
        link.click();
    }, 300);
}