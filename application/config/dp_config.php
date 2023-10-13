<?php
defined('BASEPATH') or exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| Ion Auth
|--------------------------------------------------------------------------
|
| Custom database
*/
$config['tables']['groups_color'] = 'dp_auth_groups_color';

/*
|--------------------------------------------------------------------------
| Theme
|--------------------------------------------------------------------------
|
|
*/
/* Authentification */
$config['dp_theme_auth']         = 'default';
$config['dp_theme_auth_url']     = 'assets/' . $config['dp_theme_auth'] . '/';

/* Back End */
$config['dp_theme_backend']      = 'default';
$config['dp_theme_backend_url']  = 'assets/backend/' . $config['dp_theme_backend'] . '/';

$config['dp_theme_admin']      = 'default';
$config['dp_theme_admin_url']  = 'assets/' . $config['dp_theme_admin'] . '/';

$config['dp_theme_teacher']     = 'default';
$config['dp_theme_teacher_url'] = 'assets/' . $config['dp_theme_teacher'] . '/';


/*
|--------------------------------------------------------------------------
| Form Validation
|--------------------------------------------------------------------------
|
| Changing the Error Delimiters
*/
$config['error_prefix'] = '<div class="alert alert-danger" role="alert">';
$config['error_suffix'] = '</div>';
