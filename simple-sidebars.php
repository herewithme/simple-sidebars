<?php
/*
Plugin Name: Simple Sidebars
Plugin URI: http://redmine.beapi.fr/projects/show/simple-sidebars
Description: Allow to use custom sidebar on each page.
Author: Amaury Balmer
Author URI: http://www.beapi.fr
Version: 0.3
Text Domain: simple-sidebars
Domain Path: /languages/
Network: false

----

Copyright 2010 Amaury Balmer (amaury@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Folder name
define ( 'SS_OPTION',  'simple-sidebars' );

define ( 'SS_URL', plugins_url('', __FILE__) );
define ( 'SS_DIR', dirname(__FILE__) );

// Library
require( SS_DIR . '/inc/functions.inc.php' );

// Call client class and functions
require( SS_DIR . '/inc/class.client.php' );

if ( is_admin() ) { // Call admin class
	require( SS_DIR . '/inc/class.admin.php' );
	require( SS_DIR . '/inc/class.admin.post.php' );
}

// Plugin
register_activation_hook  ( __FILE__, array('SimpleSidebars_Client', 'activate') );

// Init
add_action( 'plugins_loaded', 'init_simple_sidebars' );
function init_simple_sidebars() {
	// Load translations
	load_plugin_textdomain ( 'simple-sidebars', false, basename(rtrim(dirname(__FILE__), '/')) . '/languages' );
	
	// Client
	new SimpleSidebars_Client();
	
	// Admin
	if ( is_admin() ) {
		new SimpleSidebars_Admin();
		new SimpleSidebars_Admin_Post();
	}
}