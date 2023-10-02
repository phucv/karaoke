<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 03/05/2019
 * Time: 11:09 SA
 */
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_string')) {
    /**
     * override base_url to get time version for load js and css
     *
     * @param string $uri
     * @param null   $protocol
     *
     * @return string
     */
    function base_url($uri = '', $protocol = NULL) {
        $CI =& get_instance();
        $url = $CI->config->item("base_url") . $uri;
        return $url;
    }
}