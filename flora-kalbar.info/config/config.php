<?php
defined ('MICRODATA') or exit ( 'Forbidden Access' );

define ('APP_CONTROLLER', APPPATH.'controller/');
define ('APP_VIEW', APPPATH.'view/');
define ('APP_MODELS', APPPATH.'model/');

/* Konfigurasi APP */

$CONFIG['default']['app_server'] = TRUE;
$CONFIG['default']['app_status'] = 'Development';
$CONFIG['default']['app_debug'] = TRUE;
$CONFIG['default']['app_underdevelopment'] = FAlSE;
$CONFIG['default']['php_ext'] = '.php';
$CONFIG['default']['default_view'] = 'home';
$CONFIG['default']['login'] = 'login';
$CONFIG['default']['admin'] = false;

$CONFIG['default']['base_url'] = 'http://localhost/codekir/';
$CONFIG['default']['root_path'] = $_SERVER['DOCUMENT_ROOT'].'/codekir';

$CONFIG['default']['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/codekir/upload/';
$CONFIG['default']['max_filesize'] = 2097152;

$CONFIG['default']['css'] = APPPATH.'css/';
$CONFIG['default']['images'] = APPPATH.'images/';
$CONFIG['default']['js'] = APPPATH.'js/';

$basedomain = $CONFIG['default']['base_url'];

$CONFIG['uri']['short'] = false;
$CONFIG['uri']['friendly'] = true;
$CONFIG['uri']['extension'] = ".html";






?>
