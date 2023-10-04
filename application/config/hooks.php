<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['pre_system'][] = array(
    'class' => 'Director_load',
    'function' => 'register',
    'filename' => 'Director_load.php',
    'filepath' => 'hooks',
    'params' => array(
        APPPATH . 'controller-layout/',
        APPPATH . 'controller-manager/',
        APPPATH . 'model-crud/',
    )
);