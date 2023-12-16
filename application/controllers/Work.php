<?php

/**
 * Class Work
 * @property M_room model
 * @property M_product product
 * @property M_bill bill
 * @property M_bill_detail bill_detail
 */
class Work extends Site_base {

    function setting_base() {
        $this->_settings = array(
            "class" => "work",
            "view" => "site/work",
            "model" => "M_room",
        );
    }

    public function index() {
        $data = [
            'title' => 'Làm việc',
            'more_css' => [
                'assets/css/site/modal.css',
                'assets/css/site/work/work.css'
            ],
            "more_js" => [
                'assets/js/site/work/work.js'
            ]
        ];
        $this->manager($data);
    }


    public function get_data($data = []) {
        $data = parent::get_data($data);
        $data["url_enter_room"] = site_url('work/enter_room');
        $data["url_pay"] = site_url('work/pay');
        $data["url_change_room"] = site_url('work/change_room');
        return $data;
    }

    protected function _get_schema() {
        return ["m.name"];
    }

    protected function _get_field_filter() {
        return array(
            "filter" => array("public" => "m.public", "status" => "m.status"),
        );
    }

    protected function _get_part_filter($filter_view_folder) {
        $part = array();
        $part["row-1"] = array(
            "part-11 width-25" => array(
                array(
                    "view" => $filter_view_folder . "/part_search",
                    "class" => "width-100",
                ),
            ),
        );
        // Overwrite $part - row-2
        $part["row-2 row-custom"] = [];
        $part["row-3"] = array(
            "part31" => array(
                array(
                    "view" => $filter_view_folder . "/part_count_row",
                ),
            ),
        );
        return $part;
    }

    public function enter_room() {
        $room_id = $this->input->post("id");
        $room = $this->model->get_by(["id" => $room_id, "public" => 1]);
        $data_return = [
            "status" => 0,
        ];
        if (!$room) {
            $data_return['msg'] = "Phòng không hợp lệ vui lòng thử lại sau.";
            echo json_encode($data_return);
            return TRUE;
        }
        if (!empty($room->status)) {
            $data_return['msg'] = "Phòng đã được sử dụng. Vui lòng sử dụng phòng khác";
            echo json_encode($data_return);
            return TRUE;
        }
        $this->model->update($room_id, ["status" => 1, "time_enter" => date("Y-m-d H:i:s")]);
        $data_return = [
            'status' => 1,
            'msg' => "Cho sử dụng phòng thành công",
        ];
        echo json_encode($data_return);
        return TRUE;
    }

    public function pay() {
        $id = $this->input->post("id");
        $room = $this->model->get_by(["id" => $id, "status" => 1]);
        $data_return = [
            "status" => 0,
        ];
        if (!$room) {
            $data_return['msg'] = "Phòng không hợp lệ vui lòng thử lại sau.";
            echo json_encode($data_return);
            return TRUE;
        }
        $data = [
            'room' => $room
        ];
        $this->load->model("M_product", "product");
        $data["url_pay_save"] = site_url("work/pay_save");
        $data["products"] = $this->product->get_many_by(['public' => 1]);
        $content = $this->load->view("site/work/pay", $data, TRUE);
        $data_return = array(
            "status" => "1",
            "html" => $content,
        );
        echo json_encode($data_return);
        return TRUE;
    }

