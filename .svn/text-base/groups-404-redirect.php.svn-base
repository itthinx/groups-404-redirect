<?php
/**
 * groups-404-redirect.php
 *
 * Copyright (c) 2013 "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups-404-redirect
 * @since groups-404-redirect 1.0.0
 *
 * Plugin Name: Groups 404 Redirect
 * Plugin URI: http://www.itthinx.com/plugins/groups
 * Description: Redirect 404's when a visitor tries to access a page protected by <a href="http://wordpress.org/extend/plugins/groups/">Groups</a>.
 * Version: 1.2.3
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Donate-Link: http://www.itthinx.com
 * License: GPLv3
 */

define( 'GROUPS_404_REDIRECT_PLUGIN_DOMAIN', 'groups-404-redirect' );

/**
 * Redirection.
 */
class Groups_404_Redirect {

	/**
	 * Initialize hooks.
	 */
	public static function init() {
		// register_activation_hook(__FILE__, array( __CLASS__,'activate' ) );
		register_deactivation_hook(__FILE__,  array( __CLASS__,'deactivate' ) );
		add_action( 'wp', array( __CLASS__, 'wp' ) );
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		if ( is_admin() ) {
			add_filter( 'plugin_action_links_'. plugin_basename( __FILE__ ), array( __CLASS__, 'admin_settings_link' ) );
		}
	}

	/**
	 * Nothing to do.
	 */
	public static function activate() {
	}

	/**
	 * Delete settings.
	 */
	public static function deactivate() {
		if ( self::groups_is_active() ) {
			Groups_Options::delete_option( 'groups-404-redirect-to' );
			Groups_Options::delete_option( 'groups-404-redirect-post-id' );
		}
	}

	/**
	 * Add the Settings > Groups 404 section.
	 */
	public static function admin_menu() {
		add_options_page(
			'Groups 404 Redirect',
			'Groups 404',
			'manage_options',
			'groups-404-redirect',
			array( __CLASS__, 'settings' )
		);
	}

	/**
	 * Adds plugin links.
	 *
	 * @param array $links
	 * @param array $links with additional links
	 */
	public static function admin_settings_link( $links ) {
		$links[] = '<a href="' . get_admin_url( null,'options-general.php?page=groups-404-redirect' ) . '">' . __( 'Settings', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ) . '</a>';
		return $links;
	}

