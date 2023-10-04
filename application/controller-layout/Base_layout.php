<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Class Base_layout
 *
 * Import CI param for suggest code in IDE
 *
 * @property CI_DB_query_builder $db
 * @property CI_DB_forge $dbforge
 * @property CI_Benchmark $benchmark
 * @property CI_Calendar $calendar
 * @property CI_Cart $cart
 * @property CI_Config $config
 * @property CI_Controller $controller
 * @property CI_Email $email
 * @property CI_Encrypt $encrypt
 * @property CI_Exceptions $exceptions
 * @property CI_Ftp $ftp
 * @property CI_Hooks $hooks
 * @property CI_Image_lib $image_lib
 * @property CI_Input $input
 * @property CI_Lang $lang
 * @property CI_Log $log
 * @property CI_Output $output
 * @property CI_Pagination $pagination
 * @property CI_Parser $parser
 * @property CI_Profiler $profiler
 * @property CI_Session $session
 * @property CI_Sha1 $sha1
 * @property CI_Table $table
 * @property CI_Trackback $trackback
 * @property CI_Typography $typography
 * @property CI_Unit_test $unit_test
 * @property CI_Upload $upload
 * @property CI_URI $uri
 * @property CI_User_agent $user_agent
 * @property CI_Validation $validation
 * @property CI_Xmlrpc $xmlrpc
 * @property CI_Xmlrpcs $xmlrpcs
 * @property CI_Zip $zip
 * @property CI_Loader $load
 * @property CI_Router $router
 *
 */
abstract class Base_layout extends CI_Controller {

    protected $json_barrier = ";mtb;";
    protected $html = [];
    protected $html_append = [];
    protected $html_prepend = [];
    protected $data = [];
    private $layout_body = "admin/base_layout/layout_body";
    private $layout_all = "admin/base_layout/layout_all";
    private $parts = [
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
        "tag_manager"
    ];

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        foreach ($this->parts as $item) {
            $function_name = "set_default_" . $item;
            $temp_data = function_exists($function_name) ? $this->$function_name() : "";
            $this->data[$item] = $temp_data;
        }
    }

    private function set_default_title($title = NULL) {
        return $title ? $title : "Manager System";
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
        $data = array();
        $this->render_html_part();
        /* get html  */
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
     * @param String $part Part want to set
     * @param         $data //data want to set
     *                               pass $data['view_file'] in want to change view
     * @param boolean $merge True if want to merge(overrider) with old data, FALSE if want to remove old data
     *                               and replace
     * @see Master_layout::$parts
     *
     */
    final protected function set_data_part($part, $data, $merge = TRUE) {
        $this->_validate_part($part);
        if ($merge) {
            $this->data[$part] = empty($this->data[$part]) ? [] : $this->data[$part];
            $data = array_replace_recursive($this->data[$part], $data);
        }
        $this->data[$part] = $data;
    }

    /**
     * Set html for a part
     *
     * @param String $part Part want to set
     * @param String $html Html set to part
     * @see Master_layout::$parts
     *
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
     * @param NULL|String|Array $parts String or Array of part want to get
     * @param boolean $include_prepend_append TRUE if want to include html prepend and html append, FALSE to
     *                                                  get data only
     *
     * @return String|Array Data or Html of parts input
     * @throws Exception Invalid param, first param must be 'string' or 'array'!
     * @see Master_layout::$parts for parts name
     *
     */
    final protected function get_parts($parts = NULL, $include_prepend_append = TRUE) {
        if ($parts === NULL) {
            $parts = $this->parts;
        }
        if (is_array($parts)) {
            $return = array();
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

    /**
     * Load more css
     *
     * @param String|array $file_path File want to load
     * @param bool $at_footer True if want to load at footer assets, false if want to load ad header asset
     */
    protected function load_more_css($file_path, $at_footer = FALSE) {
        $html = "";
        if (is_string($file_path)) $file_path = [$file_path];
        foreach ($file_path as $file) {
            $html .= '<link rel="stylesheet" href="' . base_url($file) . '"/>';
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
     * @param bool $at_footer True if want to load at footer assets, false if want to load ad header asset
     */
    protected function load_more_js($file_path, $at_footer = FALSE) {
        $html = "";
        if (is_string($file_path)) $file_path = [$file_path];
        foreach ($file_path as $file) {
            $html .= '<script src="' . base_url($file) . '"></script>';
        }
        if ($at_footer) {
            $part = 'assets_footer';
        } else {
            $part = 'assets_header';
        }
        $this->append_part($part, $html);
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
}
