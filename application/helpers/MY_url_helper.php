<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03/05/2019
 * Time: 11:09 SA
 */
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('base_url')) {
    /**
     * override base_url to get time version for load js and css
     *
     * @param string $uri
     *
     * @return string
     */
    function base_url($uri = '') {
        $CI =& get_instance();
        return $CI->config->item("base_url") . $uri;
    }
}

if (!function_exists('minify_css_js')) {
    /**
     *
     * @param string $type
     * @param array $file_array
     * @param null $file_name
     * @return string
     */
    function minify_css_js($type = 'css', $file_array = array(), $file_name = NULL) {
        if (empty($type) || !in_array($type, ['css', 'js']) || empty($file_array)) {
            return FALSE;
        }
        if (is_string($file_array)) $file_array = [$file_array];
        foreach ($file_array as $file) {
            if ($type == 'css') {
                echo '<link rel="stylesheet" href="' . base_url($file) . '"/>';
            } else {
                echo '<script src="' . base_url($file) . '"></script>';
            }
        }
    }
}