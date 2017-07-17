<?php
/**
 * Simple Posts Order
 * 
 * @package    SimplePostsOrder
 * @subpackage SimplePostsOrder registered in the database
    Copyright (c) 2016- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

class SimplePostsOrderRegist {

	/* ==================================================
	 * Settings register
	 * @since	1.0
	 */
	function register_settings(){

		$sort = 'DESC';
		$orderby = 'date';
		$showsort = TRUE;
		$style = 'form';

		if ( get_option('simple_posts_order') ) {
			$simple_posts_order_settings = get_option('simple_posts_order');
			if ( array_key_exists( "sort", $simple_posts_order_settings ) ) {
				$sort = $simple_posts_order_settings['sort'];
			}
			if ( array_key_exists( "orderby", $simple_posts_order_settings ) ) {
				$orderby = $simple_posts_order_settings['orderby'];
			}
			if ( array_key_exists( "showsort", $simple_posts_order_settings ) ) {
				$showsort = $simple_posts_order_settings['showsort'];
			}
			if ( array_key_exists( "style", $simple_posts_order_settings ) ) {
				$style = $simple_posts_order_settings['style'];
				if ( $style == 'icon' ) { $style = 'form'; } // Case older than version 1.03.
			}
		}
		$simple_posts_order_tbl = array(
							'sort' => $sort,
							'orderby' => $orderby,
							'showsort' => $showsort,
							'style' => $style
							);
		update_option( 'simple_posts_order', $simple_posts_order_tbl );

	}

}

?>