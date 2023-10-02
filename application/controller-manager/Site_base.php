<?php

/**
 * Created by IntelliJ IDEA.
 * User: Thieu-LM
 * Date: 14/04/2017
 * Time: 9:25 PM
 *
 */
abstract class Site_base extends Site_layout {

    protected $_data_condition_default = array(
        "limit"    => 30,
        "offset"   => 0,
        "order_by" => NULL,
    );

    public function __construct() {
        parent::__construct();
        $this->setting_base();
        $this->setting_url();
        $this->load->model($this->_settings["model"], "model");
        $key = $this->model->get_primary_key();
        if (!empty($key)) {
            $this->_data_condition_default["order_by"] = array(
                $key => "desc",
            );
        }
        // Load lang
        $this->lang->load(array('site/site_base'));
    }
    var $paging_item_display = 7;

    /**
     * ThieuLM - 23042017: Abstract function for setting
     *
     * @return mixed
     */
    abstract function setting_base();

    /**
     * ThieuLM - 23042017: setting for url action
     */
    public function setting_url() {
        $class = $this->_settings["class"];
        $this->_url_action = array(
            "add"             => $class . "/add",
            "add_save"        => $class . "/add_save",
            "edit"            => $class . "/edit",
            "ajax_data_table" => $class . "/ajax_data_table",
            "detail"          => $class . "/detail",
            "delete"          => $class . "/delete",
            "delete_many"     => $class . "/delete",
            "delete_save"     => $class . "/delete_save",
            "change_status"   => $class . "/change_status",
        );
    }

    /**
     * ThieuLM - 23042017: method view list data
     */
    public function index() {
        $data = array();
        $this->manager($data);
    }

    protected function _load_assets_manager($data = array()) {
        $data["more_css"] = empty($data["more_css"]) ? array() : $data["more_css"];
        $data["more_js"] = empty($data["more_js"]) ? array() : $data["more_js"];
        $setting_view = $this->_settings["view"];
        if (file_exists("assets/css/" . $setting_view . "/manage.css")) {
            $this->load_more_css("assets/css/" . $setting_view . "/manage.css", FALSE, TRUE, 'base_manager');
        } else {
            $this->load_more_css("assets/css/site/base_manager/manage.css", FALSE, TRUE, 'base_manager.css');
        }
        if (file_exists("assets/js/" . $setting_view . "/manage.js")) {
            $this->load_more_js("assets/js/" . $setting_view . "/manage.js", FALSE, TRUE, 'base_manager');
        } else {
            $this->load_more_js("assets/js/site/base_manager/manage.js", FALSE, TRUE, 'base_manager.js');
        }
        if (!empty($data["more_css"])) {
            $this->load_more_css($data["more_css"], FALSE, TRUE);
        }
        if (!empty($data["more_js"])) {
            $this->load_more_js($data["more_js"], FALSE, TRUE);
        }
    }

    protected function manager($data = array()) {
        $this->_load_assets_manager($data);
        $setting_view = $this->_settings["view"];
        // title for manager
        if (empty($data["title"])) {
            $this->data["title"] = get_string("c-index-title") . " " . $this->_settings["object"];
        } else {
            $this->data["title"] = $data["title"];
        }
        // breadcrumb
        if (empty($data['breadcrumb'])) $data['breadcrumb'] = '';
        $data["title"] = $this->data["title"];
        $data["object_class"] = $this->_settings["object"];
        // url for ajax
        $data["url_add"] = site_url($this->_url_action["add"]);
        $data["url_ajax_data_table"] = site_url($this->_url_action["ajax_data_table"]);
        // get default limit
        $data["default_limit"] = $this->_data_condition_default["limit"];
        $data["default_offset"] = $this->_data_condition_default["offset"];
        $data["manage_filter"] = $this->_get_view_filter($data);
        if (file_exists(APPPATH . "views/" . $setting_view . "/content.php")) {
            $content = $this->load->view($setting_view . "/content", $data, TRUE);
        } else {
            $content = $this->load->view("site/base_layout/layout_manager/content", $data, TRUE);
        }
        $this->show_page($content);
    }

