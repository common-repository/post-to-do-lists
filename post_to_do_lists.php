<?php
/*
Plugin Name: Post To-Do Lists
Plugin URI: http://www.tammyhartdesigns.com/plugins/post-to-do-lists
Description: Create to-do lists on your posts and pages similar to those used in Basecamp and Backpack.
Version: 0.1
Author: Tammy Hart
Author URI: http://tammyhartdesigns.com
*/

/* 
Copyright (c) 2012, Tammy Hart 
 
This program is free software; you can redistribute it and/or 
modify it under the terms of the GNU General Public License 
as published by the Free Software Foundation; either version 2 
of the License, or (at your option) any later version. 
 
This program is distributed in the hope that it will be useful, 
but WITHOUT ANY WARRANTY; without even the implied warranty of 
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the 
GNU General Public License for more details. 
 
You should have received a copy of the GNU General Public License 
along with this program; if not, write to the Free Software 
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA. 
*/  

// The full path to the plugin directory
define( 'PTDL_DIR', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );
define( 'PTDL_URL', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) . '/' );

function get_ptdl_url() { return ptdl_URL; }

// Load plugin files
include_once(PTDL_DIR.'php/meta_box.php');
include_once(PTDL_DIR.'php/output.php');

// Styles and Scripts
add_action('admin_enqueue_scripts', 'ptdl_admin_enqueue');
function ptdl_admin_enqueue() {
	wp_enqueue_script('jquery-ui-datepicker', array('jquery'));
	wp_enqueue_script('ptdl_back', PTDL_URL.'js/back.js', array('jquery', 'jquery-ui-sortable', 'jquery-ui-datepicker'));
	wp_enqueue_style('ptdl_back', PTDL_URL.'css/back.css');
}
add_action('wp_enqueue_scripts', 'ptdl_wp_enqueue');
function ptdl_wp_enqueue() {
	wp_enqueue_style('ptdl_front', PTDL_URL.'css/front.css');
}

// Localization
add_action('init', 'ptdl_localization');
function ptdl_localization() {
	load_plugin_textdomain('ptdl', false, PTDL_DIR.'/lang/');  
}
	
?>