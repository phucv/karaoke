<?php

/**
 * Class Product
 * @property M_product model
 * @property K_Excel k_excel
 * @property M_category_product category_product
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
                'assets/css/site/modal.css',
                'assets/css/site/product/product.css'
            ],
            'more_js' => [
                'assets/js/site/import.js',
                'assets/js/site/product/product.js'
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

    protected function _process_data_condition($data_condition) {
        $data_return = parent::_process_data_condition($data_condition);
        $data_return["where"]["parent_id"] = NULL;
        return $data_return;
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
        $this->load->model("M_category_product", "category_product");
        $title = empty($id) ? "Thêm mới" : "Chỉnh sửa";
        $data = [
            "title" => $title,
        ];
        $data["url_save_data"] = site_url("product/add_save");
        // data material
        $data["record_data"] = empty($id) ? NULL : $this->model->get($id);
        $data["child"] = empty($id) ? [] : $this->model->get_many_by(['parent_id' => $id]);
        $data["groups"] = $this->category_product->get_all();
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
            'parent_id' => null,
            'group_id' => $data["group_id"],
            'purchase_price' => empty($data["purchase_price"]) ? 0 : $data["purchase_price"],
            'price' => empty($data["price"]) ? 0 : $data["price"],
            'quantity' => empty($data["quantity"]) ? 0 : $data["quantity"],
            'unit' => $data["unit"],
            'code' => $data["code"],
            'barcode' => $data["barcode"],
        ];
        $this->db->trans_begin();
        $update = false;
        if ($id) {
            $update = true;
            $status = $this->model->update($id, $value);
        } else {
            $status = $id = $this->model->insert($value);
        }
        if (!empty($data["unit_id"])) {
            $child = $this->model->get_many_by(['parent_id' => $id]);
            $child_ids = [];
            foreach ($child as $c) {
                $child_ids[$c->id] = $c->id;
            }
            $unit_name = empty($data["unit_name"]) ? [] : $data["unit_name"];
            $unit_value = empty($data["unit_value"]) ? [] : $data["unit_value"];
            $unit_price = empty($data["unit_price"]) ? [] : $data["unit_price"];
            $unit_code = empty($data["unit_code"]) ? [] : $data["unit_code"];
            $unit_barcode = empty($data["unit_barcode"]) ? [] : $data["unit_barcode"];
            $value_item = [];
            foreach ($data["unit_id"] as $key => $u_id) {
                if ($u_id) unset($child_ids[$u_id]);
                if (!empty($unit_name[$key])) {
                    $tmp = [
                        'name' => $data["name"],
                        'parent_id' => $id,
                        'group_id' => $data["group_id"],
                        'price' => empty($unit_price[$key]) ? 0 : $unit_price[$key],
                        'unit' => $unit_name[$key],
                        'unit_value' => empty($unit_value[$key]) ? 1 : $unit_value[$key],
                        'code' => empty($unit_code[$key]) ? '' : $unit_code[$key],
                        'barcode' => empty($unit_barcode[$key]) ? '' : $unit_barcode[$key],
                    ];
                    if ($u_id) {
                        $this->model->update($u_id, $tmp);
                    } else {
                        $value_item[] = $tmp;
                    }
                }
            }
            if (count($child_ids)) $this->model->delete_many($child_ids);
            if (count($value_item)) $this->model->insert_batch($value_item);
        } else {
            if ($update) {
                $this->model->delete_by(['parent_id' => $id]);
            }
        }
        if ($status) {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }

        $return_data["status"] = 1;
        $return_data["status_code"] = "SUCCESS";
        $return_data["msg"] = $update ? "Cập nhật thành công" : "Thêm mới thành công";
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

    public function delete_save()
    {
        $data_post = $this->input->post("id");
        if ($data_post) {
            if (!is_array($data_post)) $data_post = array($data_post);
            $this->model->delete_by(['parent_id' => $data_post]);
        }
        return parent::delete_save();
    }
}