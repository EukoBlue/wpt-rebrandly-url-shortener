<?php
/*
Plugin Name: WPT Rebrandly URL Shortener
Plugin URI: https://github.com/EukoBlue/wpt-rebrandly-url-shortener
Description: Adds support for Rebrandly as a URL shortener in Joe Dolson's WP to Twitter plugin (https://wordpress.org/plugins/wp-to-twitter/)
Version: 1.0.1
Author: Bryson Treece
Author URI: https://eukoblue.com/
*/

/**
 * Add Rebrandly as a selectable option.
 */
add_filter( 'wpt_choose_shortener', 'eb_rebrandly_choose_shortener', 10, 2 );
function eb_rebrandly_choose_shortener( $output, $shortener ) {
	// use only lowercase alphanumerics, dashes, and underscores.
	// Value will be sanitized using sanitize_key.
	$output .= '<option value="rebrandly"' . selected( $shortener, 'rebrandly', false ) . '>Rebrandly</option>';
	return $output;
}

add_filter( 'wpt_shortener_settings', 'eb_rebrandly_shortener_settings', 10, 4 );
function eb_rebrandly_shortener_settings( $output, $selected ) {
	if ( $selected == 'rebrandly' ) {
		$output .= '<p>
					<label for="rebrandly_api_key">' . __( "Rebrandly API Key", 'textdomain' ) . '</label>
					<input type="text" name="rebrandly_api_key" id="rebrandly_api_key" size="48" value="' . esc_attr( get_option( 'rebrandly_api_key' ) ) . '" />
				</p>';
		$output .= '<p>
					<label for="rebrandly_domain_id">' . __( "Rebrandly Domain [Full ID]", 'textdomain' ) . '</label>
					<input type="text" name="rebrandly_domain_id" id="rebrandly_domain_id" size="48" value="' . esc_attr( get_option( 'rebrandly_domain_id' ) ) . '" />
					</p>

				<h3><span style="font-size: 14px;">Locate Your Custom Domain\'s ID:</span></h3>

				<p><ol>
					<li>Go to your Rebrandly account dashboard</li>
					<li>Click on "Domains" in top menu to view your domains</li>
					<li>Click on the name of domain you want to use</li>
					<li>On the domain\'s page, look in the URL for that page</li>
					<li>Find the Domain ID in the last segment of that page\'s URL</li>
				</ol></p>

				<p>Default Rebrand.ly Domain Example: https://www.rebrandly.com/domains/<span style="color: #f00; font-weight: bold;">4d20ec31db1e48c5aded19e93f137a11</span></p>';
	}
				
	return $output;
}

add_filter( 'wpt_save_shortener_settings', 'eb_rebrandly_save_shortener_settings' );
function eb_rebrandly_save_shortener_settings( $message ) {
	if ( isset( $_POST['rebrandly_api_key'] ) ) {
		$api_key  = sanitize_text_field( $_POST['rebrandly_api_key'] );
		update_option( 'rebrandly_api_key', $api_key );

		// If the API key is set, and save the Domain ID if it's set
		if ( isset( $_POST['rebrandly_domain_id'] ) ) {
			$domain_id  = sanitize_text_field( $_POST['rebrandly_domain_id'] );
			update_option( 'rebrandly_domain_id', $domain_id );
		}

		$message .= __( 'Your Rebrandly settings have been updated.', 'textdomain' );
	}
	
	return $message;
}

add_filter( 'wpt_do_shortening', 'eb_rebrandly_do_shortening', 10, 6 );
function eb_rebrandly_do_shortening( $shrink, $shortener, $url, $post_title, $post_ID, $testmode ) {
	$shrink = $url; // ensure that $shrink is always defined as a valid URL

	if ( $shortener == 'rebrandly' ) {
		$api_key   = get_option( 'rebrandly_api_key' );
		$domain_id = get_option( 'rebrandly_domain_id' );

		// Check if a link has been stored for this post already
		// If so, don't call API again
		if ( get_post_meta( $post_ID, '_wpt_short_url', true ) ) {
			// Short URL already exists, fetch and return it
			$shrink = get_post_meta( $post_ID, '_wpt_short_url', true );
		} else {
			// wpt_remote_json returns an array decoded from the JSON response
			$response = wpt_remote_json( 
				add_query_arg( 
					 array( 'apikey' 		=> $api_key, 
							'destination' 	=> urlencode( $url ), 
							'title' 		=> urlencode( $post_title ), 
							'domain[id]' 	=> $domain_id ), 
					'https://api.rebrandly.com/v1/links/new' )
			);

			// if the response is a string, then this was an error. 
			// Ignore error and continue with default WPT shortening.
			if ( !is_string( $response ) ) {
				$shrink = 'http://'. $response['shortUrl'];			
			}
		}
	}
	
	return $shrink;
}
