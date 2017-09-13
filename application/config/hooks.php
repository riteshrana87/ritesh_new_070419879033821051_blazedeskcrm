<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | @Author : RJ(Rupesh Jorkar)
  | @Desc   : This file lets you define "hooks" to extend CI without hacking the core files.
  | @Date   : 21/01/2016

  |
 */

// Stores the requested URL, which will sometimes be different than previous url 


$hook['post_controller_constructor'] = array(
    'class' => 'App_hooks',
    'function' => 'save_requested',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params' => ''
);

// Allows us to perform good redirects to previous pages.
$hook['post_controller_constructor'] = array(
    'class' => 'App_hooks',
    'function' => 'prep_redirect',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params' => ''
);

// Load Config from DB
$hook['post_controller_constructor'] = array(
    'class' => '',
    'function' => 'load_config',
    'filename' => 'App_config.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'] = array(
    'class' => 'ACL',
    'function' => 'auth',
    'filename' => 'App_acl.php',
    'filepath' => 'hooks'
);
