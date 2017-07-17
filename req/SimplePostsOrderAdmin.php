<?php
/**
 * Simple Posts Order
 * 
 * @package    SimplePostsOrder
 * @subpackage SimplePostsOrder Management screen
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

class SimplePostsOrderAdmin {

	/* ==================================================
	 * Add a "Settings" link to the plugins page
	 * @since	1.0
	 */
	function settings_link( $links, $file ) {
		static $this_plugin;
		if ( empty($this_plugin) ) {
			$this_plugin = SIMPLEPOSTSORDER_PLUGIN_BASE_FILE;
		}
		if ( $file == $this_plugin ) {
			$links[] = '<a href="'.admin_url('options-general.php?page=SimplePostsOrder').'">'.__( 'Settings').'</a>';
		}
			return $links;
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_menu() {
		add_options_page( 'Simple Posts Order Options', 'Simple Posts Order', 'manage_options', 'SimplePostsOrder', array($this, 'plugin_options') );
	}

	/* ==================================================
	 * Add Css and Script
	 * @since	1.0
	 */
	function load_custom_wp_admin_style() {
		if ($this->is_my_plugin_screen()) {
			wp_enqueue_style( 'jquery-responsiveTabs', SIMPLEPOSTSORDER_PLUGIN_URL.'/css/responsive-tabs.css' );
			wp_enqueue_style( 'jquery-responsiveTabs-style', SIMPLEPOSTSORDER_PLUGIN_URL.'/css/style.css' );
			wp_enqueue_script('jquery');
			wp_enqueue_script( 'jquery-responsiveTabs', SIMPLEPOSTSORDER_PLUGIN_URL.'/js/jquery.responsiveTabs.min.js' );
			wp_enqueue_script( 'simplepostsorder-js', SIMPLEPOSTSORDER_PLUGIN_URL.'/js/jquery.simplepostsorder.js', array('jquery') );
		}
	}

	/* ==================================================
	 * For only admin style
	 * @since	1.0
	 */
	function is_my_plugin_screen() {
		$screen = get_current_screen();
		if (is_object($screen) && $screen->id == 'settings_page_SimplePostsOrder') {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/* ==================================================
	 * Settings page
	 * @since	1.0
	 */
	function plugin_options() {

		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}


		if( !empty($_POST) ) {
			$post_nonce_field = 'simplepostsorder_tabs';
			if ( isset($_POST[$post_nonce_field]) && $_POST[$post_nonce_field] ) {
				if ( check_admin_referer( 'spo_settings', $post_nonce_field ) ) {
					$this->options_updated(intval($_POST['simplepostsorder_admin_tabs']));
				}
			}
		}

		$scriptname = admin_url('options-general.php?page=SimplePostsOrder');

		$simplepostsorder_option = get_option('simple_posts_order');

		?>

	<div class="wrap">
	<h2>Simple Posts Order</h2>

	<div id="simplepostsorder-admin-tabs">
	  <ul>
	    <li><a href="#simplepostsorder-admin-tabs-1"><?php _e('How to use', 'simple-posts-order'); ?></a></li>
	    <li><a href="#simplepostsorder-admin-tabs-2"><?php _e('Settings'); ?></a></li>
		<li><a href="#simplepostsorder-admin-tabs-3"><?php _e('Donate to this plugin &#187;'); ?></a></li>
	<!--
		<li><a href="#simplepostsorder-admin-tabs-4">FAQ</a></li>
	 -->
	  </ul>
	  <div id="simplepostsorder-admin-tabs-1">
		<div class="wrap">

			<h2><?php _e('How to use', 'simple-posts-order'); ?></h2>

			<div style="width: 100%; height: 100%; margin: 5px; padding: 5px; border: #CCC 2px solid;">
				<h3><?php _e('Set the widget', 'simple-posts-order'); ?></h3>
				<?php
				$widget_html = '<a href="'.admin_url('widgets.php').'" style="text-decoration: none; word-break: break-all;">'.__('Widgets').'['.__( 'Posts Sort', 'simple-posts-order' ).']</a>';
				?>
				<div style="padding: 5px 20px; font-weight: bold;"><?php echo sprintf(__('Please set the %1$s.', 'simple-posts-order'), $widget_html); ?></div>
			</div>

			<div style="width: 100%; height: 100%; margin: 5px; padding: 5px; border: #CCC 2px solid;">
				<h3><?php _e('Set up a shortcode to the template of the theme', 'simple-posts-order'); ?></h3>

				<div style="padding: 5px 20px; font-weight: bold;"><?php _e('Example', 'simple-posts-order'); ?></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[spo]'); ?&gt</code></div>
				<div style="padding: 5px 35px;"><code>&lt;?php echo do_shortcode('[spo style="text" orderby="title date"]'); ?&gt</code></div>

				<div style="padding: 5px 20px; font-weight: bold;"><?php _e('Description of each attribute', 'simple-posts-order'); ?></div>

				<?php
				$styles1_html = '<code>form</code>';
				$styles2_html = '<code>text</code>';
				$settings_html = '<a href="'.$scriptname.'#simplepostsorder-admin-tabs-2" style="text-decoration: none; word-break: break-all;">'.__('Settings', 'simple-posts-order').'</a>';
				?>
				<div style="padding: 5px 35px;"><?php _e('Style of Sort Link', 'simple-posts-order'); ?> : <code>style</code> <?php _e('Default'); ?><code>style="form"</code></div>
				<div style="padding: 5px 50px;"><?php echo sprintf(__('Specify an %1$s form display or %2$s text display.', 'simple-posts-order'), $styles1_html, $styles2_html); ?></div>
				<div style="padding: 5px 35px;"><?php _e('Order by', 'simple-posts-order'); ?> : <code>orderby</code> <?php echo sprintf(__('If blank read the value of the %1$s.', 'simple-posts-order'), $settings_html); ?></div>
				<div style="padding: 5px 50px;"><?php _e('One or more specified.', 'simple-posts-order'); ?></div>
				<div style="padding: 5px 70px;">
				<li><code>author</code> : <?php _e('Order by author.', 'simple-posts-order'); ?></li>
				<li><code>title</code> : <?php _e('Order by title.', 'simple-posts-order'); ?></li>
				<li><code>date</code> : <?php _e('Order by date.', 'simple-posts-order'); ?></li>
				<li><code>modified</code> : <?php _e('Order by last modified date.', 'simple-posts-order'); ?></li>
				<li><code>comment_count</code> : <?php _e('Order by number of comments.', 'simple-posts-order'); ?></li>
				</div>

				<div style="padding: 5px 20px; font-weight: bold;"><?php echo sprintf(__('Attribute value of short codes can also be specified in the %1$s. Attribute value of the short code takes precedence.', 'simple-posts-order'), $settings_html); ?></div>
			</div>

		</div>
	  </div>

	  <div id="simplepostsorder-admin-tabs-2">
		<div class="wrap">

			<h2><?php _e('Settings'); ?></h2>	

			<form method="post" action="<?php echo $scriptname.'#simplepostsorder-admin-tabs-2'; ?>">
			<?php wp_nonce_field('spo_settings', 'simplepostsorder_tabs'); ?>

			<div class="submit">
			  <input type="submit" class="button" name="Submit" value="<?php _e('Save Changes') ?>" />
			  <input type="submit" class="button" name="Default" value="<?php _e('Default') ?>" />
			</div>

			<div style="width: 100%; height: 100%; margin: 5px; padding: 5px; border: #CCC 2px solid;">

				<div style="display: block; padding:5px 5px;">
					<h3><?php _e('Order and Display', 'simple-posts-order'); ?></h3>
					<div style="display: block; padding:5px 20px;">
					<input type="radio" name="simplepostsorder_showsort" value="" <?php checked('', $simplepostsorder_option['showsort']); ?> />
					<?php _e('Administrator to decide the order.', 'simple-posts-order'); ?>
						<div style="display: block; padding:5px 35px;">
						<?php _e('Order by', 'simple-posts-order'); ?>
						<div style="display: block; padding:5px 40px;">
						<div><input type="checkbox" name="simplepostsorder_orderby_1" value="author" <?php if(strpos($simplepostsorder_option['orderby'],'author') !== false ) echo 'checked="checked"'; ?>> <?php _e('Author'); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_2" value="title" <?php if(strpos($simplepostsorder_option['orderby'],'title') !== false ) echo 'checked="checked"'; ?>> <?php _e('Title'); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_3" value="date" <?php if(strpos($simplepostsorder_option['orderby'],'date') !== false ) echo 'checked="checked"'; ?>> <?php _e('Date'); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_4" value="modified" <?php if(strpos($simplepostsorder_option['orderby'],'modified') !== false ) echo 'checked="checked"'; ?>> <?php _e('Last updated'); ?></div>
						<div><input type="checkbox" name="simplepostsorder_orderby_5" value="comment_count" <?php if(strpos($simplepostsorder_option['orderby'],'comment_count') !== false ) echo 'checked="checked"'; ?>> <?php _e('Number of comments', 'simple-posts-order'); ?></div>
						</div>
						<?php _e('Order'); ?>
						<select id="simplepostsorder_sort" name="simplepostsorder_sort">
							<option value="DESC" <?php if ('DESC' == $simplepostsorder_option['sort'])echo 'selected="selected"'; ?>><?php _e('Des', 'simple-posts-order'); ?></option>
							<option value="ASC" <?php if ('ASC' == $simplepostsorder_option['sort'])echo 'selected="selected"'; ?>><?php _e('Asc', 'simple-posts-order'); ?></option>
						</select>
						</div>
					</div>
					<div style="display: block; padding:5px 20px;">
						<input type="radio" name="simplepostsorder_showsort" value="1" <?php checked('1', $simplepostsorder_option['showsort']); ?> />
						<?php _e('Users to decide the order.', 'simple-posts-order'); ?>
					</div>
					<div style="display: block; padding:5px 35px;">
						<?php _e('Style of Sort Link', 'simple-posts-order'); ?> : 
						<select id="simplepostsorder_style" name="simplepostsorder_style">
							<option value="form" <?php if ('form' == $simplepostsorder_option['style'])echo 'selected="selected"'; ?>>form</option>
							<option value="text" <?php if ('text' == $simplepostsorder_option['style'])echo 'selected="selected"'; ?>>text</option>
						</select>
					</div>
					<div style="display: block; padding:5px 40px;">
					<?php
					$form_or_text_1_html = '<code>form</code>';
					$form_or_text_2_html = '<code>text</code>';
					$form_or_text_3_html = '<code>'.__('Order by', 'simple-posts-order').'</code>';
					$form_or_text_4 = sprintf(__('In the case of style %1$s, the %2$s will be the initial value is what the administrator has decided. The user can change the value.', 'simple-posts-order'), $form_or_text_1_html, $form_or_text_3_html);
					$form_or_text_5 = sprintf(__('In the case of style %1$s, the %2$s will be those that the administrator has decided. The user can not change the value. However, you can specify a value in the shortcode.', 'simple-posts-order'), $form_or_text_2_html, $form_or_text_3_html);
					?>
					<div><li><?php echo $form_or_text_4; ?></li></div>
					<div><li><?php echo $form_or_text_5; ?></li></div>
					</div>
				</div>
			</div>

			<div class="submit">
				<input type="hidden" name="simplepostsorder_admin_tabs" value="2" />
				<input type="submit" class="button" name="Submit" value="<?php _e('Save Changes') ?>" />
			</div>

			</form>

		</div>
	  </div>

	  <div id="simplepostsorder-admin-tabs-3">
		<div class="wrap">
			<?php
			$plugin_datas = get_file_data( SIMPLEPOSTSORDER_PLUGIN_BASE_DIR.'/simplepostsorder.php', array('version' => 'Version') );
			$plugin_version = __('Version:').' '.$plugin_datas['version'];
			?>
			<h4 style="margin: 5px; padding: 5px;">
			<?php echo $plugin_version; ?> |
			<a style="text-decoration: none;" href="https://wordpress.org/support/plugin/simple-posts-order" target="_blank"><?php _e('Support Forums') ?></a> |
			<a style="text-decoration: none;" href="https://wordpress.org/support/view/plugin-reviews/simple-posts-order" target="_blank"><?php _e('Reviews', 'simple-posts-order') ?></a>
			</h4>
			<div style="width: 250px; height: 170px; margin: 5px; padding: 5px; border: #CCC 2px solid;">
			<h3><?php _e('Please make a donation if you like my work or would like to further the development of this plugin.', 'simple-posts-order'); ?></h3>
			<div style="text-align: right; margin: 5px; padding: 5px;"><span style="padding: 3px; color: #ffffff; background-color: #008000">Plugin Author</span> <span style="font-weight: bold;">Katsushi Kawamori</span></div>
	<a style="margin: 5px; padding: 5px;" href='https://pledgie.com/campaigns/28307' target="_blank"><img alt='Click here to lend your support to: Various Plugins for WordPress and make a donation at pledgie.com !' src='https://pledgie.com/campaigns/28307.png?skin_name=chrome' border='0' ></a>
			</div>
		</div>
	  </div>

	<!--
	  <div id="simplepostsorder-admin-tabs-4">
		<div class="wrap">
		<h2>FAQ</h2>

		</div>
	  </div>
	-->

	</div>
	</div>
	<?php

	}

	/* ==================================================
	 * Update wp_options table.
	 * @param	string	$tabs
	 * @since	1.0
	 */
	function options_updated($tabs){

		$simple_posts_order_reset_tbl = array(
								'sort' => 'DESC',
								'orderby' => 'date',
								'showsort' => TRUE,
								'style' => 'form'
					);

		switch ($tabs) {
			case 1:
				break;
			case 2:
				if ( !empty($_POST['Default']) ) {
					update_option( 'simple_posts_order', $simple_posts_order_reset_tbl );
					echo '<div class="updated"><ul><li>'.__('Settings').' --> '.__('Default').' --> '.__('Changes saved.').'</li></ul></div>';
				} else {
					$orderby = NULL;
					if ( !empty($_POST['simplepostsorder_orderby_1']) || !empty($_POST['simplepostsorder_orderby_2']) || !empty($_POST['simplepostsorder_orderby_3']) || !empty($_POST['simplepostsorder_orderby_4']) || !empty($_POST['simplepostsorder_orderby_5']) ) {
						if ( !empty($_POST['simplepostsorder_orderby_1']) ) {
							$orderby .= $_POST['simplepostsorder_orderby_1'].' ';
						}
						if ( !empty($_POST['simplepostsorder_orderby_2']) ) {
							$orderby .= $_POST['simplepostsorder_orderby_2'].' ';
						}
						if ( !empty($_POST['simplepostsorder_orderby_3']) ) {
							$orderby .= $_POST['simplepostsorder_orderby_3'].' ';
						}
						if ( !empty($_POST['simplepostsorder_orderby_4']) ) {
							$orderby .= $_POST['simplepostsorder_orderby_4'].' ';
						}
						if ( !empty($_POST['simplepostsorder_orderby_5']) ) {
							$orderby .= $_POST['simplepostsorder_orderby_5'];
						}
						rtrim($orderby, ' ');
					} else {
						$orderby = 'date';
					}
					if ( !empty($_POST['simplepostsorder_showsort']) ) {
						$simplepostsorder_showsort = intval($_POST['simplepostsorder_showsort']);
					} else {
						$simplepostsorder_showsort = FALSE;
					}
					$simple_posts_order_tbl = array(
									'sort' => $_POST['simplepostsorder_sort'],
									'orderby' => $orderby,
									'showsort' => $simplepostsorder_showsort,
									'style' => $_POST['simplepostsorder_style']
								);
					update_option( 'simple_posts_order', $simple_posts_order_tbl );
					echo '<div class="updated"><ul><li>'.__('Settings').' --> '.__('Changes saved.').'</li></ul></div>';
				}
				break;
		}

		return;

	}

}

?>