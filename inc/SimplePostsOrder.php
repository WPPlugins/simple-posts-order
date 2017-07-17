<?php
/**
 * Simple Posts Order
 * 
 * @package    Simple Posts Order
 * @subpackage SimplePostsOrder
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

class SimplePostsOrder {

	/*
	 * Main
	 * @param	string	$query
	 * @since	1.0
	 */
	function sort_posts($query) {

		if ( !is_admin() && $query->is_main_query() ) {
			$simplepostsorder_option = get_option('simple_posts_order');
			if ( $simplepostsorder_option['showsort'] ) {
				if ( !empty($_GET['sort_spo']) && ($_GET['sort_spo'] === 'DESC' || $_GET['sort_spo'] === 'ASC') ) {
					$sort = $_GET['sort_spo'];
				} else {
					$sort = 'DESC';
				}
				$orderby = NULL;
				if ( !empty($_GET['orderby_spo_1']) || !empty($_GET['orderby_spo_2']) || !empty($_GET['orderby_spo_3']) || !empty($_GET['orderby_spo_4']) || !empty($_GET['orderby_spo_5']) ) {
					if ( !empty($_GET['orderby_spo_1']) ) {
						$orderby .= $_GET['orderby_spo_1'].' ';
					}
					if ( !empty($_GET['orderby_spo_2']) ) {
						$orderby .= $_GET['orderby_spo_2'].' ';
					}
					if ( !empty($_GET['orderby_spo_3']) ) {
						$orderby .= $_GET['orderby_spo_3'].' ';
					}
					if ( !empty($_GET['orderby_spo_4']) ) {
						$orderby .= $_GET['orderby_spo_4'].' ';
					}
					if ( !empty($_GET['orderby_spo_5']) ) {
						$orderby .= $_GET['orderby_spo_5'];
					}
					rtrim($orderby, ' ');
				}
			} else {
				$sort = $simplepostsorder_option['sort'];
				$orderby = $simplepostsorder_option['orderby'];
			}

			$query->set( 'order', $sort );
			$query->set( 'orderby', $orderby );
		}

	}

	/*
	 * @param	string	$style
	 * @param	string	$orderby
	 * @return	string	$sortlinks
	 * @since	1.0
	 */
	function sort_links($style, $orderby) {

		$query = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		if(is_ssl()){
			$query = str_replace('http:', 'https:', $query);
		}

		if ( $style == 'form' ) {
			$arr_params = array( 'sort_spo', 'orderby_spo_1', 'orderby_spo_2', 'orderby_spo_3', 'orderby_spo_4', 'orderby_spo_5');
			$query_string = remove_query_arg( $arr_params );
			$query_string = str_replace( '/?', '', $query_string );

			$query_html = NULL;
			if ( $query_string <> '/' ) {
				parse_str($query_string, $query_strings);
				foreach ($query_strings as $key => $value) {
					if( $value <> NULL ) {
						$query_html .= '<input type="hidden" name="'.$key.'" value="'.$value.'">';
					}
				}
			}
		}

		if ( !empty($_GET['sort_spo']) && ($_GET['sort_spo'] === 'DESC' || $_GET['sort_spo'] === 'ASC') ) {
			$sort = $_GET['sort_spo'];
		} else {
			$sort = 'DESC';
		}

		$sortnamedes = __('Des', 'simple-posts-order');
		$sortnameasc = __('Asc', 'simple-posts-order');

		$a_html = NULL;
		if ($sort === 'DESC') {
			// des
			if ( $style == 'form' ) {
				$a_html = $sortnameasc.'<span class="dashicons dashicons-arrow-up"></span>';
			} else {
				$a_html = $sortnameasc;
			}
			$order = 'ASC';
		} else if ($sort === 'ASC') {
			// asc
			if ( $style == 'form' ) {
				$a_html = $sortnamedes.'<span class="dashicons dashicons-arrow-down"></span>';
			} else {
				$a_html = $sortnamedes;
			}
			$order = 'DESC';
		}
		$query_arg_text = array('sort_spo' => $order); // for text

		if ( empty($orderby) ){
			if ( !empty($_GET['orderby_spo_1']) || !empty($_GET['orderby_spo_2']) || !empty($_GET['orderby_spo_3']) || !empty($_GET['orderby_spo_4']) || !empty($_GET['orderby_spo_5']) ) {
				if ( !empty($_GET['orderby_spo_1']) ) {
					$cheked_orderby1 = 'checked="checked"';
					$query_arg_text['orderby_spo_1'] = $_GET['orderby_spo_1'];
				} else {
					$cheked_orderby1 = NULL;
				}
				if ( !empty($_GET['orderby_spo_2']) ) {
					$cheked_orderby2 = 'checked="checked"';
					$query_arg_text['orderby_spo_2'] = $_GET['orderby_spo_2'];
				} else {
					$cheked_orderby2 = NULL;
				}
				if ( !empty($_GET['orderby_spo_3']) ) {
					$cheked_orderby3 = 'checked="checked"';
					$query_arg_text['orderby_spo_3'] = $_GET['orderby_spo_3'];
				} else {
					$cheked_orderby3 = NULL;
				}
				if ( !empty($_GET['orderby_spo_4']) ) {
					$cheked_orderby4 = 'checked="checked"';
					$query_arg_text['orderby_spo_4'] = $_GET['orderby_spo_4'];
				} else {
					$cheked_orderby4 = NULL;
				}
				if ( !empty($_GET['orderby_spo_5']) ) {
					$cheked_orderby5 = 'checked="checked"';
					$query_arg_text['orderby_spo_5'] = $_GET['orderby_spo_5'];
				} else {
					$cheked_orderby5 = NULL;
				}
			} else { // read admin orderby
				$simplepostsorder_option = get_option('simple_posts_order');
				$orderby_admin = $simplepostsorder_option['orderby'];
				if( strpos($orderby_admin,'author') !== false ) {
					$cheked_orderby1 = 'checked="checked"';
					$query_arg_text['orderby_spo_1'] = 'author';
				} else {
					$cheked_orderby1 = NULL;
				}
				if( strpos($orderby_admin,'title') !== false ) {
					$cheked_orderby2 = 'checked="checked"';
					$query_arg_text['orderby_spo_2'] = 'title';
				} else {
					$cheked_orderby2 = NULL;
				}
				if( strpos($orderby_admin,'date') !== false ) {
					$cheked_orderby3 = 'checked="checked"';
					$query_arg_text['orderby_spo_3'] = 'date';
				} else {
					$cheked_orderby3 = NULL;
				}
				if( strpos($orderby_admin,'modified') !== false ) {
					$cheked_orderby4 = 'checked="checked"';
					$query_arg_text['orderby_spo_4'] = 'modified';
				} else {
					$cheked_orderby4 = NULL;
				}
				if( strpos($orderby_admin,'comment_count') !== false ) {
					$cheked_orderby5 = 'checked="checked"';
					$query_arg_text['orderby_spo_5'] = 'comment_count';
				} else {
					$cheked_orderby5 = NULL;
				}
			}
		} else { // orderby of shortcode
			if( strpos($orderby,'author') !== false ) {
				$cheked_orderby1 = 'checked="checked"';
				$query_arg_text['orderby_spo_1'] = 'author';
			} else {
				$cheked_orderby1 = NULL;
			}
			if( strpos($orderby,'title') !== false ) {
				$cheked_orderby2 = 'checked="checked"';
				$query_arg_text['orderby_spo_2'] = 'title';
			} else {
				$cheked_orderby2 = NULL;
			}
			if( strpos($orderby,'date') !== false ) {
				$cheked_orderby3 = 'checked="checked"';
				$query_arg_text['orderby_spo_3'] = 'date';
			} else {
				$cheked_orderby3 = NULL;
			}
			if( strpos($orderby,'modified') !== false ) {
				$cheked_orderby4 = 'checked="checked"';
				$query_arg_text['orderby_spo_4'] = 'modified';
			} else {
				$cheked_orderby4 = NULL;
			}
			if( strpos($orderby,'comment_count') !== false ) {
				$cheked_orderby5 = 'checked="checked"';
				$query_arg_text['orderby_spo_5'] = 'comment_count';
			} else {
				$cheked_orderby5 = NULL;
			}
		}

		$label1 = __('Order by', 'simple-posts-order');
		$label2 = __('Order', 'simple-posts-order');

		$checklabel1 = __('Author');
		$checklabel2 = __('Title');
		$checklabel3 = __('Date', 'simple-posts-order');
		$checklabel4 = __('Last updated');
		$checklabel5 = __('Number of comments', 'simple-posts-order');

		if ( $style == 'form' ) {
			$sortlinks = <<<SORTLINKS

<form method="get" action = "$query" >
$query_html
<input type="hidden" name="sort_spo" value="$order">
$label1
<div style="display: block; padding:5px 5px;">
<div><input type="checkbox" name="orderby_spo_1" value="author" $cheked_orderby1> $checklabel1</div>
<div><input type="checkbox" name="orderby_spo_2" value="title" $cheked_orderby2> $checklabel2</div>
<div><input type="checkbox" name="orderby_spo_3" value="date" $cheked_orderby3> $checklabel3</div>
<div><input type="checkbox" name="orderby_spo_4" value="modified" $cheked_orderby4> $checklabel4</div>
<div><input type="checkbox" name="orderby_spo_5" value="comment_count" $cheked_orderby5> $checklabel5</div>
</div>
<div>$label2</div>
<div style="display: block; padding:5px 5px;">
<button type="submit">$a_html</button>
</div>
</form>
SORTLINKS;
		} else {
			$new_query = add_query_arg( $query_arg_text, $query );
			$sortlinks = '<a href="'.$new_query.'" title="'.$a_html.'">'.$a_html.'</a>';
		}

		return $sortlinks;

	}

	/* ==================================================
	 * short code
	 * @param	array	$atts
	 * @return	array	$html
	 */
	function simplepostsorder_func( $atts, $html = NULL ) {

		extract(shortcode_atts(array(
			'style' => '',
			'orderby' => ''
		), $atts));

		$simplepostsorder_option = get_option('simple_posts_order');
		if ( $simplepostsorder_option['showsort'] ) {
			if ( empty($style) ) { $style = $simplepostsorder_option['style']; }
			$sortlinks = $this->sort_links($style, $orderby);
			$html .= $sortlinks;
		}

		return $html;

	}

	/* ==================================================
	 * Load Dashicons
	 * @since	1.0
	 */
	function load_styles() {
		wp_enqueue_style('dashicons');
	}

}

?>