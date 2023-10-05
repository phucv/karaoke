<?php
$data_bill = empty($data_bill) ? [] : $data_bill;
$bill_details = empty($bill_details) ? [] : $bill_details;
$products = empty($products) ? [] : $products;
?>
<style>
    body {
        width: 900px;
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
        width: 250px;
        padding: 10px 0 10px 20px;
        margin: 10px;
        height: 50px;
        line-height: 1.7;
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
<div class="inline" style="font-size: 18px">
    <div class="title_info bold">
        <div>KARAOKE MẠNH HÙNG</div>
    </div>
    <div class="title_info bold" style="float: right; margin-top: 0">
        <div>HOÁ ĐƠN THANH TOÁN</div>
    </div>
</div>
<div class="inline" style="font-size: 14px">
    Địa chỉ: Thuận Thành, Bắc Ninh
</div>
<div class="inline" style="font-size: 14px">
    Số điện thoại: 0918273645
</div>
<div class="fz-18" style="margin: 15px 0">
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
                    <td><?php echo !empty($products[$product_id]->name) ? $products[$product_id]->name : "Phòng hát"; ?></td>
                    <td><?php echo !empty($products[$product_id]->unit) ? $products[$product_id]->unit : "giờ"; ?></td>
                    <td class="right"><?php echo empty($products[$product_id]->unit) ? number_format($bill_detail['quantity'], 1, ',', '.') : $bill_detail['quantity']; ?></td>
                    <td class="right"><?php echo number_format($bill_detail['price'], 0, ',', '.'); ?></td>
                    <td class="right"><?php echo number_format($bill_detail['value_total'], 0, ',', '.'); ?></td>
                </tr>
            <?php } ?>
            <tr>
                <td></td>
                <td colspan="4" class="right">Cộng sản phẩm</td>
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
        </table>
    </div>
</div>
<div class="inline" style="font-size: 14px;">
    <div class="title_info" style="width: 100px; margin-top: 0">
        <div style="color: #fff;">PhuCV</div>
        <div class="center">Khách hàng</div>
    </div>
    <div class="title_info" style="width: 300px;float: right; margin-top: 0">
        <div class="center">Ngày <?php echo date("d"); ?> tháng <?php echo date("m"); ?> năm <?php echo date("Y"); ?></div>
        <div class="center">Người viết hoá đơn</div>
    </div>
</div>