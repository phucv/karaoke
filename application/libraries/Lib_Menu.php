<?php

/**
 * Class Lib_Menu
 *
 */
class Lib_Menu {

    protected $_ci;

    public function __construct() {
        $this->_ci = &get_instance();
        $this->_ci->load->model('M_config_menu', 'config_menu');
    }

    public function get_menu() {
        $role = $this->_ci->session->userdata("user_data")->role_id;
        $menu = $this->get_user_menu($role);
        $menu = $this->_get_menu_by_role($menu);
        return $menu;
    }

    /**
     * ajax save menu status
     * @param $role_id
     *
     * @return array
     */
    public function get_user_menu($role_id) {
        $menus = $this->_ci->config_menu->get_many_by(["role_id" => $role_id, "status" => "1"]);
        $ls_menu = [];
        $list_menu = [];
        foreach ($menus as $menu) {
            if (empty($menu->parent_id)) {
                $ls_menu[$menu->id] = [
                    "text"       => $menu->alias,
                    "icon"       => $menu->icon,
                    "url"        => site_url($menu->controller),
                    "obj_active" => $menu->obj_active,
                    "class"      => $menu->class,
                    "order"      => $menu->order,
                ];
            } else {
                $ls_menu[$menu->parent_id]["child"][$menu->order] = [
                    "text"       => $menu->alias,
                    "icon"       => $menu->icon,
                    "url"        => site_url($menu->controller),
                    "obj_active" => $menu->obj_active,
                    "class"      => $menu->class,
                ];
            }
        }
        foreach ($ls_menu as $order => $menu_parent) {
            if (isset($menu_parent["child"])) ksort($menu_parent["child"]);
            $list_menu[$menu_parent["order"]] = $menu_parent;
        }
        ksort($list_menu);
        return $list_menu;
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
        $role_data = $this->_ci->session->userdata("role_data");
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
}