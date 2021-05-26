<?php
//adiocionar admin menu
add_action ('admin_menu', 'sswEImainAdminPage');
// para pegar a url de uma página
/* menu_page_url( string $menu_slug, bool $echo = true ) */
function sswEImainAdminPage()
{
	add_menu_page(
		SSW_TEAMSI_PLUGIN_NAME,
		SSW_TEAMSI_PLUGIN_NAME,
		'manage_options',
		SSW_TEAMSI_PLUGIN_SLUG,
		'sswEIreturnMainPage',
		'dashicons-admin-settings',
		150
	);
	/*
	add_submenu_page( string $parent_slug, 
					string $page_title, 
					string $menu_title, 
					string $capability, 
					string $menu_slug, 
					callable $function = '', 
					int $position = null )
	*/
	add_submenu_page( 
		SSW_TEAMSI_PLUGIN_SLUG, 
		SSW_TEAMSI_PLUGIN_NAME.'Autorização', 
		'Autorização', 
		'manage_options',
		SSW_TEAMSI_PLUGIN_SLUG.'-auth', 
		'sswEIreturnAuthPage', 
		1
	);
	add_submenu_page( 
		SSW_TEAMSI_PLUGIN_SLUG, 
		SSW_TEAMSI_PLUGIN_NAME.'Opções', 
		'Opções', 
		'manage_options',
		SSW_TEAMSI_PLUGIN_SLUG.'-options', 
		'sswEIreturnOptionsPage', 
		10
	);
}
function sswEIreturnMainPage(){
	include SSW_TEAMSI_PATH."/views/template/index.php";
}
function sswEIreturnAuthPage(){
	include SSW_TEAMSI_PATH."/views/template/auth.php";
}
function sswEIreturnOptionsPage(){
	include SSW_TEAMSI_PATH."/views/template/options.php";
}