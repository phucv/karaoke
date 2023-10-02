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
 * Class Base_layout
 *
 * Import CI param for suggest code in IDE
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge         $dbforge
 * @property CI_Benchmark        $benchmark
 * @property CI_Calendar         $calendar
 * @property CI_Cart             $cart
 * @property CI_Config           $config
 * @property CI_Controller       $controller
 * @property CI_Email            $email
 * @property CI_Encrypt          $encrypt
 * @property CI_Exceptions       $exceptions
 * @property CI_Ftp              $ftp
 * @property CI_Hooks            $hooks
 * @property CI_Image_lib        $image_lib
 * @property CI_Input            $input
 * @property CI_Lang             $lang
 * @property CI_Log              $log
 * @property CI_Output           $output
 * @property CI_Pagination       $pagination
 * @property CI_Parser           $parser
 * @property CI_Profiler         $profiler
 * @property CI_Session          $session
 * @property CI_Sha1             $sha1
 * @property CI_Table            $table
 * @property CI_Trackback        $trackback
 * @property CI_Typography       $typography
 * @property CI_Unit_test        $unit_test
 * @property CI_Upload           $upload
 * @property CI_URI              $uri
 * @property CI_User_agent       $user_agent
 * @property CI_Validation       $validation
 * @property CI_Xmlrpc           $xmlrpc
 * @property CI_Xmlrpcs          $xmlrpcs
 * @property CI_Zip              $zip
 * @property CI_Loader           $load
 * @property CI_Router           $router
 * @property Ion_auth_model      $ion_auth
 * @property MY_Form_validation  $form_validation
 * @property Crud_manager        $model
 *
 */
abstract class Base_layout extends CI_Controller {

    protected $json_barrier = ";mtb;";
    protected $html = Array();
    protected $html_append = Array();
    protected $html_prepend = Array();
    protected $data = Array();
    private $layout_body = "admin/base_layout/layout_body";
    private $layout_all = "admin/base_layout/layout_all";
    private $parts = Array(
        "title",
        "description",
        "favicon",
        "keywords",
        "canonical",
        "breadcrumb",
        "top_bar",
        "side_bar_left",
        "side_bar_right",
        "side_bar_absolute",
        "footer",
        "assets_footer",
        "assets_header",
        "meta_sharing",
        "tag_manager" // tag manager element: Google tag manager (Tracking data, remarketing, ...)
    );

    protected $_role_manager = ["company", "tester", "manager_tester", 'rnd', 'training_department'];

    protected $_url_redirect = [
        "company"             => "site/manage_student",
        "tester"              => "site/work_schedule",
        "manager_tester"      => "site/manage_test_schedule",
        'rnd'                 => "site/list_program",
        'training_manager'    => "site/dashboard_manager",
        'teacher'             => "site/class_study",
        'training_department' => "site/manage_class",
    ];

    function __construct() {
        parent::__construct();
        $this->check_maintain();
        $this->config->load("my_service_config");
        $this->config->load("my_constant_config");
        foreach ($this->parts as $item) {
            $function_name = "set_default_" . $item;
            $temp_data = $this->$function_name();
            $this->data[$item] = $temp_data;
        }
        // set language for this session
        // if is set user language, change it by user
        $site_lang = $this->session->userdata('site_lang');
        if (empty($site_lang)) {
            $site_lang = 'vi';
        }
        $lang_list_define = $this->config->item("lang_list");
        $this->config->set_item('language', empty($lang_list_define[$site_lang]) ? 'vietnamese' : $lang_list_define[$site_lang]);
        // Log access
//        $this->log_access();
    }

    /**
     * check system maintain by role
     */
    public function check_maintain() {
        $user_data = $this->session->userdata("user_data");
        $user_role = empty($user_data) ? "" : $user_data->role_alias;
        if (empty($user_role)) {
            $url = current_url();
            $url = explode("/", $url);
            $role = $url[count($url) - 1];
            if ($role == "cpanel") {
                $user_role = "admin";
            } else {
                $user_role = $role;
            }
        }
        if ($user_role != "admin") {
            $this->lang->load("site/system_maintain");
            $this->load->model("M_config_maintain", "config_maintain");
            $maintain = $this->config_maintain->get_by(["time_start <=" => date('Y-m-d H:i:s'), "time_end >=" => date('Y-m-d H:i:s'), 'status' => 1]);
            if (!empty($maintain)) {
                $content = $this->load->view("site_errors/html/system_maintain", ['maintain_current' => $maintain], TRUE);
                echo $content;
                exit;
            }
        }
    }
    /**
     * @param $layout_body
     */
    protected function set_layout_body($layout_body) {
        $this->layout_body = $layout_body;
    }

