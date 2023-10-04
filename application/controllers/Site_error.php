<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Error
 */
class Site_error extends Site_layout {

    function __construct() {
        parent::__construct();
    }

    protected function check_role() {
        return TRUE;
    }

    /**
     * Error permission
     */
    public function error_permission() {
        $data = Array();
        $data['current_controller'] = $this->router->class;
        $data["error"] = "Không có quyền truy cập!";
        $content = $this->load->view("site/error/error_permission", $data, TRUE);
        $this->show_page($content);
    }

}