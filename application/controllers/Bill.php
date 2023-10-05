<?php

/**
 * Class Bill
 * @property M_bill model
 * @property M_bill_detail bill_detail
 * @property M_product product
 */
class Bill extends Site_base {

    function setting_base() {
        $this->_settings = array(
            "class" => "bill",
            "view" => "site/bill",
            "model" => "M_bill",
        );
    }

    public function index() {
        $data = [
            'title' => 'Danh sách hoá đơn',
            'more_css' => [
                'assets/css/site/base_manager/table.css',
                'assets/css/site/work/work.css'
            ],
            'more_js' => [
                'assets/js/site/base_manager/report_management.js',
            ]
        ];
        $this->manager($data);
    }

    public function get_data($data = []) {
        $get_data = parent::get_data($data);
        $get_data['url_bill_detail'] = site_url('bill/detail');
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

    public function detail() {
        $this->load->model("M_bill_detail", "bill_detail");
        $bill_id = $this->input->post("id");
        $bill = $this->model->get($bill_id);
        $bill_detail = $this->bill_detail->get_many_by(["bill_id" => $bill_id]);
        $data = [
            'bill' => $bill,
            'bill_details' => $bill_detail
        ];
        $product_ids = [];
        foreach ($bill_detail as $detail) {
            $detail->product_id && $product_ids[] = $detail->product_id;
        }
        $this->load->model("M_product", "product");
        $this->product->_with_deleted();
        $products = count($product_ids) ? $this->product->get_many_by(["id" => $product_ids]) : [];
        foreach ($products as $product) {
            $data["products"][$product->id] = $product;
        }
        $content = $this->load->view("site/bill/detail", $data, TRUE);
        $data_return = array(
            "status" => "1",
            "html" => $content,
        );
        echo json_encode($data_return);
        return TRUE;
    }
}