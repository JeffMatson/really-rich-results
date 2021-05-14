<?php
/**
 * Handles REST API routes.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results;

use WP_REST_Server;

/**
 * Main routing class.
 *
 * @package Really_Rich_Results
 */
class Routes {

	/**
	 * Holds the route namespace.
	 *
	 * @var string The REST API namespace to use.
	 */
	private $namespace = 'really_rich_results/v1';

	/**
	 * Initializes actions to perform.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register routes for settings API calls.
	 *
	 * @return void
	 */
	public function register_routes() {

		$site_settings      = new Routes\Settings\Site();
		$post_type_settings = new Routes\Settings\Post_Types();

		// Register the site settings route.
		register_rest_route(
			$this->namespace,
			'/settings/site',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $site_settings, 'read' ),
					'permission_callback' => array( $site_settings, 'can_read' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $site_settings, 'edit' ),
					'permission_callback' => array( $site_settings, 'can_edit' ),
				),
			)
		);

		// Register the post type settings route.
		register_rest_route(
			$this->namespace,
			'/settings/post_types',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $post_type_settings, 'read' ),
					'permission_callback' => array( $post_type_settings, 'can_read' ),
				),
			)
		);

		// Register the single post type settings route.
		register_rest_route(
			$this->namespace,
			'/settings/post_types/(?P<slug>(.*)+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $post_type_settings, 'read' ),
					'permission_callback' => array( $post_type_settings, 'can_read' ),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $post_type_settings, 'edit' ),
					'permission_callback' => array( $post_type_settings, 'can_edit' ),
				),
			)
		);
	}
}
