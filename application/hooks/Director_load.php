<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Director_load {

    private $_include_paths = array();

    public function register(array $paths = array()) {
        $this->_include_paths = $paths;
        spl_autoload_register(array($this, 'autoloader'));
    }

    public function autoloader($class) {
        foreach ($this->_include_paths as $path) {
            $filepath = $path . $class . ".php";
            if (!class_exists($class, FALSE) AND is_file($filepath)) {
                include_once($filepath);
                break;
            }
        }
    }

}