	/**
	 * Admin settings.
	 */
	public static function settings() {

		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Access denied.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ) );
		}

		if ( !self::groups_is_active() ) {
			echo '<p>';
			echo __( 'Please install and activate <a href="http://wordpress.org/extend/plugins/groups/">Groups</a> to use this plugin.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
			echo '</p>';
			return;
		}

		$http_status_codes = array(
			'301' => __( 'Moved Permanently', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ),
			'302' => __( 'Found', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ),
			'303' => __( 'See Other', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ),
			'307' => __( 'Temporary Redirect', GROUPS_404_REDIRECT_PLUGIN_DOMAIN )
		);

		if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'save' ) && wp_verify_nonce( $_POST['groups-404-redirect'], 'admin' ) ) {

			$redirect_to = 'post';
			if ( !empty( $_POST['redirect_to'] ) ) {
				switch( $_POST['redirect_to'] ) {
					case 'post' :
					case 'login' :
						Groups_Options::update_option( 'groups-404-redirect-to', $_POST['redirect_to'] );
						break;
				}
			}

			if ( !empty( $_POST['post_id'] ) ) {
				Groups_Options::update_option( 'groups-404-redirect-post-id', intval( $_POST['post_id'] ) );
			} else {
				Groups_Options::delete_option( 'groups-404-redirect-post-id' );
			}

			Groups_Options::update_option( 'groups-404-redirect-restricted-terms', !empty( $_POST['redirect_restricted_terms'] ) );

			if ( key_exists( $_POST['status'], $http_status_codes ) ) {
				Groups_Options::update_option( 'groups-404-redirect-status', $_POST['status'] );
			}

			echo
			'<p class="info">' .
			__( 'The settings have been saved.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ) .
			'</p>';
		}

		$redirect_to     = Groups_Options::get_option( 'groups-404-redirect-to', 'post' );
		$post_id         = Groups_Options::get_option( 'groups-404-redirect-post-id', '' );
		$redirect_status = Groups_Options::get_option( 'groups-404-redirect-status', '301' );
		$redirect_restricted_terms = Groups_Options::get_option( 'groups-404-redirect-restricted-terms', false );

		echo '<h1>';
		echo __( 'Groups 404 Redirect', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</h1>';

		echo '<p>';
		echo __( 'Redirect settings when a visitor tries to access a page protected by Groups.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';

		echo '<div class="settings">';
		echo '<form name="settings" method="post" action="">';
		echo '<div>';

		echo '<label>';
		echo sprintf( '<input type="radio" name="redirect_to" value="post" %s />', $redirect_to == 'post' ? ' checked="checked" ' : '' );
		echo ' ';
		echo __( 'Redirect to a page or post', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</label>';

		echo '<div style="margin: 1em 0 0 2em">';

		echo '<label>';
		echo __( 'Page or Post ID', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo ' ';
		echo sprintf( '<input type="text" name="post_id" value="%s" />', $post_id );
		echo '</label>';

		if ( !empty( $post_id ) ) {
			$post_title = get_the_title( $post_id );
			echo '<p>';
			echo sprintf( __( 'Title: <em>%s</em>', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ), $post_title );
			echo '</p>';
		}

		echo '<p class="description">';
		echo __( 'Indicate the ID of a page or a post to redirect to, leave it empty to redirect to the home page.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '<br/>';
		echo __( 'The title of the page will be shown if a valid ID has been given.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';
		echo '<p class="description">';
		echo __( 'If the <strong>Redirect to the WordPress login</strong> option is chosen instead, visitors who are logged in but may not access a requested page, can be redirected to a specific page by setting the Page or Post ID here.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';

		echo '</div>';

		echo '<br/>';

		echo '<label>';
		echo sprintf( '<input type="radio" name="redirect_to" value="login" %s />', $redirect_to == 'login' ? ' checked="checked" ' : '' );
		echo ' ';
		echo __( 'Redirect to the WordPress login', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</label>';

		echo '<div style="margin: 1em 0 0 2em">';
		echo '<p class="description">';
		echo __( 'If the visitor is logged in but is not allowed to access the requested page, the visitor will be taken to the home page, or, if a Page or Post ID is set, to the page indicated above.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';
		echo '</div>';

		echo '<br/>';

		echo '<label>';
		echo sprintf( '<input type="checkbox" name="redirect_restricted_terms" %s />', $redirect_restricted_terms ? ' checked="checked" ' : '' );
		echo ' ';
		echo __( 'Redirect restricted categories, tags and taxonomy terms &hellip;', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</label>';

		echo '<div style="margin: 1em 0 0 2em">';
		echo '<p class="description">';
		echo __( 'If the visitor is not allowed to access the requested taxonomy term, including restricted categories and tags, the visitor will be redirected as indicated above.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';
		echo '<p class="description">';
		echo __( 'This option will only take effect if <a href="http://www.itthinx.com/plugins/groups-restrict-categories/">Groups Restrict Categories</a> is used.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';
		echo '</div>';

		echo '<br/>';

		echo
			'<p style="border-top:1px solid #eee; margin-top:1em; padding-top: 1em;">' .
			'<label>' .
			__( 'Redirect Status Code', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ) .
			' ' .
			'<select name="status">';
		foreach ( $http_status_codes as $code => $name ) {
			echo '<option value="' . esc_attr( $code ) . '" ' . ( $redirect_status == $code ? ' selected="selected" ' : '' ) . '>' . $name . ' (' . $code . ')' . '</option>';
		}
		echo
			'</select>' .
			'</label>' .
			'</p>';

		echo '<p class="description">';
		echo __( '<a href="http://www.w3.org/Protocols/rfc2616/rfc2616.html">RFC 2616</a> provides details on <a href="http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html">Status Code Definitions</a>.', GROUPS_404_REDIRECT_PLUGIN_DOMAIN );
		echo '</p>';

		wp_nonce_field( 'admin', 'groups-404-redirect', true, true );

		echo '<br/>';

		echo '<div class="buttons">';
		echo sprintf( '<input class="create button" type="submit" name="submit" value="%s" />', __( 'Save', GROUPS_404_REDIRECT_PLUGIN_DOMAIN ) );
		echo '<input type="hidden" name="action" value="save" />';
		echo '</div>';

		echo '</div>';
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Handles redirection.
	 */
	public static function wp() {

		global $wp_query;

		$is_restricted_term = false;
		if ( class_exists( 'Groups_Options' ) && class_exists( 'Groups_Restrict_Categories' ) ) {
			$redirect_restricted_terms = Groups_Options::get_option( 'groups-404-redirect-restricted-terms', false );
			if ( $redirect_restricted_terms ) {
				$is_term = $wp_query->is_category || $wp_query->is_tag || $wp_query->is_tax;
				if ( $is_term ) {
					$restricted_term_ids = Groups_Restrict_Categories::get_user_restricted_term_ids( get_current_user_id() );
					$term_id = $wp_query->get_queried_object_id();
					if ( in_array( $term_id, $restricted_term_ids ) ) {
						$is_restricted_term = true;
					}
				}
			}
		}

		if ( $wp_query->is_404 || $is_restricted_term ) {
			if ( self::groups_is_active() ) {
				$redirect_to     = Groups_Options::get_option( 'groups-404-redirect-to', 'post' );
				$post_id         = Groups_Options::get_option( 'groups-404-redirect-post-id', '' );
				$redirect_status = intval( Groups_Options::get_option( 'groups-404-redirect-status', '301' ) );

				$current_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

				$current_post_id = url_to_postid( $current_url );
				if ( !$current_post_id ) {
					$current_post_id = $wp_query->get_queried_object_id();
				}
				if ( !$current_post_id ) {
					require_once 'groups-404-url-to-postid.php';
					$current_post_id = groups_404_url_to_postid( $current_url );
				}

				if ( $current_post_id ) {

					$is_restricted_by_term = false;
					if ( class_exists( 'Groups_Restrict_Categories' ) && method_exists( 'Groups_Restrict_Categories', 'user_can_read' ) ) {
						$is_restricted_by_term = !Groups_Restrict_Categories::user_can_read( $current_post_id );
					}

					if ( !Groups_Post_Access::user_can_read_post( $current_post_id, get_current_user_id() ) || $is_restricted_by_term || $is_restricted_term ) {

						switch( $redirect_to ) {
							case 'login' :
								if ( !is_user_logged_in() ) {
									wp_redirect( wp_login_url( $current_url ), $redirect_status );
									exit;
								} else {
									// If the user is already logged in, we can't
									// redirect to the WordPress login again,
									// we either send them to the home page, or
									// to the page indicated in the settings.
									if ( empty( $post_id ) ) {
										wp_redirect( get_home_url(), $redirect_status );
									} else {
										$post_id = apply_filters( 'groups_404_redirect_post_id', $post_id, $current_post_id, $current_url );
										if ( $post_id != $current_post_id ) {
											wp_redirect( get_permalink( $post_id ), $redirect_status );
										} else {
											return;
										}
									}
									exit;
								}

							default: // 'post'
								if ( empty( $post_id ) ) {
									wp_redirect( get_home_url(), $redirect_status );
								} else {
									$post_id = apply_filters( 'groups_404_redirect_post_id', $post_id, $current_post_id, $current_url );
									if ( $post_id != $current_post_id ) {
										wp_redirect( get_permalink( $post_id ), $redirect_status );
									} else {
										return;
									}
								}
								exit;

						}
					}
				}
			}
		}
	}

	/**
	 * Returns true if the Groups plugin is active.
	 * @return boolean true if Groups is active
	 */
	private static function groups_is_active() {
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_sitewide_plugins = array_keys( $active_sitewide_plugins );
			$active_plugins = array_merge( $active_plugins, $active_sitewide_plugins );
		}
		return in_array( 'groups/groups.php', $active_plugins ); 
	}
}
Groups_404_Redirect::init();