    public function pay_save() {
        $data = $this->input->post();
        $return_data = array(
            "status" => 0,
            "msg" => 'Dữ liệu không chính xác!'
        );
        if (!$this->input->is_ajax_request()) {
            echo json_encode($return_data);
            return FALSE;
        }
        $product_ids = empty($data['product_id']) ? [] : $data['product_id'];
        $product_quantities = empty($data['product_quantity']) ? [] : $data['product_quantity'];
        $product_prices = empty($data['product_price']) ? [] : $data['product_price'];
        $room_id = empty($data['room_id']) ? 0 : $data['room_id'];
        $room_quantity = empty($data['room_quantity']) ? 0 : $data['room_quantity'];
        $room_price = empty($data['room_price']) ? 0 : $data['room_price'];
        $discount_amount = empty($data['discount_amount']) ? 0 : $data['discount_amount'];
        $grand_total = $room_price * $room_quantity;
        $bill_details[] = [
            "room_id" => $room_id,
            "product_id" => 0,
            "quantity" => $room_quantity,
            "price" => $room_price,
            "value_total" => $grand_total,
        ];
        foreach ($product_ids as $k => $product_id) {
            $product_quantity = !empty($product_quantities[$k]) ? $product_quantities[$k] : 0;
            if (!$product_id || !$product_quantity) continue;
            $product_price = !empty($product_prices[$k]) ? $product_prices[$k] : 0;
            $value_total = $product_quantity * $product_price;
            $grand_total += $value_total;
            $bill_details[] = [
                "room_id" => $room_id,
                "product_id" => $product_id,
                "quantity" => $product_quantity,
                "price" => $product_price,
                "value_total" => $value_total,
            ];
        }
        $total = $grand_total - $discount_amount;
        $data_bill = [
            'total' => max($total, 0),
            'discount_amount' => $discount_amount,
            'grand_total' => $grand_total,
            'status' => 'done',
            'payment_date' => date("Y-m-d H:i:s"),
        ];
        $this->load->model("M_bill", "bill");
        $this->load->model("M_bill_detail", "bill_detail");
        $this->db->trans_begin();
        $bill_id = $this->bill->insert($data_bill);
        $status = false;
        if ($bill_id) {
            foreach ($bill_details as $key => $bill_detail) {
                $bill_details[$key]["bill_id"] = $bill_id;
            }
            $this->bill_detail->insert_batch($bill_details);
            $status = $this->model->update($room_id, ["status" => 0, "time_enter" => NULL]);
        }
        if (!$status) {
            $this->db->trans_rollback();
            $return_data["msg"] = "Lỗi thanh toán. Vui lòng thử lại.";
            echo json_encode($return_data);
            return TRUE;
        }
        $this->db->trans_complete();

        $destination_path = "upload/bill/";
        if (!is_dir($destination_path)) {
            mkdir($destination_path, 0777, TRUE);
        }
        $pdfFilePath = $destination_path . "bill_$bill_id.pdf";
        //xuat pdf
        $this->_export_pdf($data_bill, $bill_details, $product_ids, $pdfFilePath);
        $return_data["status"] = 1;
        $return_data["msg"] = "Thanh toán thành công";
        $return_data["url"] = base_url($pdfFilePath);
        $return_data["callback"] = "payCallback";
        echo json_encode($return_data);
        return TRUE;
    }

    private function _export_pdf($data_bill, $bill_details, $product_ids, $pdfFilePath) {
        $data = [
            "data_bill" => $data_bill,
            "bill_details" => $bill_details
        ];
        $this->load->model("M_product", "product");
        $products = $this->product->get_many($product_ids);
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

    public function change_room() {
        $id = $this->input->post("id");
        $data_return = [
            "status" => 0,
        ];
        $room = $this->model->get_by(["id" => $id, "status" => 1]);
        if (!$room) {
            $data_return['msg'] = "Phòng không hợp lệ vui lòng thử lại sau.";
            echo json_encode($data_return);
            return TRUE;
        }
        $room_change = $this->model->get_many_by(["id !=" => $id, "status" => 0, 'public' => 1]);
        if (empty($room_change)) {
            $data_return['msg'] = "Không còn phòng trống để chuyển.";
            echo json_encode($data_return);
            return TRUE;
        }
        $data["room"] = $room;
        $data["rooms"] = $room_change;
        $data["url_change_room_save"] = site_url("work/change_room_save");
        $content = $this->load->view("site/work/change_room", $data, TRUE);
        $data_return = array(
            "status" => "1",
            "html" => $content,
        );
        echo json_encode($data_return);
        return TRUE;
    }

    public function change_room_save() {
        $data_return = [
            "status" => 0,
        ];
        $id = $this->input->post("id");
        $room = $this->model->get_by(["id" => $id, "status" => 1]);
        if (!$room) {
            $data_return['msg'] = "Phòng cần chuyển không hợp lệ.";
            echo json_encode($data_return);
            return TRUE;
        }
        $room_change_id = $this->input->post("room_change_id");
        $room_change = $this->model->get_by(["id" => $room_change_id, "status" => 0, 'public' => 1]);
        if (!$room_change) {
            $data_return['msg'] = "Phòng chuyển đến không hợp lệ.";
            echo json_encode($data_return);
            return TRUE;
        }
        $this->db->trans_begin();
        $status = $this->model->update($id, ['status' => 0, "time_enter" => NULL]);
        $status_change = $this->model->update($room_change_id, ['status' => 1, "time_enter" => $room->time_enter]);
        if (!$status || !$status_change) {
            $this->db->trans_rollback();
            $data_return['msg'] = "Đã có lỗi xảy ra. Vui lòng thử lại";
            echo json_encode($data_return);
            return TRUE;
        }
        $this->db->trans_complete();
        $return_data["status"] = 1;
        $return_data["msg"] = "Chuyển phòng thành công";
        $return_data["callback"] = "defaultCallbackSubmit";
        echo json_encode($return_data);
        return TRUE;
    }
}