<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class'    => 'AuthHook',
    'function' => 'check_login',
    'filename' => 'AuthHook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'ReturAccessHook',
    'function' => 'check_retur_access',
    'filename' => 'ReturAccessHook.php',
    'filepath' => 'hooks'
);