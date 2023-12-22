<?php

/**
 * Class Sale
 * @property M_bill bill
 * @property M_bill_detail bill_detail
 * @property M_product product
 * @property M_customer customer
 */
class Sale extends Site_layout {


    function __construct() {
        parent::__construct();
        // set title
        $this->data["title"] = "Bán hàng";
        // Load model
        $this->load->model('M_bill', 'bill');
    }

    public function index() {
        $data = array();
        $this->load->model("M_product", "product");
        $this->load->model("M_customer", "customer");
        $data["products"] = $this->product->get_many_by(["public" => 1]);
        $data["customers"] = $this->customer->get_many_by([]);
        $product_parent = $this->product->get_many_by(["public" => 1, "parent_id" => null]);
        foreach ($product_parent as $product) {
            $data["product_parent"][$product->id] = $product;
        };
        // URL for link
        $data["url_save_data"] = site_url("sale/add_save");
        $content = $this->load->view("site/sale/content", $data, TRUE);
        $this->show_page($content);
    }

    public function add_save() {
        $data = $this->input->post();
        $return_data = array(
            "status" => 0,
            "status_code" => "INVALID_DATA",
            "msg" => 'Dữ liệu không chính xác!'
        );
        if (!$this->input->is_ajax_request()) {
            echo json_encode($return_data);
            return FALSE;
        }
        $this->load->model("M_product", "product");
        $product_ids = empty($data["product_id"]) ? [] : $data["product_id"];
        $products = count($product_ids) ? $this->product->get_many($product_ids) : [];
        if (!$products) {
            $return_data['msg'] = "Không có sản phẩm hợp lệ để nhập hàng";
            echo json_encode($return_data);
            return FALSE;
        }
        $quantity = empty($data["quantity"]) ? [] : $data["quantity"];
        $price = empty($data["price"]) ? [] : $data["price"];
        $bill_details = [];
        $product_details = [];
        $quantity_parent = [];
        foreach ($products as $product) {
            $product_details[$product->id] = $product;
        }
        $grand_total = 0;
        foreach ($product_ids as $k => $product_id) {
            if (!$product_id || empty($product_details[$product_id]) || empty($quantity[$k])) continue;
            $pro_price = empty($price[$k]) ? 0 : $price[$k];
            $total = $quantity[$k] * $pro_price;
            $grand_total += $total;
            $bill_details[] = [
                "product_id" => $product_id,
                "quantity" => $quantity[$k],
                "price" => $pro_price,
                "value_total" => $total,
            ];

            if ($product_details[$product_id]->parent_id) {
                if (empty($quantity_parent[$product_details[$product_id]->parent_id])) $quantity_parent[$product_details[$product_id]->parent_id] = 0;
                $quantity_parent[$product_details[$product_id]->parent_id] += $quantity[$k] * $product_details[$product_id]->unit_value;
            } else {
                if (empty($quantity_parent[$product_id])) $quantity_parent[$product_id] = 0;
                $quantity_parent[$product_id] += $quantity[$k];
            }
        }
        if (!count($bill_details)) {
            $return_data['msg'] = "Không có số lượng sản phẩm để nhập hàng";
            echo json_encode($return_data);
            return FALSE;
        }
        $discount_amount_total = empty($data["discount_amount_total"]) ? 0 : $data["discount_amount_total"];
        $customer_id = empty($data["customer_id"]) ? NULL : $data["customer_id"];
        $data_bill = [
            "status" => "done",
            "total" => $grand_total > $discount_amount_total ? $grand_total - $discount_amount_total : 0,
            "discount_amount" => $discount_amount_total,
            "grand_total" => $grand_total,
            "customer_id" => $customer_id,
            "payment_date" => date("Y-m-d H:i:s"),
        ];
        $this->db->trans_begin();
        $status = $bill_id = $this->bill->insert($data_bill);
        if ($bill_id) {
            foreach ($bill_details as $key => $detail) {
                $bill_details[$key]["bill_id"] = $bill_id;
            }
            foreach ($quantity_parent as $p_id => $q) {
                $status = $status && $this->product->update_quantity($p_id, 0 - $q);
            }
            $this->load->model("M_bill_detail", "bill_detail");
            $status = $status && $this->bill_detail->insert_batch($bill_details);
        }
        if ($status) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        $destination_path = "upload/bill/";
        if (!is_dir($destination_path)) {
            mkdir($destination_path, 0777, TRUE);
        }
        $pdfFilePath = $destination_path . "bill_$bill_id.pdf";
        //xuat pdf
        $this->_export_pdf($data_bill, $bill_details, $products, $pdfFilePath);

        $return_data["status"] = 1;
        $return_data["status_code"] = "SUCCESS";
        $return_data["msg"] = "Bán hàng thành công";
        $return_data["url"] = base_url($pdfFilePath);
        $return_data["url_redirect"] = site_url('sale');
        $return_data["callback"] = "saleCallback";
        echo json_encode($return_data);
        return TRUE;
    }

    private function _export_pdf($data_bill, $bill_details, $products, $pdfFilePath) {
        $this->load->model("M_customer", "customer");
        $data = [
            "data_bill" => $data_bill,
            "bill_details" => $bill_details,
            "customer" => $this->customer->get($data_bill["customer_id"])
        ];
        foreach ($products as $product) {
            $data["products"][$product->id] = $product;
        }
        $content_table = $this->load->view('site/work/pdf', $data, TRUE);

        //export pdf
        $pdf = new \Mpdf\Mpdf([
            'mode' => "",    // mode - default ''
            "format" => 'A4-P',    // format - A4, for example, default ''
            "default_font_size" => 0,     // font size - default 0
            "default_font" => '',    // default font family
            "margin_right" => 15,    // margin_left
            "margin_left" => 15,    // margin right
            "margin_top" => 16,    // margin top
            "margin_bottom" => 16,    // margin bottom
            "margin_header" => 9,     // margin header
            "margin_footer" => 9,     // margin footer
            'orientation' => 'P'    // L - landscape, P - portrait
        ]);
        //generate the PDF from the given html
        $pdf->simpleTables = TRUE;
        $pdf->packTableData = TRUE;
        $pdf->WriteHTML($content_table);

        //download it.
        $pdf->Output($pdfFilePath, "F");
    }
}