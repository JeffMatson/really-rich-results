<?php
/**
 * Builds and outputs schema for single posts.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Content_Types;

use Really_Rich_Results\Data_Sources\WP_Post;
use Really_Rich_Results\Factories\Schema;

/**
 * Class for single content types.
 */
class Single extends Abstract_Content_Type {

	/**
	 * Holds the post data source.
	 *
	 * @var WP_Post|null $post Holds the single WP_Post data source.
	 */
	private $post;

	/**
	 * Holds any items that should be added to the content type under the hasPart property.
	 *
	 * @var array
	 */
	private $parts = array();

	/**
	 * Sets the primary content to build the schema against.
	 *
	 * @param WP_Post $post The post data source.
	 *
	 * @return void
	 */
	public function set_primary_content( $post ) {
		$this->post = $post;
	}

	/**
	 * Holds objects that are meant to be a part of the primary content.
	 *
	 * @param mixed $part The part to add.
	 *
	 * @return void
	 */
	public function add_part( $part ) {
		$this->parts[] = $part;
	}

	/**
	 * Builds a schema object based on the the post.
	 *
	 * @return \Really_Rich_Results\Schema\Thing
	 */
	public function build_schema() {
		$primary_schema = Schema::create( $this->post );

		if ( ! empty( $this->parts ) ) {
			$primary_schema->set_schema_prop( 'hasPart', $this->parts );
		}

		return $primary_schema;
	}
}
