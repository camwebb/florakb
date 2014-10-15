<?php
// defined ('TATARUANG') or exit ( 'Forbidden Access' );

define ('APP_CONTROLLER', APPPATH.'controller/');
define ('APP_VIEW', APPPATH.'view/');
define ('APP_MODELS', 'model/');

/* Konfigurasi APP */

$CONFIG['dashboard']['app_server'] = TRUE;
$CONFIG['dashboard']['smarty_enabled'] = false;
$CONFIG['dashboard']['app_status'] = 'Development';
$CONFIG['dashboard']['app_debug'] = TRUE;
$CONFIG['dashboard']['app_underdevelopment'] = FAlSE;
$CONFIG['dashboard']['smarty_enabled'] = true;
$CONFIG['dashboard']['php_ext'] = '.php';
$CONFIG['dashboard']['html_ext'] = '.html';
$CONFIG['dashboard']['default_view'] = 'home';
$CONFIG['dashboard']['login'] = 'login';


$CONFIG['dashboard']['app_url'] = 'http://localhost/florakb/flora-kalbar.info/';
$CONFIG['dashboard']['base_url'] = 'http://localhost/florakb/flora-kalbar.info/dashboard/';
$CONFIG['dashboard']['root_path'] = $_SERVER['DOCUMENT_ROOT'].'/florakb/flora-kalbar.info/dashboard';

$CONFIG['dashboard']['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/florakb/flora-kalbar.info/public_assets/';

$CONFIG['dashboard']['max_filesize'] = 2097152;

$CONFIG['dashboard']['css'] = APPPATH.'css/';
$CONFIG['dashboard']['images'] = APPPATH.'images/';
$CONFIG['dashboard']['js'] = APPPATH.'js/';

$basedomain = $CONFIG['dashboard']['base_url'];
$app_domain = $CONFIG['dashboard']['app_url'];

/* Konfigurasi DB */

$dbConfig[0]['host'] = 'localhost';
$dbConfig[0]['user'] = 'root';
$dbConfig[0]['pass'] = 'root123root';
$dbConfig[0]['name'] = 'florakalbar';
$dbConfig[0]['server'] = 'mysql';

$dbConfig[1]['host'] = 'localhost';
$dbConfig[1]['user'] = 'root';
$dbConfig[1]['pass'] = 'root123root';
$dbConfig[1]['name'] = 'florakalbar_app';
$dbConfig[1]['server'] = 'mysql';


?>
