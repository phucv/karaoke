<?php

/**
 * Class Product
 * @property M_product model
 * @property K_Excel k_excel
 */
class Product extends Site_base {

    function setting_base() {
        $this->_settings = array(
            "class" => "product",
            "view" => "site/product",
            "model" => "M_product",
        );
    }

    public function index() {
        $data = [
            'title' => 'Quản lý sản phẩm',
            'more_css' => [
                'assets/css/site/modal.css'
            ],
            'more_js' => [
                'assets/js/site/import.js'
            ],
        ];
        $this->manager($data);
    }

    protected function _get_schema() {
        return ["m.name", "m.code"];
    }

    protected function _get_field_filter() {
        return array(
            "filter" => array("public" => "m.public"),
        );
    }

    public function get_data($data = []) {
        $this->_url_action["edit"] = 'product/add';
        return parent::get_data($data);
    }

    protected function _get_part_filter($filter_view_folder) {
        $part = [];
        $part["row-1"] = array(
            "part-11 width-25" => array(
                array(
                    "view" => $filter_view_folder . "/part_search",
                    "class" => "width-100",
                ),
            ),
            "part-12 right-part" => array(
                array(
                    "view" => $filter_view_folder . "/part_check_type",
                ),
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
        $data["url_save_data"] = site_url("product/add_save");
        // data material
        $data["record_data"] = empty($id) ? NULL : $this->model->get($id);
        $content = $this->load->view("site/product/add", $data, TRUE);
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
        $id = isset($data["id"]) ? $data["id"] : 0;
        $value = [
            'name' => $data["name"],
            'price' => $data["price"],
            'unit' => $data["unit"],
            'code' => $data["code"],
        ];
        if ($id) {
            $this->model->update($id, $value);
        } else {
            $this->model->insert($value);
        }

        $return_data["status"] = 1;
        $return_data["status_code"] = "SUCCESS";
        $return_data["msg"] = $id ? "Cập nhật thành công" : "Thêm mới thành công";
        $return_data["callback"] = "defaultCallbackSubmit";
        echo json_encode($return_data);
        return TRUE;
    }

    public function import_file() {
        $dataReturn = array();
        if (!empty($_FILES['file'])) {
            $this->load->library("K_Excel");
            $data_import = $this->k_excel->get_data_from_excel($_FILES['file']['tmp_name'], 5);
            if ($data_import['state'] == 1 && !empty($data_import['data'][0])) {
                $products = [];
                foreach ($data_import['data'][0] as $product) {
                    if (empty($product[1])) continue;
                    $products[] = [
                        "name" => $product[1],
                        "price" => $product[2],
                        "unit" => $product[3],
                        "code" => $product[4],
                    ];
                }
                if (count($products)) $this->model->insert_batch($products);
                $dataReturn["state"] = 1;
                $dataReturn['msg'] = "Import sản phẩm thành công.";
            } else {
                $dataReturn["state"] = 0;
                $dataReturn['msg'] = "Lỗi khi đọc file";
            }
        } else {
            $dataReturn["state"] = 0;
            $dataReturn['msg'] = "Lỗi file import";
        }
        echo json_encode($dataReturn);
        return TRUE;
    }
}