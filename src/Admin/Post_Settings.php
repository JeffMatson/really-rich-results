<?php
/**
 * Contains the Post_Settings admin class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Admin;

/**
 * Contains functionality related to post settings.
 */
class Post_Settings {

	/**
	 * Actions to run on the WordPress `init` action hook.
	 *
	 * @return void
	 */
	public function init() {
		$this->register_post_meta();
	}

	/**
	 * Register any post meta associated with post settings.
	 *
	 * @return void
	 */
	protected function register_post_meta() {
		$product_rest_schema = array(
			'type'       => 'object',
			'properties' => array(
				'mpn'          => array( 'type' => 'string' ),
				'sku'          => array( 'type' => 'string' ),
				'name'         => array( 'type' => 'string' ),
				'description'  => array( 'type' => 'string' ),
				'image'        => array( 'type' => 'string' ),
				'price'        => array( 'type' => 'number' ),
				'currency'     => array( 'type' => 'string' ),
				'brand'        => array( 'type' => 'string' ),
				'availability' => array( 'type' => 'string' ),
			),
		);

		$post_meta_prefix = 'really_rich_results_';

		$post_meta = array(
			'override_defaults' => array(
				'show_in_rest' => true,
				'single'       => true,
				'default'      => false,
				'type'         => 'boolean',
			),
			'schema_type'       => array(
				'show_in_rest' => true,
				'single'       => true,
				'default'      => '',
				'type'         => 'string',
			),
			'product_enabled'   => array(
				'show_in_rest' => true,
				'single'       => true,
				'default'      => false,
				'type'         => 'boolean',
			),
			'product'           => array(
				'single'       => false,
				'type'         => 'object',
				'show_in_rest' => array(
					'schema' => $product_rest_schema,
				),
			),
		);

		$post_meta = apply_filters( 'really_rich_results_post_meta', $post_meta, $post_meta_prefix );

		foreach ( $post_meta as $meta_key => $meta_props ) {
			register_post_meta( '', $post_meta_prefix . $meta_key, $meta_props );
		}
	}
}
