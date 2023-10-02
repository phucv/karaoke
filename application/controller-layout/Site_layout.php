<?php
/**
 * Created by IntelliJ IDEA.
 * User: phamtrong
 * Date: 3/17/16
 * Time: 11:16
 */

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Site_layout
 *
 * @property M_user                user
 * @property OWS_Menu              ows_menu
 * @property M_system_config       system_config
 * @property M_live_class_logs     live_class_log
 * @property OWS_Permission_System ows_permission_system
 * @property M_user_role           user_role
 * @property M_live_class_reviews  live_class_reviews
 * @property OWS_Logs              ows_logs
 */
abstract class Site_layout extends Base_layout {

    public $show_menu = TRUE;
    protected $_settings = array(
        "class"  => "",
        "view"   => "",
        "model"  => "",
        "object" => "",
    );
    protected $_lang = array(
        "key"   => "",
        "value" => "",
    );
    protected $_url_action = array(
        "add"             => "",
        "add_save"        => "",
        "edit"            => "",
        "ajax_data_table" => "",
        "detail"          => "",
        "delete"          => "",
        "delete_many"     => "",
        "delete_save"     => "",
        "change_status"   => "",
    );

    function __construct() {
        parent::__construct();
        // set timezone
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $this->load->model('M_user', 'user');
        $this->load->model('M_user_role', 'user_role');
        $this->load->model('M_system_config', 'system_config');
        $this->load->model('M_live_class_logs', 'live_class_log');
        // Check login
        $this->check_login();
        // Check role
        $this->check_role();
        // Load lang
        $this->lang->load(array('site/base_layout'));
        $this->lang->load(array('site/base_layout_main'));
        // show/hide menu
        $this->_set_show_menu();
        // lang
        $this->_set_lang();
        // Set part
        $role_alias = !empty($this->session->userdata('user_data')->role_alias) ? $this->session->userdata('user_data')->role_alias : '';
        $this->set_data_part("title", $this->lang->line('login_title') . "", FALSE);
        $this->_set_favicon();
        $this->set_data_part("keywords", "Edo, Đào tạo nội bộ", FALSE);
        $this->set_data_part("canonical", NULL, FALSE);
        $this->set_data_part("breadcrumb", array("view_file" => "site/base_layout/breadcrumb"), FALSE);
        $this->_set_top_bar();
        $this->set_data_part("side_bar_right", "", FALSE);
        $this->set_data_part("side_bar_absolute", "", FALSE);
        $this->set_data_part("footer", array("view_file" => "site/base_layout/footer"), FALSE);
        $this->set_data_part("assets_footer", ($role_alias == 'contact') ? '' : array("view_file" => "site/base_layout/assets_footer"), FALSE);
        $this->set_data_part("assets_header", array("view_file" => "site/base_layout/assets_header"), FALSE);
        $this->_set_side_bar_left();
        // Set layout
        $this->set_layout_body("site/base_layout/layout_body");
        $this->set_layout_all("site/base_layout/layout_all");
        $this->_save_data_post();
    }

    protected function _set_favicon() {
        $this->load->model("M_system_config", "system_config");
        $favicon = $this->system_config->get_by(["key" => "favicon_company"]);
        $fa = empty($favicon) ? base_url("assets/images/site/favicon.ico") : base_url($favicon->value);
        $this->set_data_part("favicon", $fa, FALSE);
    }

    /**
     * QuynhPN: 16/01/2018 override _set_top_bar to add button Join for live class
     */
    protected function _set_top_bar() {
        $user_id = $this->session->userdata("id");
        $user = $this->user->get($user_id);
        $live = $this->get_class_running();
        if (!empty($live)) {
            $live->join = $this->get_join_live_class($live->live_class_id);
        }
        $view_file = "site/base_layout/top_bar";
        // get teacher rate avg
        $list_cat = array();
        if (!empty($user) && $user->role_alias == "teacher") {
            $user->rate_avg = $this->get_teacher_rate_avg($user->email);
        } elseif (!empty($user) && $user->role_alias == "student") {
            $this->load->library("OWS_Menu");
            $this->load->model("M_object_category", "object_category");
            $list_cat = $this->object_category->get_many_by(['object_type' => 'program']);
            $view_file = "site/base_layout/top_bar_student";
            $menu = $this->ows_menu->get_menu();
        }
        $info_company = $this->system_config->get_by(["key" => "logo_company"]);
        $roles = empty($user->list_role_id) ? [] : explode(",", $user->list_role_id);
        if (count($roles) > 1) {
            $key = array_search($user->role_id, $roles);
            unset($roles[$key]);
            $ls_role = $this->user_role->get_many_by(["id" => $roles]);
        }
        $data = Array(
            'view_file'          => $view_file,
            "user_data"          => $user,
            'current_controller' => $this->router->class,
            'current_method'     => $this->router->method,
            'show_menu'          => $this->show_menu,
            'join_class_running' => empty($live->live_class_id) ? "" : site_url("site/live_class/join/" . $live->live_class_id),
            'class_name'         => empty($live->class_fullname) ? "Live class" : $live->class_fullname,
            'live_class'         => $live,
            'url_logo'           => $info_company->value,
            'ls_role'            => empty($ls_role) ? '' : $ls_role,
        );
        if (!empty($menu)) $data["menu_student"] = $menu;
        if (!empty($user) && $user->role_alias == "student") {
            $data['list_category'] = $list_cat;
        }

        $this->set_data_part('top_bar', $data, FALSE);
    }

