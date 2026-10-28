<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
$hook['post_controller'] = array(
    'class'    => 'AppStatusHook',
    'function' => 'check_app_status',
    'filename' => 'AppStatusHook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'SuspendInfoHook',
    'function' => 'check',
    'filename' => 'SuspendInfoHook.php',
    'filepath' => 'hooks'
);

$hook['post_controller_constructor'][] = array(
    'class'    => 'PasswordReminderHook',
    'function' => 'check_reminder',
    'filename' => 'PasswordReminderHook.php',
    'filepath' => 'hooks'
);
