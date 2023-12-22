<?php

/**
 * Class Customer
 * @property M_customer model
 */
class Customer extends Site_base {

    function setting_base() {
        $this->_settings = array(
            "class" => "customer",
            "view" => "site/customer",
            "model" => "M_customer",
        );
    }

    public function index() {
        $data = [
            'title' => 'Quản lý Khách hàng',
            'more_css' => [
                'assets/css/site/modal.css'
            ],
            'more_js' => [
                'assets/css/site/customer/customer.js'
            ],
        ];
        $this->manager($data);
    }

    public function get_data($data = []) {
        $this->_url_action["edit"] = 'customer/add';
        return parent::get_data($data);
    }

    protected function _get_schema() {
        return ["m.name"];
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
            "part-12 right-part" => array(
                array(
                    "view" => $filter_view_folder . "/part_sort",
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

    public function add($id = 0) {
        $title = empty($id) ? "Thêm mới" : "Chỉnh sửa";
        $data = [
            "title" => $title,
        ];
        $data["callback"] = $this->input->post("callback");
        $data["url_save_data"] = site_url("customer/add_save");
        // data material
        $data["record_data"] = empty($id) ? NULL : $this->model->get($id);
        $content = $this->load->view("site/customer/add", $data, TRUE);
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
        if (!$this->input->is_ajax_request() || empty($data['name'])) {
            echo json_encode($return_data);
            return FALSE;
        }
        $id = isset($data["id"]) ? $data["id"] : 0;
        $code = empty($data["code"]) ? "KH" . date("YmdHis") : $data["code"];
        $value = [
            'name' => $data["name"],
            'code' => $code,
            'phone' => $data["phone"],
            'address' => $data["address"],
            'email' => $data["email"],
            'tax_code' => $data["tax_code"],
        ];
        //check code
        $check = [
            "code" => $code
        ];
        if ($id) $check['id !='] = $id;
        if ($this->model->get_by($check)) {
            $return_data["msg"] = "Mã khách hàng " . $code . " đã tồn tại";
            echo json_encode($return_data);
            return FALSE;
        }
        $update = true;
        if ($id) {
            $this->model->update($id, $value);
        } else {
            $update = false;
            $id = $this->model->insert($value);
        }

        $return_data["status"] = 1;
        $return_data["status_code"] = "SUCCESS";
        $return_data["msg"] = $update ? "Cập nhật thành công" : "Thêm mới thành công";
        $return_data["callback"] = "addCustomer";
        $return_data["data"] = $this->model->get($id);
        echo json_encode($return_data);
        return TRUE;
    }
}