    protected function get_layout_body() {
        return $this->layout_body;
    }

    /**
     * @param $layout_all
     */
    protected function set_layout_all($layout_all) {
        $this->layout_all = $layout_all;
    }

    protected function get_layout_all() {
        return $this->layout_all;
    }

    protected function render_html_part() {
        foreach ($this->parts as $part) {
            if (isset($this->data[$part])) {
                $part_data = $this->data[$part];
                if (is_array($part_data) && isset($part_data["view_file"])) {
                    $part_html = $this->load->view($part_data["view_file"], $part_data, TRUE);
                } else {
                    if (is_string($part_data)) {
                        $part_html = $part_data;
                    } else {
                        throw new Exception("Part '$part' data must be array with view_file or string");
                    }
                }
            } else {
                $part_html = "";
            }
            if (isset($this->html_append[$part])) {
                $part_html = $part_html . $this->html_append[$part];
            }
            if (isset($this->html_prepend[$part])) {
                $part_html = $this->html_prepend[$part] . $part_html;
            }
            $this->html[$part] = $part_html;
        }
    }

    /**
     * Show content in layout
     *
     * @param $content string content which need to show
     *
     * @throws Exception
     */
    protected function show_page($content) {
        $data = Array();
        $this->load->model("M_config_maintain", "config_maintain");
        $this->db->order_by('time_start', 'asc');
        $maintain = $this->config_maintain->get_by(["status" => 1, "time_start >" => date('Y-m-d H:i:s')]);
        $this->render_html_part();
        /* get html  */
        $data["maintain"] = $maintain;
        $data["top_bar"] = $this->html["top_bar"];
        $data["breadcrumb"] = $this->html["breadcrumb"];
        $data["content"] = $content;
        $data["side_bar_left"] = $this->html["side_bar_left"];
        $data["side_bar_right"] = $this->html["side_bar_right"];
        $data["side_bar_absolute"] = $this->html["side_bar_absolute"];
        $data["footer"] = $this->html["footer"];
        $new_content = $this->load->view($this->layout_body, $data, TRUE);
        $this->show_page_blank($new_content);
    }

    protected function show_page_blank($content) {
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
        $this->load->view($this->layout_all, $data);
    }

    /**
     * Set data or html of one part in master page
     *
     * @see Master_layout::$parts
     *
     * @param String  $part          Part want to set
     * @param         $data          //data want to set
     *                               pass $data['view_file'] in want to change view
     * @param boolean $merge         True if want to merge(overrider) with old data, FALSE if want to remove old data
     *                               and replace
     * @param boolean $is_home_main
     */
    final protected function set_data_part($part, $data, $merge = TRUE, $is_home_main = FALSE) {
        if ($part == 'top_bar' && !$is_home_main) {
            if (!$this->session->userdata('show_warning_maintain')) {
                $this->lang->load(array('site/system_maintain'));
                $this->load->model("M_config_maintain", "config_maintain");
                $this->db->order_by('time_start', 'asc');
                $maintain = $this->config_maintain->get_by(["status" => 1, "time_start >" => date('Y-m-d H:i:s'), "time_start <" => date('Y-m-d H:i:s', strtotime("+ 7 days"))]);
            }
            $data_warning_maintain = empty($maintain) ? '' : $this->load->view('site/system_maintain/warning_header', array('maintain' => $maintain), TRUE);
            if (!empty($data_warning_maintain))
                $data['warning_maintain'] = $data_warning_maintain;
        }
        $this->_validate_part($part);
        if ($merge) {
            $data = array_replace_recursive($this->data[$part], $data);
        }
        $this->data[$part] = $data;
    }

    /**
     * Set html for a part
     *
     * @see Master_layout::$parts
     *
     * @param String $part Part want to set
     * @param String $html Html set to part
     */
    final protected function set_html_part($part, $html) {
        $this->html[$part] = $html;
    }

    /**
     * append html to part of master page
     *
     * @param String $part Part want to append
     * @param String $html html append
     */
    final protected function append_part($part, $html) {
        $this->_validate_part($part);
        if (isset($this->html_append[$part])) {
            $this->html_append[$part] = $this->html_append[$part] . $html;
        } else {
            $this->html_append[$part] = $html;
        }
    }

