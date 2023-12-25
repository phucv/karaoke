<?php
$data_bill = empty($data_bill) ? [] : $data_bill;
$bill_details = empty($bill_details) ? [] : $bill_details;
$products = empty($products) ? [] : $products;
$customer = empty($customer) ? (object)[] : $customer;
$total_price_string = empty($total_price_string) ? "" : $total_price_string;
?>
<style>
    body {
        width: 900px;
        line-height: 1.7;
    }

    .fz-18 {
        font-size: 18px;
    }

    .bold {
        font-weight: bold;
    }

    .inline {
        display: inline-block;
        width: 100%;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .title_info {
        float: left;
        height: 50px;
    }

    table {
        width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
    }

    table td {
        border: 1px solid #ccc;
        padding: 2px 5px;
    }

    td {
        border-collapse: collapse;
    }
</style>
<div class="inline" style="font-size: 14px">
    <div>Showroom máy lọc nước Europura</div>
    <div>Số 44 - Phố Thú Y - Đức Thượng - Hoài Đức - Hà Nội</div>
    <div>0904 248 568 - 081 555 8883</div>
</div>
<div class="inline" style="font-size: 18px; margin:10px;">
    <div class="bold" style="text-align: center;">
        <div>ĐƠN BÁN HÀNG</div>
    </div>
</div>
<div class="inline" style="font-size: 14px;">
    <div class="title_info" style="width: 470px; margin-top: 0">
        <div>Tên khách hàng: <?php echo empty($customer->name) ? "................................................................................" : $customer->name; ?></div>
        <div>Địa chỉ: <?php echo empty($customer->address) ? ".............................................................................................." : $customer->address; ?></div>
        <div>Mã số thuế: <?php echo empty($customer->tax_code) ? "........................................................................................" : $customer->tax_code; ?></div>
    </div>
    <div class="title_info" style="width: 200px; margin-top: 0">
        <div>Ngày: <?php echo date("d/m/Y"); ?></div>
        <div>Số: <?php echo "DH" . str_repeat(0, 7 - strlen($data_bill["id"])) . $data_bill["id"]; ?></div>
        <div>Loại tiền: VNĐ</div>
    </div>
</div>
<div class="inline" style="font-size: 14px">
    <div>Diễn giải: ....................................................................................................................................................</div>
</div>
<div class="inline" style="font-size: 14px;">
    <div class="title_info" style="width: 350px; margin-top: 0">
        <div>Điện thoại: <?php echo empty($customer->phone) ? "...................................................................." : $customer->phone; ?></div>
    </div>
    <div class="title_info" style="width: 310px; margin-top: 0">
        <div>Fax: .....................................................................</div>
    </div>
</div>
<div class="fz-18" style="margin: 0 0 15px 0">
    <div>
        <table>
            <tr>
                <td class="center">TT</td>
                <td class="center">Sản phẩm</td>
                <td class="center">Đơn vị</td>
                <td class="center">Số lượng</td>
                <td class="center">Đơn giá</td>
                <td class="center">Thành tiền</td>
            </tr>
            <?php
            $k = 1;
            foreach ($bill_details as $bill_detail) {
                $product_id = $bill_detail['product_id'];
                ?>
                <tr>
                    <td class="center"><?php echo $k++;?></td>
                    <td><?php echo !empty($products[$product_id]->name) ? $products[$product_id]->name : ""; ?></td>
                    <td><?php echo !empty($products[$product_id]->unit) ? $products[$product_id]->unit : ""; ?></td>
                    <td class="right"><?php echo empty($products[$product_id]->unit) ? number_format($bill_detail['quantity'], 1, ',', '.') : $bill_detail['quantity']; ?></td>
                    <td class="right"><?php echo number_format($bill_detail['price'], 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($bill_detail['value_total'], 0, ',', '.'); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td></td>
                <td colspan="4" class="right">Cộng tiền hàng</td>
                <td class="right"><?php echo number_format($data_bill['grand_total'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" class="right">Giảm giá</td>
                <td class="right"><?php echo number_format($data_bill['discount_amount'], 0, ',', '.')?></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="4" class="right">Tổng</td>
                <td class="right"><?php echo number_format($data_bill['total'], 0, ',', '.')?></td>
            </tr>
            <tr>
                <td colspan="6">Số tiền viết bằng chữ: <?php echo $total_price_string; ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="inline" style="font-size: 14px">
    <div>Ngày giao hàng: <?php echo date("d/m/Y"); ?></div>
    <div>Địa điểm giao hàng: .....................................................................................................................................</div>
    <div>Điều kiện thanh toán: ...................................................................................................................................</div>
</div>
<div class="inline" style="font-size: 14px;">
    <div class="title_info" style="width: 100px; margin-top: 0">
        <div style="color: #fff;">PhuCV</div>
    </div>
    <div class="title_info" style="width: 300px;float: right; margin-top: 0">
        <div class="center">Người lập</div>
        <div class="center"><i>(Ký, họ tên)</i></div>
    </div>
</div>