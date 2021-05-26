<?php
/**
 * Plugin Name: SSW Integração do Teams
 * Plugin URI: https://www.santanasolucoesweb.com.br/
 * Description: Provê uma classe para acesso ao teams da microsoft
 * Version: 1.0
 * Author: Vinicius de Santana
 * Author URI: https://www.santanasolucoesweb.com.br/
 */
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Informações do app
define('SSW_TEAMSI_PATH', dirname( __FILE__ ) );
define('SSW_TEAMSI_URL', plugins_url( '', __FILE__ ) );
define('SSW_TEAMSI_PLUGIN_NAME', 'SSW Teams Integra' );
define('SSW_TEAMSI_PLUGIN_SLUG', 'ssw-teamsi-admin' );
define('SSW_TEAMSI_URLHOME', '/wp-admin/admin.php?page='.SSW_TEAMSI_PLUGIN_SLUG );
//informações do aplicativo criado no rd
define('SSW_TEAMSI_CLIENT_ID', 'ssw-teamsi-client-id');
define('SSW_TEAMSI_CLIENTE_SECRET', 'ssw-teamsi-cliente-secret');
define('SSW_TEAMSI_URLCALLBACK', site_url().'/wp-json/ssw-teamsi-integration/v1/callback/');
define('SSW_TEAMSI_CODE', 'ssw-teamsi-code');
define('SSW_TEAMSI_ACCESS_TOKEN', 'ssw-teamsi-access-token');
define('SSW_TEAMSI_REFRESH_TOKEN', 'ssw-teamsi-refresh-token');
// informações do Teams
define('SSW_TEAMSI_GROUP', 'ssw-teamsi-group');

include_once SSW_TEAMSI_PATH.'/class/index.php';
include_once SSW_TEAMSI_PATH.'/api/index.php';
include_once SSW_TEAMSI_PATH.'/functions/index.php';

register_activation_hook(__FILE__, 'ssw_tint_install');
register_uninstall_hook(__FILE__, 'ssw_tint_uninstall');
//==================================================================
//funções
/**
 * função de instalação do plugin
 */
function ssw_tint_install(){
	add_option(SSW_TEAMSI_CLIENT_ID, '');
	add_option(SSW_TEAMSI_CLIENTE_SECRET, '');
	add_option(SSW_TEAMSI_CODE, '');
	add_option(SSW_TEAMSI_ACCESS_TOKEN, '');
	add_option(SSW_TEAMSI_REFRESH_TOKEN, '');
	add_option(SSW_TEAMSI_GROUP, '');
}

/**
 * função de desinstalação do plugin
 */
function ssw_tint_uninstall(){
	delete_option(SSW_TEAMSI_CLIENT_ID);
	delete_option(SSW_TEAMSI_CLIENTE_SECRET);
	delete_option(SSW_TEAMSI_CODE);
	delete_option(SSW_TEAMSI_ACCESS_TOKEN);
	delete_option(SSW_TEAMSI_REFRESH_TOKEN);
	delete_option(SSW_TEAMSI_GROUP);
}