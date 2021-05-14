<?php
/**
 * Contains the Archive content type class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Content_Types;

use Really_Rich_Results\Data_Sources\WP_Post;
use Really_Rich_Results\Factories\Schema;
use Really_Rich_Results\Data_Sources\List_Array;
use Really_Rich_Results\Schema\ItemList;

/**
 * Archive content type class.
 *
 * @package Really_Rich_Results\Content_Types
 */
class Archive extends Abstract_Content_Type {

	/**
	 * Holds any posts associated with the current content type.
	 *
	 * @var array Array of WP_Post data source objects.
	 */
	protected $posts = array();

	/**
	 * Holds the primary content of the content type.
	 *
	 * @var object
	 */
	protected $primary_content;

	/**
	 * Adds a post to be listed as part of the archive.
	 *
	 * @param WP_Post $post WP_Post content type.
	 *
	 * @return void
	 */
	public function add_archive_post( WP_Post $post ) {
		$this->posts[] = $post;
	}

	/**
	 * Sets the primary content of the content type.
	 *
	 * @param object $primary_content The primary content.
	 *
	 * @return void
	 */
	public function set_primary_content( $primary_content ) {
		$this->primary_content = $primary_content;
	}

	/**
	 * Builds the schema that will be used.
	 *
	 * @return object
	 */
	public function build_schema() {
		$primary_content_schema = Schema::create( $this->primary_content );
		$item_list_source       = new List_Array();

		foreach ( $this->posts as $archive_post ) {
			$item_list_source->add_list_item( Schema::create( $archive_post ) );
		}

		$item_list_schema = Schema::create( $item_list_source, new ItemList() );
		$primary_content_schema->set_schema_prop( 'mainEntity', $item_list_schema );

		return $primary_content_schema;
	}
}
