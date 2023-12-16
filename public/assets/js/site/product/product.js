$(document).ready(function () {
    $(document).on("click", ".e_add_unit", addUnit);
});

function addUnit () {
    let obj = $(this);
    let form = obj.closest('form');
    let unitBase = form.find('.e_unit_base');
    if (!unitBase.val()) {
        notify("Chưa nhập Đơn vị bán thấp nhất");
        return;
    }
    let unitTable = form.find('.e_unit');
    let itemTemplate = form.find('.e_item.hidden');
    let item = itemTemplate.clone();
    unitTable.removeClass("hidden");
    item.removeClass("hidden");
    itemTemplate.before(item);
}