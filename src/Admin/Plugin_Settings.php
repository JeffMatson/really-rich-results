<?php
/**
 * Contains the main plugin settings class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Admin;

/**
 * Handles actions related to plugin settings.
 */
class Plugin_Settings {

	/**
	 * Init function.
	 *
	 * @return void
	 */
	public function init() {
		$this->register_settings();
	}

	/**
	 * Registers plugin settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		$rest_schema = array(
			'type'       => 'object',
			'properties' => array(
				'orgName' => array(
					'type' => 'string',
				),
			),
		);

		$setting_props = array(
			'default'      => '',
			'type'         => 'object',
			'single'       => true,
			'show_in_rest' => array(
				'schema' => $rest_schema,
			),
		);

		register_setting( 'really_rich_results', 'really_rich_results_global', $setting_props );
		$this->register_org_name_setting();
		$this->register_org_url_setting();
		$this->register_org_duns_setting();
		$this->register_org_address_setting();
		$this->register_org_same_as_setting();
	}

	/**
	 * Registers the really_rich_results_org_name setting.
	 *
	 * @return void
	 */
	private function register_org_name_setting() {
		register_setting(
			'really_rich_results',
			'really_rich_results_org_name',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}

	/**
	 * Registers the really_rich_results_org_url setting.
	 *
	 * @return void
	 */
	private function register_org_url_setting() {
		register_setting(
			'really_rich_results',
			'really_rich_results_org_url',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'wp_http_validate_url',
			)
		);
	}

	/**
	 * Registers the really_rich_results_org_duns setting.
	 *
	 * @return void
	 */
	private function register_org_duns_setting() {
		register_setting(
			'really_rich_results',
			'really_rich_results_org_duns',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}

	/**
	 * Registers the really_rich_results_org_address setting.
	 *
	 * @return void
	 */
	private function register_org_address_setting() {
		register_setting(
			'really_rich_results',
			'really_rich_results_org_address',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_org_address' ),
			)
		);
	}

	/**
	 * Registers the really_rich_results_org_same_as setting.
	 *
	 * @return void
	 */
	private function register_org_same_as_setting() {
		register_setting(
			'really_rich_results',
			'really_rich_results_org_same_as',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_org_same_as' ),
			)
		);
	}

	/**
	 * Sanitizes address settings.
	 *
	 * @param array $org_address The address settings.
	 *
	 * @return array
	 */
	public function sanitize_org_address( $org_address ) {
		$sanitized    = array();
		$valid_fields = array(
			'street',
			'poBox',
			'city',
			'state',
			'postalCode',
			'country',
		);

		foreach ( $valid_fields as $valid_field ) {
			if ( isset( $org_address[ $valid_field ] ) && is_string( $org_address[ $valid_field ] ) ) {
				$sanitized[ $valid_field ] = sanitize_text_field( $org_address[ $valid_field ] );
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitizes same as settings.
	 *
	 * @param array $same_as Array of sameAs URLs.
	 *
	 * @return array
	 */
	public function sanitize_org_same_as( $same_as ) {
		$sanitized = array();
		foreach ( $same_as as $url ) {
			if ( wp_http_validate_url( $url ) ) {
				$sanitized[] = $url;
			}
		}

		return $sanitized;
	}
}