    /**
     * prepend html to part of master page
     *
     * @param String $part Part want to prepend
     * @param String $html html prepend
     */
    final protected function prepend_part($part, $html) {
        $this->_validate_part($part);
        if (isset($this->html_prepend[$part])) {
            $this->html_prepend[$part] = $html . $this->html_prepend[$part];
        } else {
            $this->html_prepend[$part] = $html;
        }
    }


    /**
     * Get data of one part in master page
     *
     * @see Master_layout::$parts for parts name
     *
     * @param NULL|String|Array $parts                  String or Array of part want to get
     * @param boolean           $include_prepend_append TRUE if want to include html prepend and html append, FALSE to
     *                                                  get data only
     *
     * @return String|Array Data or Html of parts input
     * @throws Exception Invalid param, first param must be 'string' or 'array'!
     */
    final protected function get_parts($parts = NULL, $include_prepend_append = TRUE) {
        if ($parts === NULL) {
            $parts = $this->parts;
        }
        if (is_array($parts)) {
            $return = Array();
            foreach ($parts as $item) {
                $return[$item] = $this->get_parts($item, $include_prepend_append);
            }
            return $return;
        } elseif (is_string($parts)) {
            $this->_validate_part($parts);
            $part_data = $this->data[$parts];
            if ($include_prepend_append) {
                if (isset($this->html_prepend[$parts])) {
                    $part_data['html_prepend'] = $this->html_prepend[$parts];
                }
                if (isset($this->html_append[$parts])) {
                    $part_data['html_append'] = $this->html_append[$parts];
                }
            }
            return $part_data;
        }
        throw new Exception("Invalid param, first param must be 'string' or 'array'!");
    }

    private function _validate_part($part) {
        if (!is_string($part)) {
            throw new Exception('Invalid param, part name must be "string" !');
            return FALSE;
        }
        if (!in_array($part, $this->parts)) {
            throw new Exception("Part '" . $part . "' not exist!");
            return FALSE;
        }
        return TRUE;
    }


    private function set_default_title($title = NULL) {
        return $title ? $title : "Manager System";
    }

    private function set_default_description($description = NULL) {
        return $description ? $description : "Manager System";
    }

    private function set_default_keywords($keywords = NULL) {
        return $keywords ? $keywords : "Manager, Admin, Backend";
    }

    private function set_default_canonical($canonical = NULL) {
        return NULL;
    }

    private function set_default_top_bar() {
        $data = Array();
        $data["is_login"] = FALSE; //TODO: this for demo only, use session to check login when implement
        $data["logout_url"] = site_url('admin/login/logout');
        $data["changer_info_url"] = site_url('admin/my_account');
        $data["logo"] = base_url("images/logo_white.png");
        $data["avatar"] = base_url("images/default_avatar.png");
        $data["view_file"] = "admin/base_layout/top_bar";
        return $data;
    }

    /**
     * Load more css
     *
     * @param String|array $file_path File want to load
     * @param bool         $at_footer True if want to load at footer assets, false if want to load ad header asset
     * @param bool         $minify
     * @param null         $file_name
     */
    protected function load_more_css($file_path, $at_footer = FALSE, $minify = FALSE, $file_name = NULL) {
        $html = "";
        if ($minify) {
            $html = minify_css_js('css', $file_path, $file_name);
        } else {
            if (is_string($file_path)) $file_path = [$file_path];
            foreach ($file_path as $file) {
                $html .= '<link rel="stylesheet" href="' . base_url($file) . '"/>';
            }
        }
        if ($at_footer) {
            $part = 'assets_footer';
        } else {
            $part = 'assets_header';
        }
        $this->append_part($part, $html);
    }

    /**
     * Load more js
     *
     * @param String|array $file_path File want to load
     * @param bool         $at_footer True if want to load at footer assets, false if want to load ad header asset
     * @param bool         $minify
     * @param null         $file_name
     */
    protected function load_more_js($file_path, $at_footer = FALSE, $minify = FALSE, $file_name = NULL) {
        $html = "";
        if ($minify) {
            $html = minify_css_js('js', $file_path, $file_name);
        } else {
            if (is_string($file_path)) $file_path = [$file_path];
            foreach ($file_path as $file) {
                $html .= '<script src="' . base_url($file) . '"></script>';
            }
        }
        if ($at_footer) {
            $part = 'assets_footer';
        } else {
            $part = 'assets_header';
        }
        $this->append_part($part, $html);
    }