    public function get_join_live_class($live_id = 0) {
        $live_log = $this->live_class_log->get_last_log_action(["live_class_id" => $live_id]);
        if (empty($live_log)) {
            $join = 1;
        } else {
            if ($live_log->event_type == "meeting_destroyed") {
                $join = 0;
            } else {
                $join = 1;
            }
        }
        return $join;
    }

    /**
     * MinhNV: get teacher rating
     *
     * @param string $teacher_email
     *
     * @return float|int
     */
    protected function get_teacher_rate_avg($teacher_email = '') {
        $this->load->model("M_live_class_reviews", "live_class_reviews");
        $review_teacher_condition = array(
            'type'            => 'USER',
            'user_role'       => 'MODERATOR',
            'user_email'      => $teacher_email,
            'created_by_role' => 'VIEWER',
        );
        $list_review_of_teacher = $this->live_class_reviews->select_sum_rate($review_teacher_condition);

        if (!empty($list_review_of_teacher)) {
            if (!empty($list_review_of_teacher->number_rate)) {
                return round(($list_review_of_teacher->total_rate / $list_review_of_teacher->number_rate), 1);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * QuynhPN: 16/01/2018 get id of live_class running of user
     *
     * @return int
     */
    protected function get_class_running() {
        $this->load->model("M_users_enroll_class", "users_enroll_class");
        $user_id = $this->session->userdata("id");
        $class_running = $this->users_enroll_class->get_live_running($user_id);
        return $class_running;
    }

    protected function check_login() {
        if (!$this->session->userdata("id") && !$this->session->userdata("contact_id")) {
            $this->redirect_to_login();
        }
    }

    protected function redirect_to_login() {
        $login_link = site_url("site/login");
        $this->session->set_userdata('redirect_login', current_url());
        $this->session->set_flashdata("msg", "<div class='alert alert-warning'>Required login!</div>");
        redirect($login_link);
    }

    protected function check_role() {
        if (!$this->session->userdata("id") && !$this->session->userdata("contact_id")) {
            $this->redirect_to_login();
        } else {
            //check time use system
            if ($this->session->userdata("limit_time_use") === NULL) {
                $this->load->library("OWS_Permission_System");
                $this->ows_permission_system->check_time_use_system();
            }
            // check role
            $role_data = $this->session->userdata("role_data");
            if ($role_data) {
                $role_data_arr = explode(";", $role_data);
                $class = $this->router->class;
                $method = $this->router->method;
                if (in_array("*.*", $role_data_arr)
                    || in_array(trim($class . ".*"), $role_data_arr)
                    || in_array(trim($class . "." . $method), $role_data_arr)
                ) {
                    return TRUE;
                }
            }
            redirect(site_url("site/site_error/error_permission"));
        }
    }

    /**
     * Set hidden menu for student
     */
    protected function _set_show_menu() {
        // get data by group
        $user_data = $this->session->userdata("user_data");
        $role_group = isset($user_data->role_group) ? $user_data->role_group : "view";
        $this->load->config("my_constant_config");
        $role_group_define = $this->config->item("role_group");
        $view = isset($role_group_define["view"]) ? $role_group_define["view"] : array();
        if (in_array($role_group, $view)) {
            $this->show_menu = FALSE;
        }
    }

    /**
     * PhuCV: Set lang
     */
    protected function _set_lang() {
        $site_lang = $this->session->userdata("site_lang");
        $this->_lang["key"] = $site_lang;
        $this->_lang["value"] = $site_lang;
        $this->load->config("my_constant_config");
        $lang_list = $this->config->item("lang_list");
        if (empty($site_lang)) {
            $this->load->config("config");
            $language = $this->config->item("language");
            foreach ($lang_list as $lang_key => $lang_value) {
                if ($language == strtolower($lang_value)) $site_lang = $lang_key;
            }
        } else {
            $language = !empty($lang_list[$site_lang]) ? $lang_list[$site_lang] : "english";
        }
        $this->_lang["key"] = $site_lang;
        $this->_lang["value"] = $language ? $language : "english";
    }

    /**
     * ThieuLM - 26042017 - set topbar
     */
//    protected function _set_top_bar() {
//        $user_id = $this->session->userdata("id");
//        $user = $this->user->get($user_id);
//        $data = Array(
//            'view_file'          => "site/base_layout/top_bar",
//            "user_data"          => $user,
//            'current_controller' => $this->router->class,
//            'current_method'     => $this->router->method,
//            'show_menu'          => $this->show_menu,
//        );
//        $this->set_data_part('top_bar', $data, FALSE);
//    }

    protected function _set_side_bar_left() {
        $this->load->library("OWS_Menu");
        $menu = $this->ows_menu->get_menu();
        $menu = $this->_get_menu_by_role($menu);
        $data = Array(
            'view_file'          => "site/base_layout/side_bar_left",
            "controller"         => "no-controller",
            'menu_data'          => $menu,
            'current_controller' => $this->router->class,
            'current_method'     => $this->router->method,
            'show_menu'          => $this->show_menu,
        );
        $this->set_data_part('side_bar_left', $data, TRUE);
    }

    /**
     * Ham xu ly hien thi menu theo role
     *
     * @param $menu array
     *
     * @return array
     */
    protected function _get_menu_by_role($menu) {
        if (empty($menu)) return array();
        $role_data = $this->session->userdata("role_data");
        $menu_role = array();
        if ($role_data) {
            $role_data_arr = explode(";", $role_data);
            if ($role_data_arr) {
                if (in_array("*.*", $role_data_arr)) {
                    $menu_role = $menu;
                } else {
                    $menu_role = $this->_filter_menu_role($menu, $role_data_arr);
                }
            }
        }
        // clear menu role
        $menu_role = $this->_clear_menu_role($menu_role);
        return $menu_role;
    }

    /**
     * Unset menu role
     *
     * @param $menu_data array
     * @param $role_data array
     *
     * @return array
     */
    protected function _filter_menu_role($menu_data, $role_data) {
        foreach ($menu_data as $key => $value_menu) {
            if (empty($value_menu["child"])) {
                $check_show = FALSE;
                if (isset($value_menu["obj_active"])) {
                    // get array obj active
                    $list_obj_active = explode(";", $value_menu["obj_active"]);
                    // Check allow show menu
                    foreach ($list_obj_active as $obj_active) {
                        $tmp = explode(".", $obj_active);
                        $controller = isset($tmp[0]) ? $tmp[0] : "";
                        $obj_active_all = $controller . ".*";
                        if (in_array($obj_active, $role_data) || in_array($obj_active_all, $role_data)) {
                            $check_show = TRUE;
                        }
                    }
                }
                if (!$check_show) unset($menu_data[$key]);
            } else {
                $res = $this->_filter_menu_role($value_menu["child"], $role_data);
                if (empty($res)) {
                    unset($menu_data[$key]);
                } else {
                    $menu_data[$key]["child"] = $res;
                }
            }
        }
        return $menu_data;
    }

    /**
     * Clear menu role: if menu has 1 child -> convert parent = child
     *
     * @param $menu_data array
     *
     * @return array
     */
    protected function _clear_menu_role($menu_data = array()) {
        foreach ($menu_data as $key => $menu) {
            // check count child = 1
            if (!empty($menu["child"]) && count($menu["child"]) == 1) {
                $menu_data[$key] = reset($menu["child"]); // get first element array
            }
        }
        return $menu_data;
    }

    /**
     * Get view for filter
     */
    protected function _get_view_filter($data = array()) {
        // load css
        if (empty($data["css"])) {
            $this->load_more_css("assets/css/site/base_filter/filter.css", FALSE, TRUE, 'base_filter.css');
        } else {
            if (is_string($data["css"])) $data["css"] = array($data["css"]);
            $this->load_more_css($data["css"], FALSE, TRUE, __FUNCTION__);
        }
        // load js
        if (empty($data["js"])) {
            $this->load_more_js("assets/js/site/base_filter/filter.js", FALSE, TRUE, 'base_filter.js');
            $this->load_more_js("assets/plugins-bower/select2/dist/js/i18n/" . $this->_lang["key"] . ".js");
        } else {
            if (is_string($data["js"])) $data["js"] = array($data["js"]);
            $this->load_more_js($data["js"], FALSE, TRUE, __FUNCTION__);
        }
        // get default limit
        $this->load->library("OWS_Filter");
        $ows_filter = new OWS_Filter();
        $filter_view_folder = $ows_filter->_filter_view_folder;
        $filter_parts = $this->_get_part_filter($filter_view_folder);
        $ows_filter->set_part($filter_parts);
        $return_data = $ows_filter->get_filter($data);
        return $return_data;
    }

    protected function _get_part_filter($filter_view_folder) {
        $part = array();
        $list_data_default = array(
            array(
                "id"   => "video",
                "name" => "Video",
            ),
        );
        $data_filter_default = array(
            "label"        => "Bài học",
            "label_class"  => "label-lesson",
            "data_default" => "Tất cả",
            "filter_field" => "lesson",
            "list_data"    => json_decode(json_encode($list_data_default)),
        );
        $part["row-1"] = array(
            "part-11 width-25"   => array(
                array(
                    "view"  => $filter_view_folder . "/part_search",
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
                array(
                    "view" => $filter_view_folder . "/part_filter_custom_btn",
                ),
            ),
        );
        // Overwrite $part - row-2
        $part["row-2 row-custom"] = array(
            "part-21 block-part" => array(
                array(
                    "view"       => $filter_view_folder . "/part_select",
                    "data"       => $data_filter_default,
                    "class"      => "filter-custom-row filter-select select-quick",
                    "attributes" => "action-type='1'",
                ),
                array(
                    "view"       => $filter_view_folder . "/part_select",
                    "data"       => $data_filter_default,
                    "class"      => "filter-custom-row filter-select hidden",
                    "attributes" => "action-type='0'",
                ),
                array(
                    "view"       => $filter_view_folder . "/part_input",
                    "data"       => array(),
                    "class"      => "filter-custom-row filter-input hidden",
                    "attributes" => "action-type='0'",
                ),
                array(
                    "view"  => $filter_view_folder . "/part_filter_custom_btn_close",
                    "data"  => array(),
                    "class" => "filter-custom-row popup-row-btn hidden",
                ),
            ),
        );
        $part["row-3"] = array(
            "part31"            => array(
                array(
                    "view" => $filter_view_folder . "/part_count_row",
                ),
            ),
            "part32 right-part" => array(
                array(
                    "view" => $filter_view_folder . "/part_show_selected",
                    "data" => array("url_delete_many" => site_url($this->_url_action["delete_many"])),
                ),
            ),
        );
        return $part;
    }

    /**
     * ThieuLM: overwrite show_page_blank: add data show_menu
     */
    protected function show_page_blank($content) {
        $data["show_menu"] = $this->show_menu;
        $data["title"] = $this->html["title"];
        $data["description"] = $this->html["description"];
        $data["keywords"] = $this->html["keywords"];
        $data["canonical"] = $this->html["canonical"];
        $data["favicon"] = $this->html["favicon"];
        $data["meta_sharing"] = $this->html["meta_sharing"];
        $data["assets_header"] = $this->html["assets_header"];
        $data["assets_footer"] = $this->html["assets_footer"];
        $data["tag_manager"] = $this->html["tag_manager"];
        $data["json_barrier"] = $this->json_barrier;
        $data["content"] = $content;
        $this->load->view($this->get_layout_all(), $data);
    }

    /**
     * Custom name order of live class
     **/
    protected function _custom_name_class($name, $order) {
        if (is_numeric($order)) {
            if ($order < 10) {
                $order = "00" . $order;
            } elseif ($order >= 10 && $order < 100) {
                $order = "0" . $order;
            }
        }
        $name = $name . $order;
        return $name;
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

    /**
     * destroy specific session id
     *
     * @param string $session_id_to_destroy
     */
    protected function destroy_session_id($session_id_to_destroy = '') {
        // 1. commit session if it's started.
        if (session_id()) {
            session_commit();
        }
        // 2. store current session id
        session_start();
        $current_session_id = session_id();
        session_commit();
        // 3. hijack then destroy session specified.
        session_id($session_id_to_destroy);
        session_start();
        session_destroy();
        session_commit();
        // 4. restore current session id. If don't restore it, your current session will refer     to the session you just destroyed!
        session_id($current_session_id);
        session_start();
        session_commit();
    }

    /**
     * get role config access for view
     *
     * @return mixed
     */
    public function get_role_view() {
        if (!$this->session->userdata("id")) {
            $this->redirect_to_login();
        } else {
            // check role
            $role_data_view = $this->session->userdata("role_data_view");
            if ($role_data_view) {
                $class = $this->router->class;
                return $role_data_view[$class];
            }
        }
    }

    /**
     * Get config upload_max_size
     *
     * @return float|int
     */
    protected function _get_upload_max_size() {
        $config_upload_max_size = $this->system_config->get_by(["key" => "upload_max_size"]);
        return empty($config_upload_max_size) ? 60 * 1024 : 1024 * intval($config_upload_max_size->value);
    }

    protected function _save_data_post() {
        $this->load->config("my_service_config");
        $method_save_data = $this->config->item("method_save_data");
        $function = $this->router->method;
        $class = $this->router->class;
        $method = $this->input->method();
        if (!empty($method_save_data[$method][$class]) && in_array($function, $method_save_data[$method][$class])) {
            $date = date("Y-m-d");
            $this->ows_logs->save_log("log_data/$date/$class", date("H:i:s") . " " . json_encode($this->input->$method()), $function);
        }
    }
}
