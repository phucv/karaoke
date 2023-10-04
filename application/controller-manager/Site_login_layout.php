<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Site_login_layout
 * @property M_user user
 * @property M_user_role user_role
 */
abstract class Site_login_layout extends Base_layout {

    function __construct() {
        parent::__construct();
        // Set part
        $this->set_data_part("title", "Karaoke", FALSE);
        $this->set_data_part("favicon", base_url("assets/images/site/favicon.ico"), FALSE);
        $this->set_data_part("keywords", "Karaoke", FALSE);
        $this->set_data_part("canonical", NULL, FALSE);

        $this->set_data_part("breadcrumb", "", FALSE);
        $this->set_top_bar();
        $this->set_data_part("side_bar_left", "", FALSE);
        $this->set_data_part("side_bar_right", "", FALSE);
        $this->set_data_part("side_bar_absolute", "", FALSE);
        $this->set_data_part("footer", array("view_file" => "site/base_layout/login/footer"), FALSE);
        $this->set_data_part("assets_footer", array("view_file" => "site/base_layout/login/assets_footer"), FALSE);
        $this->set_data_part("assets_header", array("view_file" => "site/base_layout/login/assets_header"), FALSE);
        // Set layout
        $this->set_layout_body("site/base_layout/login/layout_body");
        $this->set_layout_all("site/base_layout/login/layout_all");
        $this->load->model('M_user', 'user');
        $this->load->model('M_user_role', 'user_role');
    }

    protected function set_top_bar() {
        $data = array(
            'view_file' => "site/base_layout/login/top_bar",
            "logo" => base_url('assets/images/site/logo.png')
        );
        $this->set_data_part('top_bar', $data, FALSE);
    }

    /**
     * MinhNV: 30/01/2018 to check and convert vietnamese string to non-signed Vietnamese
     *
     * @param $str
     *
     * @return mixed
     */
    public function convert_vietnamese_string($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", "a", $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", "e", $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", "i", $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", "o", $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", "u", $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", "y", $str);
        $str = preg_replace("/(đ)/", "d", $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", "A", $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", "E", $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", "I", $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", "O", $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", "U", $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", "Y", $str);
        $str = preg_replace("/(Đ)/", "D", $str);
        $str = str_replace(" ", "_", str_replace("&*#39;", "", $str));
        return $str;
    }
}