    private function set_default_breadcrumb() {
        $data = Array();
        $data["view_file"] = "admin/base_layout/breadcrumb";
        return $data;
    }

    private function set_default_side_bar_left() {
        $data = Array();
        //TODO: handle view for menu permisson at here
        $data["view_file"] = "admin/base_layout/side_bar_left";
        return $data;
    }

    private function set_default_side_bar_right() {
        $data = Array();
        $data["view_file"] = "admin/base_layout/side_bar_right";
        return $data;
    }

    private function set_default_side_bar_absolute() {
        $data = Array();
        //TODO: handle view for menu permisson at here
        $data["view_file"] = "admin/base_layout/side_bar_absolute";
        return $data;
    }

    private function set_default_footer() {
        $data = Array();
        $data["view_file"] = "admin/base_layout/footer";
        return $data;
    }

    private function set_default_assets_footer() {
        $data = Array();
        $data["view_file"] = "admin/base_layout/assets_footer";
        return $data;
    }

    private function set_default_assets_header() {
        $data = Array();
        $data["view_file"] = "admin/base_layout/assets_header";
        return $data;
    }

    private function set_default_favicon() {
        return base_url("/images/favicon.png");
    }

    private function set_default_meta_sharing() {
        // https://developers.facebook.com/docs/sharing/webmasters/
        $this->load->model("M_system_config", "system_config");
        $meta_data = $this->system_config->get_by(["key" => "meta_data"]);
        $data_default = Array(
            'meta_title'       => 'CloudClass',
            'meta_description' => 'Giải pháp E-Learning toàn diện cho các cá nhân và tổ chức đào tạo.',
            'meta_image'       => 'assets/images/site/base_layout/image_login.png',
        );
        $data = empty($meta_data) ? $data_default : json_decode($meta_data->value, TRUE);
        $data["view_file"] = "site/base_layout/meta_sharing";
        return $data;
    }

    /**
     * Set view default for tag manager (tracking data, marketing)
     **/
    private function set_default_tag_manager() {
        // https://developers.facebook.com/docs/sharing/webmasters/
        $data = Array();
        $this->load->model("M_system_config", "system_config");
        $bugsnag = $this->system_config->get_by(["key" => "bugsnagId"]);
        $data["bugsnag_conf"] = empty($bugsnag->value) ? "" : $bugsnag->value;

        $ga = $this->system_config->get_by(["key" => "ga_id"]);
        $data["ga_id"] = empty($ga->value) ? "" : $ga->value;

        $chat = $this->system_config->get_by(["key" => "live_chat_tawk_id"]);
        $data["live_chat_tawk_id"] = empty($chat->value) ? "" : $chat->value;
        $data["hook_capture_event"] = $this->config->item("hook_capture_event");
        $data["view_file"] = "site/base_layout/tag_manager";
        return $data;
    }

    public function random_string($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($index = 0; $index < $length; $index++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    protected function log_access() {
        $this->load->model("m_log_access", "log_access");
        if (!$this->input->is_ajax_request()) {// page refreshed
            $this->load->library('user_agent');
            $data_log = array(
                'user_id'    => !empty($this->session->has_userdata('id')) ? $this->session->userdata('id') : NULL,
                'browser'    => $this->agent->browser() ? $this->agent->browser() . ' ' . $this->agent->version() : NULL,
                'ip_address' => array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $this->input->ip_address(),
            );
            $this->log_access->insert($data_log);
        }
    }

    protected function _load_asset_video_js() {
        $this->load_more_css(array(
             "assets/css/base/videojs/cdnvideo-js.css",
             "assets/css/base/videojs/video-js.css",
             "assets/css/base/videojs/videojs.watermark.css",
         ), FALSE, TRUE, 'video_js_min_css');
        $this->load_more_js(["assets/js/base/videojs/video.js"], FALSE, TRUE, "video_js");
        $this->load_more_js(["assets/js/base/videojs/videojs-http-streaming.js"], FALSE, TRUE, "video_js_streaming");
        $this->load_more_js(["assets/js/base/videojs/videojs-hls-quality-selector.js"], FALSE, TRUE, "video_js_quality_selector");
        $this->load_more_js(["assets/js/base/videojs/videojs-contrib-quality-levels.js"], FALSE, TRUE, "video_js_quality_levels");
        $this->load_more_js(["assets/js/base/videojs/videojs.watermark.js"], FALSE, TRUE, "video_js_watermark");
    }
}
