<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://mbmti.ir
 * @since             2.1.6
 * @package           MBMTI_PKG
 *
 * @wordpress-plugin
 * Plugin Name:       Manage User Access Permission
 * Plugin URI:        https://mbmti.ir/manage-user-access-permission-plugin
 * Description:       User role management,Managing access to menus in such a way that if the menu is not accessed, the screen will be locked,Post type support,Support for plugins and template menus,completely free
 * Version:           2.1.6
 * Author:            Net Tutorial
 * Author URI:        http://mbmti.ir/about-us
 * Text Domain:       muap-mbmti
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) die;

/* General Definition
******************************/
define('MUAP_MPMTI_VERSION', '2.1.6');

define('MUAP_MPMTI_BASE', plugin_dir_path(__FILE__));
define('MUAP_MPMTI_URI', plugin_dir_url(__FILE__));
define('MUAP_MPMTI_FILE', __FILE__);
define('MUAP_MPMTI_Include', MUAP_MPMTI_BASE . 'include/');
define('MUAP_MPMTI_Controller', MUAP_MPMTI_BASE . 'controller/');
$ViewData = [];

require MUAP_MPMTI_Include . 'sql_scripts.php';
require MUAP_MPMTI_Include . 'core.php';
require MUAP_MPMTI_Controller . 'role.php';
require MUAP_MPMTI_Controller . 'access.php';


$MUAP_MPMTI_Core;
global $MUAP_MPMTI_Core;
$MUAP_MPMTI_Core = new MUAP_MPMTI_Core();



//
