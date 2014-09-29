<?php
/**
 * groups-404-url-to-postid.php
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
 * @author WordPress
 * @author betterwp.net
 * @author itthinx
 * 
 * @package groups-404-redirect
 * @since groups-404-redirect 1.1.0
 */

/**
 * Find the post ID also for custom post types and bypassing filters.
 * 
 * Sources used:
 * - url_to_postid() in rewrite.php
 * - http://betterwp.net/wordpress-tips/url_to_postid-for-custom-post-types/
 * 
 * Modifications made so that Groups doesn't filter out the post we're looking for.
 * 
 * @param string $url
 * @link http://betterwp.net/wordpress-tips/url_to_postid-for-custom-post-types/
 * @see url_to_postid()
 */
function groups_404_url_to_postid( $url ) {
	global $wp_rewrite;

	$result = 0;

	$url = apply_filters( 'url_to_postid', $url );

	// First, check to see if there is a 'p=N' or 'page_id=N' to match against
	if ( preg_match( '#[?&](p|page_id|attachment_id)=(\d+)#', $url, $values ) )   {
		$id = absint( $values[2] );
		if ( $id ) {
			return $id;
		}
	}

	// Check to see if we are using rewrite rules
	$rewrite = $wp_rewrite->wp_rewrite_rules();

	// Not using rewrite rules, and 'p=N' and 'page_id=N' methods failed, so we're out of options
	if ( empty( $rewrite ) ) {
		return 0;
	}

	// Get rid of the #anchor
	$url_split = explode( '#', $url );
	$url = $url_split[0];

	// Get rid of URL ?query=string
	$url_split = explode( '?', $url );
	$url = $url_split[0];

	// Add 'www.' if it is absent and should be there
	if ( false !== strpos( home_url(), '://www.' ) && false === strpos( $url, '://www.' ) ) {
		$url = str_replace( '://', '://www.', $url );
	}

	// Strip 'www.' if it is present and shouldn't be
	if ( false === strpos( home_url(), '://www.' ) ) {
		$url = str_replace('://www.', '://', $url);
	}

	// Strip 'index.php/' if we're not using path info permalinks
	if ( !$wp_rewrite->using_index_permalinks() ) {
		$url = str_replace( 'index.php/', '', $url );
	}

	if ( false !== strpos($url, home_url()) ) {
		// Chop off http://domain.com
		$url = str_replace( home_url(), '', $url );
	} else {
		// Chop off /path/to/blog
		$home_path = parse_url( home_url() );
		$home_path = isset( $home_path['path'] ) ? $home_path['path'] : '' ;
		$url = str_replace( $home_path, '', $url );
	}

	// Trim leading and lagging slashes
	$url = trim( $url, '/' );

	$request = $url;

	// Look for matches.
	$request_match = $request;
	foreach ( ( array ) $rewrite as $match => $query ) {

		// If the requesting file is the anchor of the match, prepend it
		// to the path info.
		if ( !empty( $url ) && ( $url != $request ) && ( strpos( $match, $url ) === 0 ) ) {
			$request_match = $url . '/' . $request;
		}

		if ( preg_match( "!^$match!", $request_match, $matches ) ) {

			if ( $wp_rewrite->use_verbose_page_rules && preg_match( '/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch ) ) {
				// this is a verbose page match, lets check to be sure about it
				if ( ! get_page_by_path( $matches[ $varmatch[1] ] ) ) {
					continue;
				}
			}

			// Got a match.
			// Trim the query of everything up to the '?'.
			$query = preg_replace( "!^.+\?!", '', $query );

			// Substitute the substring matches into the query.
			$query = addslashes( WP_MatchesMapRegex::apply( $query, $matches ) );

			// Filter out non-public query vars
			global $wp;
			parse_str( $query, $query_vars );
			$query = array();
			foreach ( ( array ) $query_vars as $key => $value ) {
				if ( in_array( $key, $wp->public_query_vars ) )
					$query[$key] = $value;
			}

			// add the post type to the query
			foreach ( $GLOBALS['wp_post_types'] as $post_type => $t ) {
				if ( $t->query_var ) {
					$post_type_query_vars[$t->query_var] = $post_type;
				}
			}

			foreach ( $wp->public_query_vars as $wpvar ) {
				if ( isset( $wp->extra_query_vars[$wpvar] ) ) {
					$query[$wpvar] = $wp->extra_query_vars[$wpvar];
				} else if ( isset( $_POST[$wpvar] ) ) {
					$query[$wpvar] = $_POST[$wpvar];
				} else if ( isset( $_GET[$wpvar] ) ) {
					$query[$wpvar] = $_GET[$wpvar];
				} else if ( isset( $query_vars[$wpvar] ) ) {
					$query[$wpvar] = $query_vars[$wpvar];
				}

				if ( !empty( $query[$wpvar] ) ) {
					if ( ! is_array( $query[$wpvar] ) ) {
						$query[$wpvar] = (string) $query[$wpvar];
					} else {
						foreach ( $query[$wpvar] as $vkey => $v ) {
							if ( !is_object( $v ) ) {
								$query[$wpvar][$vkey] = (string) $v;
							}
						}
					}

					if ( isset( $post_type_query_vars[$wpvar] ) ) {
						$query['post_type'] = $post_type_query_vars[$wpvar];
						$query['name'] = $query[$wpvar];
					}
				}
			}

			// We just want to find out, Groups will restrict the result but we
			// need to get the post ID ...
			$query['suppress_filters'] = true;

			// Do the query
			$query = new WP_Query( $query );

			if ( !empty( $query->posts ) && $query->is_singular ) {
				return $query->post->ID;
			} else {
				return 0;
			}
		}
	}
	return 0;
}
