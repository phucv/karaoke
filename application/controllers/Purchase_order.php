<?php

/**
 * Class Purchase_order
 * @property M_purchase_order model
 * @property M_purchase_order_detail purchase_order_detail
 * @property M_product product
 */
class Purchase_order extends Site_base {

    function setting_base() {
        $this->_settings = array(
            "class" => "purchase_order",
            "view" => "site/purchase_order",
            "model" => "M_purchase_order",
        );
    }

    public function index() {
        $data = [
            'title' => 'Nhập hàng',
            'more_css' => [
                'assets/css/site/modal.css',
                'assets/css/site/base_manager/table.css',
                'assets/css/site/purchase_order/purchase_order.css',
            ],
            'more_js' => [
                'assets/js/site/purchase_order/purchase_order.js',
                'assets/js/site/base_manager/report_management.js',
            ]
        ];
        $this->manager($data);
    }

    public function get_data($data = []) {
        $get_data = parent::get_data($data);
        $get_data['url_order_detail'] = site_url('purchase_order/detail');
        return $get_data;
    }

    protected function _process_data_condition($data_condition) {
        if (empty($data_condition["filter"]['time_created'])) {
            $data_condition["filter"]['time_created'][] = [
                "from" => date("d-m-Y"),
                "to" => date("d-m-Y"),
            ];
        }
        return parent::_process_data_condition($data_condition);
    }

    protected function _get_field_filter() {
        return array(
            "filter" => array("time_created" => "payment_date"),
        );
    }

    protected function _get_part_filter($filter_view_folder) {
        $part = array();
        $part["row-1"] = array(
            "part-11 width-25" => [
                array(
                    "view" => $filter_view_folder . "/part_search",
                    "class" => "width-100",
                ),
            ],
        );
        // Overwrite $part - row-2
        $part["row-2 row-custom"] = [
            "part-21 block-part" => [
                [
                    "view"       => $filter_view_folder . "/part_input",
                    "data"       => array(
                        "label"      => "Thời gian",
                        "value_from" => date('d-m-Y'),
                        "value_to"   => date('d-m-Y'),
                    ),
                    "class"      => "filter-custom-row filter-input select-quick",
                    "attributes" => "action-type='1'",
                ]
            ]
        ];
        $part["row-3"] = array(
            "part31" => array(
                array(
                    "view" => $filter_view_folder . "/part_count_row",
                ),
            ),
        );
        return $part;
    }

    public function add() {
        $title = "Thêm mới";
        $data = [
            "title" => $title,
        ];
        $data["url_save_data"] = site_url("purchase_order/add_save");
        // data material
        $this->load->model("M_product", "product");
        $data["products"] = $this->product->get_many_by(["public" => 1]);
        $product_parent = $this->product->get_many_by(["public" => 1, "parent_id" => null]);
        foreach ($product_parent as $product) {
            $data["product_parent"][$product->id] = $product;
        };
        $content = $this->load->view("site/purchase_order/add", $data, TRUE);
        $data_return = array(
            "status" => "1",
            "html" => $content,
        );
        echo json_encode($data_return);
        return TRUE;
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
        $purchase_price = empty($data["purchase_price"]) ? [] : $data["purchase_price"];
        $discount_amount = empty($data["discount_amount"]) ? [] : $data["discount_amount"];
        $value_total = empty($data["value_total"]) ? [] : $data["value_total"];
        $details = [];
        $product_details = [];
        $quantity_parent = [];
        foreach ($products as $product) {
            $product_details[$product->id] = $product;
        }
        $grand_total = 0;
        foreach ($product_ids as $k => $product_id) {
            if (!$product_id || empty($product_details[$product_id]) || empty($quantity[$k])) continue;
            $total = empty($value_total[$k]) ? 0 : $value_total[$k];
            $details[] = [
                "product_id" => $product_id,
                "quantity" => $quantity[$k],
                "purchase_price" => empty($purchase_price[$k]) ? 0 : $purchase_price[$k],
                "discount_amount" => empty($discount_amount[$k]) ? 0 : $discount_amount[$k],
                "value_total" => $total,
                "product_info" => json_encode($product_details[$product_id]),
            ];
            $grand_total += $total;

            if ($product_details[$product_id]->parent_id) {
                if (empty($quantity_parent[$product_details[$product_id]->parent_id])) $quantity_parent[$product_details[$product_id]->parent_id] = 0;
                $quantity_parent[$product_details[$product_id]->parent_id] += $quantity[$k] * $product_details[$product_id]->unit_value;
            } else {
                if (empty($quantity_parent[$product_id])) $quantity_parent[$product_id] = 0;
                $quantity_parent[$product_id] += $quantity[$k];
            }
        }
        if (!count($details)) {
            $return_data['msg'] = "Không có số lượng sản phẩm để nhập hàng";
            echo json_encode($return_data);
            return FALSE;
        }
        $discount_amount_total = empty($data["discount_amount_total"]) ? 0 : $data["discount_amount_total"];
        $purchase = [
            "code" => "NH" . date("YmdHis"),
            "status" => "done",
            "total" => $grand_total > $discount_amount_total ? $grand_total - $discount_amount_total : 0,
            "discount_amount" => $discount_amount_total,
            "grand_total" => $grand_total,
            "payment_date" => date("Y-m-d H:i:s"),
        ];
        $this->db->trans_begin();
        $status = $purchase_id = $this->model->insert($purchase);
        if ($purchase_id) {
            foreach ($details as $key => $detail) {
                $details[$key]["purchase_order_id"] = $purchase_id;
            }
            foreach ($quantity_parent as $p_id => $q) {
                $status = $status && $this->product->update_quantity($p_id, $q);
            }
            $this->load->model("M_purchase_order_detail", "purchase_order_detail");
            $status = $status && $this->purchase_order_detail->insert_batch($details);
        }
        if ($status) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        $return_data["status"] = 1;
        $return_data["status_code"] = "SUCCESS";
        $return_data["msg"] = "Thêm mới thành công";
        $return_data["callback"] = "defaultCallbackSubmit";
        echo json_encode($return_data);
        return TRUE;
    }

    public function detail() {
        $this->load->model("M_purchase_order_detail", "purchase_order_detail");
        $purchase_order_id = $this->input->post("id");
        $purchase_order = $this->model->get($purchase_order_id);
        $purchase_order_detail = $this->purchase_order_detail->get_many_by(["purchase_order_id" => $purchase_order_id]);
        $data = [
            'purchase_order' => $purchase_order,
            'purchase_order_detail' => $purchase_order_detail
        ];
        $content = $this->load->view("site/purchase_order/detail", $data, TRUE);
        $data_return = array(
            "status" => "1",
            "html" => $content,
        );
        echo json_encode($data_return);
        return TRUE;
    }
}