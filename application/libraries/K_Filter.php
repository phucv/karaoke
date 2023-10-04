<?php

class K_Filter {
    public $_filter_view_folder;
    protected $_ci;
    protected $_filter_parts;
    protected $_filter_view_path;

    public function __construct() {
        $this->_ci = &get_instance();
        $this->_filter_view_folder = "site/base_layout/layout_filter";
        $this->_filter_view_path = $this->_filter_view_folder . "/filter.php";
    }

    /**
     * Set filter
     * @param null $view
     */
    public function set_filter($view = NULL) {
        if (!$view) {
            $view = $this->_filter_view_folder . "/filter.php";
        }
        $this->_filter_view_path = $view;
    }

    /**
     * Get html filter
     * @param array $data
     * @return mixed
     */
    public function get_filter($data = array()) {
        $view_path = $this->_filter_view_path;
        $data["filter_view"] = $this->get_html_part();
        return $this->_ci->load->view($view_path, $data, TRUE);
    }

    public function get_html_part() {
        $html_part = array();
        $parts = $this->_filter_parts;
        foreach ($parts as $key_rows => $rows) {
            foreach ($rows as $key_row_parts => $row_parts) {
                foreach ($row_parts as $key_subpart => $subpart) {
                    $view = $subpart["view"];
                    $data = isset($subpart["data"]) ? $subpart["data"] : array();
                    $class = isset($subpart["class"]) ? $subpart["class"] : NULL;
                    $attributes = isset($subpart["attributes"]) ? $subpart["attributes"] : NULL;
                    $html_part[$key_rows][$key_row_parts][$key_subpart] = array(
                        "html"  => $this->_ci->load->view($view, $data, TRUE),
                        "class" => $class,
                        "attributes" => $attributes,
                    );
                    //clearing the cached variables for views
                    $this->_ci->load->clear_vars();
                }
            }
        }
        return $html_part;
    }

    /**
     * Set part of filter
     * @param $part
     */
    public function set_part($part) {
        $this->_filter_parts = $part;
    }

    /**
     * Get part of filter
     */
    public function get_part() {
        return $this->_filter_parts;
    }
}