    public function ajax_data_table($data = array()) {
        if (!$this->input->is_ajax_request()) return FALSE;
        $data_condition = $this->input->post();
        $data = $this->_get_data($data);
        // get url for href
        $data["url_change_status"] = site_url($this->_url_action["change_status"]);
        $data["url_edit"] = site_url($this->_url_action["edit"]);
        $data["url_delete"] = site_url($this->_url_action["delete"]);
        $data["url_delete_many"] = site_url($this->_url_action["delete_many"]);
        $data["url_detail"] = site_url($this->_url_action["detail"]);
        if (file_exists(APPPATH . "views/" . $this->_settings["view"] . "/manage_table.php")) {
            $content_table = $this->load->view($this->_settings["view"] . "/manage_table", $data, TRUE);
        } else {
            $content_table = $this->load->view("site/base_layout/layout_manager/manage_table", $data, TRUE);
        }
        $data_return = array(
            "status"      => 1,
            "status_code" => "SUCCESS",
            "count"       => $data["count_record_list_data"],
            "html"        => $content_table,
        );
        if (!empty($data_condition["callback"])) {
            $data_return["callback"] = $data_condition["callback"];
        }
        echo json_encode($data_return);
        return TRUE;
    }

    protected function _get_data($data = []){
        if (!$this->input->is_ajax_request()) return FALSE;
        $data_condition = $this->input->post();
        $list_condition = $this->_process_data_condition($data_condition);
        // set condition
        $limit = isset($list_condition["limit"]) ? $list_condition["limit"] : $this->_data_condition_default["limit"];
        $offset = isset($list_condition["offset"]) ? $list_condition["offset"] : $this->_data_condition_default["limit"];
        $order_by = isset($list_condition["order_by"]) ? $list_condition["order_by"] : $this->_data_condition_default["order_by"];

        $where_condition = isset($list_condition["where"]) ? $list_condition["where"] : array();
        $wherein_condition = isset($list_condition["wherein"]) ? $list_condition["wherein"] : array();
        $like_condition = isset($list_condition["like"]) ? $list_condition["like"] : array();
        // Get data material
        $record_list_data = $this->model->get_list_filter($where_condition, $wherein_condition, $like_condition, $limit, $offset, $order_by);
        $count_record_list_data = $this->model->get_list_filter_count($where_condition, $wherein_condition, $like_condition);
        $data["limit"] = $limit;
        $data["offset"] = $offset;
        $data["record_list_data"] = $record_list_data;
        $data["count_record_list_data"] = $count_record_list_data;
        return $data;
    }

    /**
     * Process data condition
     */
    protected function _process_data_condition($data_condition) {
        if (empty($data_condition)) {
            $field_id = $this->model->get_primary_key();
            $data_return = array(
                "limit"    => $this->_data_condition_default["limit"],
                "offset"   => $this->_data_condition_default["offset"],
                "order_by" => array($field_id => "DESC"),
            );
        } else {
            $data_return = array();
            $data_return["limit"] = isset($data_condition["limit"]) ? $data_condition["limit"] : $this->_data_condition_default["limit"];
            $data_return["offset"] = isset($data_condition["offset"]) ? $data_condition["offset"] : $this->_data_condition_default["offset"];
            $data_return["order_by"] = isset($data_condition["order_by"]["quick_sort"][0]) ? $data_condition["order_by"]["quick_sort"][0] : $this->_data_condition_default["order_by"];
            // filter
            $filter_condition_tmp = isset($data_condition["filter"]) ? $data_condition["filter"] : array();
            $field_filter = $this->_get_field_filter();
            $where_condition = array();
            $wherein_condition = array();
            foreach ($filter_condition_tmp as $key => $filter) {
                foreach ($field_filter["filter"] as $filter_client => $field_table) {
                    if ($filter_client == $key) {
                        if (count($filter) == 1) {
                            foreach ($filter as $filter_0) {
                                if (is_array($filter_0)) {
                                    // include from, to
                                    if (!empty($filter_0["from"])) {
                                        if ($filter_0["from"]) $where_condition[$field_table . " >="] = date("Y-m-d H:i:s", strtotime($filter_0["from"]));
                                    }
                                    if (!empty($filter_0["to"])) {
                                        if ($filter_0["to"]) $where_condition[$field_table . " <="] = date("Y-m-d H:i:s", strtotime($filter_0["to"]) + 86400);
                                    }
                                } else {
                                    $where_condition[$field_table] = $filter_0;
                                }
                            }
                        } else {
                            $wherein_condition[$field_table] = $filter;
                        }
                    }
                }
            }
            $data_return["where"] = $where_condition;
            $data_return["wherein"] = $wherein_condition;
            // search - todo
            $search_condition_tmp = isset($data_condition["search"]["search_all"]) ? $data_condition["search"]["search_all"] : array();
            $search_condition = array();
            $search_condition_value = NULL;

            foreach ($search_condition_tmp as $search_value) {
                $search_condition_value = $search_value;
            }
            if ($search_condition_value) {
                foreach ($this->_get_schema() as $schema) {
                    $search_condition[$schema] = $search_condition_value;
                }
            }
            if (count($search_condition)) $data_return["like"]["or"] = $search_condition;
        }
        return $data_return;
    }

    protected function _get_field_filter() {
        return array(
            "filter" => array(),
        );
    }

