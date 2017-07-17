<?php
/*
Plugin Name: Simple Posts Order
Version: 1.07
Description: Sort the posts order.
Author: Katsushi Kawamori
Author URI: http://riverforest-wp.info/
Text Domain: simple-posts-order
Domain Path: /languages
*/

/*  Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

	load_plugin_textdomain('simple-posts-order');
//	load_plugin_textdomain('simple-posts-order', false, basename( dirname( __FILE__ ) ) . '/languages' );

	define("SIMPLEPOSTSORDER_PLUGIN_BASE_FILE", plugin_basename(__FILE__));
	define("SIMPLEPOSTSORDER_PLUGIN_BASE_DIR", dirname(__FILE__));
	define("SIMPLEPOSTSORDER_PLUGIN_URL", plugins_url($path='simple-posts-order',$scheme=null));

	require_once( SIMPLEPOSTSORDER_PLUGIN_BASE_DIR . '/req/SimplePostsOrderRegist.php' );
	$simplepostsorderregist = new SimplePostsOrderRegist();
	add_action('admin_init', array($simplepostsorderregist, 'register_settings'));
	unset($simplepostsorderregist);

	require_once( SIMPLEPOSTSORDER_PLUGIN_BASE_DIR . '/req/SimplePostsOrderAdmin.php' );
	$simplepostsorderadmin = new SimplePostsOrderAdmin();
	add_action( 'admin_menu', array($simplepostsorderadmin, 'plugin_menu'));
	add_action( 'admin_enqueue_scripts', array($simplepostsorderadmin, 'load_custom_wp_admin_style') );
	add_filter( 'plugin_action_links', array($simplepostsorderadmin, 'settings_link'), 10, 2 );
	unset($simplepostsorderadmin);

	require_once( SIMPLEPOSTSORDER_PLUGIN_BASE_DIR.'/req/SimplePostsOrderWidgetItem.php' );
	add_action('widgets_init', create_function('', 'return register_widget("SimplePostsOrderWidgetItem");'));

	include_once( SIMPLEPOSTSORDER_PLUGIN_BASE_DIR . '/inc/SimplePostsOrder.php' );
	$simplepostsorder = new SimplePostsOrder();
	add_action( 'pre_get_posts', array($simplepostsorder, 'sort_posts') );
	add_action( 'wp_print_styles', array($simplepostsorder, 'load_styles'));
	add_shortcode( 'spo', array($simplepostsorder, 'simplepostsorder_func'));
	unset($simplepostsorder);

?>