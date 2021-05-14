<?php
/**
 * Contains the post type settings route class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Routes\Settings;

use WP_Error;
use WP_Post_Type;
use WP_REST_Request;

/**
 * Class for handling the `/settings/post_types` route.
 *
 * @package Really_Rich_Results\Admin\Routes\Settings
 */
class Post_Types {

	/**
	 * Gets post type settings.
	 *
	 * @param WP_REST_Request $request The request object.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function read( WP_REST_Request $request ) {
		$post_type_settings = array();
		$slug               = $this->get_slug_param( $request->get_params() );
		$post_types         = $this->get_post_types( $slug );

		// If no post types are found, there's nothing to do.
		if ( empty( $post_types ) ) {
			return rest_ensure_response( new WP_Error( 'no_post_types', __( 'No post types', 'really-rich-results' ) ) );
		}

		foreach ( $post_types as $post_type ) {
			$current_post_type_setting = $this->get_post_type_settings( $post_type );

			// If the slug for a post type was passed, just return the first result.
			if ( ! empty( $slug ) ) {
				return rest_ensure_response( $current_post_type_setting );
			}

			$post_type_settings[ $post_type->name ] = $current_post_type_setting;
		}

		return rest_ensure_response( $post_type_settings );
	}

	/**
	 * Gets stored post type settings, merging with defaults.
	 *
	 * @param WP_Post_Type $post_type The post type object to build from.
	 *
	 * @return array
	 */
	private function get_post_type_settings( $post_type ) {
		$defaults = array(
			'name'        => $post_type->name,
			'label'       => $post_type->label,
			'enabled'     => true,
			'schema_type' => 'WebPage',
			'show_author' => true,
		);

		$stored_post_type_settings = get_option( 'really_rich_results_post_type_' . $post_type->name, array() );

		$post_type_settings = wp_parse_args( $stored_post_type_settings, $defaults );

		return $post_type_settings;
	}

	/**
	 * Gets post types. Optionally matching a specific slug.
	 *
	 * @param string|null $slug The post type to search for.
	 *
	 * @return WP_Post_Type[]
	 */
	private function get_post_types( $slug = null ) {
		// Only get public post types.
		$post_type_args = array( 'public' => true );

		if ( ! empty( $slug ) ) {
			$post_type_args['name'] = $slug;
		}

		return get_post_types( $post_type_args, 'objects' );
	}

	/**
	 * Updates post type settings.
	 *
	 * @param WP_REST_Request $request The request being processed.
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function edit( WP_REST_Request $request ) {
		$slug = $this->get_slug_param( $request->get_params() );

		// If the slug isn't good, don't even bother.
		if ( ! empty( $slug ) ) {
			$body_params        = $request->get_json_params();
			$post_type_settings = $this->build_post_type_settings( $body_params );

			$response = update_option( 'really_rich_results_post_type_' . $slug, $post_type_settings );
		} else {
			$response = new WP_Error( 'rrr_error', __( 'Something went wrong', 'really-rich-results' ) );
		}

		return rest_ensure_response( $response );
	}

	/**
	 * REST API permissions callback for editing site settings.
	 *
	 * @return true|WP_Error
	 */
	public function can_read() {
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
	public function can_edit() {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return new WP_Error( 'rest_forbidden', __( 'You don\'t have permission to do this.', 'really-rich-results' ), array( 'status' => 401 ) );
	}

	/**
	 * Gets the slug parameter and makes sure it's a valid post type.
	 *
	 * @param array $params Parameters from WP_REST_Request.
	 *
	 * @return string|null String if the slug exists and is valid. null otherwise.
	 */
	private function get_slug_param( $params ) {
		if ( isset( $params['slug'] ) && ! empty( $params['slug'] ) ) {
			$slug = sanitize_key( $params['slug'] );

			if ( post_type_exists( $slug ) ) {
				return $slug;
			}
		}

		return null;
	}

	/**
	 * Builds post type settings.
	 *
	 * @param array $settings The settings being checked.
	 *
	 * @return array
	 */
	private function build_post_type_settings( $settings ) {
		$to_update = array();

		if ( array_key_exists( 'enabled', $settings ) && is_bool( $settings['enabled'] ) ) {
			$to_update['enabled'] = $settings['enabled'];
		}

		if ( array_key_exists( 'show_author', $settings ) && is_bool( $settings['show_author'] ) ) {
			$to_update['show_author'] = $settings['show_author'];
		}

		if ( array_key_exists( 'schema_type', $settings ) && is_string( $settings['schema_type'] ) ) {
			$to_update['schema_type'] = sanitize_text_field( $settings['schema_type'] );
		}

		return $to_update;
	}

}