    /*
     * get field for filter and search
     * field_filter => field_table
     */

    protected function _get_schema() {
        $schema = $this->model->schema;
        $data_return = array();
        foreach ($schema as $schema_value) {
            $data_return[] = $schema_value["db_field"];
        }
        return $data_return;
    }

    protected function _get_paging($total, $current, $display, $link, $key = "p") {
        $data["total_page"] = $total;
        $data["current_page"] = $current;
        $data["page_link_display"] = $display;
        $data["link"] = $link;

        $data["key"] = $key;
        return $this->load->view("site/base_layout/layout_manager/paging", $data, true);
    }

    /**
     * SET PUBLIC/PRIVATE
     */
    public function change_status() {
        $id = $this->input->post("id");
        $public = $this->input->post("public");
        $public = intval($public);
        if (!$this->model->get($id)) {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-change_status-no_record"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
        if ($this->model->check_field_exist("public")) {
            $status = $this->model->update($id, array("public" => $public));
        } else {
            $status = FALSE;
        }
        if ($status || $status === 0) {
            $return_data = array(
                "status" => 1,
                "msg"    => get_string("c-change_status-success"),
            );
            echo json_encode($return_data);
            return FALSE;
        } else {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-change_status-fail"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
    }

    /**
     * PhuCV - Get list item for select
     * @return bool
     */
    public function list_select() {
        $data = $this->_get_data();
        if (!empty($data["record_list_data"])) {
            foreach ($data["record_list_data"] as $record) {
                $record->name = isset($record->name) ? $record->name : $record->id;
                $record->full_name = isset($record->name) ? $record->name : $record->id;
                $record->id = isset($record->name) ? $record->name : $record->id;
            }
        }
        echo json_encode($data);
        return FALSE;
    }

    public function list_select_id() {
        $data = $this->_get_data();
        if (!empty($data["record_list_data"])) {
            foreach ($data["record_list_data"] as $record) {
                $record->name = !empty($record->name) ? $record->name : $record->id;
                $record->full_name = isset($record->name) ? $record->name : $record->id;
                $record->id = isset($record->id) ? $record->id : $record->name;
            }
        }
        echo json_encode($data);
        return FALSE;
    }

    /**
     * delete
     */
    public function delete() {
        $data_post = $this->input->post();
        if (!$data_post) {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-delete-no_record"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
        $data = array();
        $record_list = $this->model->get_many_by(array("id" => $data_post));
        if (!$record_list) {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-delete-no_record"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
        $data["record_list"] = $record_list;
        $data["url_save_data"] = site_url($this->_url_action["delete_save"]);
        if (file_exists(APPPATH . "views/" . $this->_settings["view"] . "/delete.php")) {
            $content = $this->load->view($this->_settings["view"] . "/delete", $data, TRUE);
        } else {
            $content = $this->load->view("site/base_layout/layout_manager/delete", $data, TRUE);
        }
        $return_data = array(
            "status" => 1,
            "html"   => $content,
        );
        echo json_encode($return_data);
        return TRUE;
    }

    public function delete_save() {
        $data_post = $this->input->post("id");
        if (!$data_post) {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-delete-no_record"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
        if (!is_array($data_post)) $data_post = array($data_post);
        $delete_count = 0;
        foreach ($data_post as $id) {
            $delete_status = $this->model->delete($id);
            if ($delete_status) $delete_count++;
        }
        if ($delete_count) {
            $return_data = array(
                "status" => 1,
                "msg"    => get_string("c-delete_many-success", $delete_count),
            );
            echo json_encode($return_data);
            return TRUE;
        } else {
            $return_data = array(
                "status" => 0,
                "msg"    => get_string("c-delete-fail"),
            );
            echo json_encode($return_data);
            return FALSE;
        }
    }

    /**
     * custom order for class
     *
     * @param string $order
     *
     * @return string
     */
    public function custom_order($order = "") {
        if (is_numeric($order)) {
            if ($order < 10) {
                $order = "00" . $order;
            } elseif ($order >= 10 && $order < 100) {
                $order = "0" . $order;
            }
        }
        return $order;
    }

    public function get_class_fullname($name = "", $order = "") {
        $order = $this->custom_order($order);
        $fullname = $name . $order;
        return $fullname;
    }

    /**
     * QuynhPN: 23/01/2018 custom name of class(name + order)
     *
     * @param int $class_id
     *
     * @return bool|string
     */
    public function custom_name_class($class_id = 0) {
        $this->load->model("M_class", "class");
        $class = $this->class->get($class_id);
        if (!empty($class)) {
            $name = $this->_custom_name_class($class->name, $class->order);
            return $name;
        }
        return FALSE;
    }
}