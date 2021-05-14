<?php
/**
 * Contains the post type settings route class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Routes\Settings;

use WP_Error;
use WP_REST_Request;

/**
 * Class for handling the `/settings/site` route.
 *
 * @package Really_Rich_Results\Admin\Routes\Settings
 */
class Site {

	/**
	 * Gets site settings, using built-in defaults if not set.
	 *
	 * @return array
	 */
	public function read() {
		return array(
			'organization' => array(
				'name'    => get_option( 'really_rich_results_org_name', get_bloginfo( 'name' ) ),
				'url'     => get_option( 'really_rich_results_org_url', get_site_url() ),
				'duns'    => get_option( 'really_rich_results_org_duns', '' ),
				'address' => array(
					'street'     => get_option( 'really_rich_results_org_street', '' ),
					'poBox'      => get_option( 'really_rich_results_org_po_box', '' ),
					'city'       => get_option( 'really_rich_results_org_locality', '' ),
					'state'      => get_option( 'really_rich_results_org_region', '' ),
					'postalCode' => get_option( 'really_rich_results_org_postal_code', '' ),
					'country'    => get_option( 'really_rich_results_org_country', '' ),
				),
				'sameAs'  => get_option( 'really_rich_results_org_same_as', array() ),
			),
		);
	}

	/**
	 * Updates site settings.
	 *
	 * @param WP_REST_Request $request The request from the REST API.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function edit( WP_REST_Request $request ) {
		$body_params = $request->get_json_params();

		$organization_settings = $body_params['organization'];

		$result = array();

		foreach ( $organization_settings as $option_key => $option_value ) {
			switch ( $option_key ) {
				case 'name':
					$result[ $option_key ] = update_option( 'really_rich_results_org_name', $option_value );
					break;
				case 'url':
					$result[ $option_key ] = update_option( 'really_rich_results_org_url', $option_value );
					break;
				case 'duns':
					$result[ $option_key ] = update_option( 'really_rich_results_org_duns', $option_value );
					break;
				case 'address':
					$result[ $option_key ] = $this->update_address_props( $option_value );
					break;
				case 'sameAs':
					$result[ $option_key ] = update_option( 'really_rich_results_org_same_as', $option_value );
					break;
			}
		}

		return rest_ensure_response( $result );
	}

	/**
	 * Updates address props.
	 *
	 * @param string[] $address_props Array of address props to update.
	 *
	 * @return bool[]
	 */
	private function update_address_props( $address_props ) {
		$result = array();

		foreach ( $address_props as $address_prop => $address_val ) {
			switch ( $address_prop ) {
				case 'street':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_street', $address_val );
					break;
				case 'poBox':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_po_box', $address_val );
					break;
				case 'city':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_locality', $address_val );
					break;
				case 'state':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_region', $address_val );
					break;
				case 'postalCode':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_postal_code', $address_val );
					break;
				case 'country':
					$result[ $address_prop ] = update_option( 'really_rich_results_org_country', $address_val );
					break;
			}
		}

		return $result;
	}

	/**
	 * REST API permissions callback for editing site settings.
	 *
	 * @return true|WP_Error
	 */
	public function can_edit() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden', __( 'You don\'t have permission to do this.', 'really-rich-results' ), array( 'status' => 401 ) );
	}

	/**
	 * REST API permissions callback for editing site settings.
	 *
	 * @return true|WP_Error
	 */
	public function can_read() {
		if ( current_user_can( 'edit_posts' ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden', __( 'You don\'t have permission to do this.', 'really-rich-results' ), array( 'status' => 401 ) );
	}

}
