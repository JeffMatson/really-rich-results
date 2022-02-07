<?php
/**
 * Builds and outputs schema for single posts.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Content_Types;

use Really_Rich_Results\Data_Sources\WP_Post;
use Really_Rich_Results\Data_Sources\Generic;
use Really_Rich_Results\Factories\Schema;
use Really_Rich_Results\Schema\ImageObject;

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
	 * Gets the primary content being used.
	 *
	 * @return WP_Post
	 */
	public function get_primary_content() {
		return $this->post;
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
	 * Gets any image blocks chilling in the content.
	 * 
	 * @return array
	 */
	public function get_content_image_blocks() {
		$attached_images = array();
		$parsed_blocks   = $this->post->get_content_block_data();

		foreach ( $parsed_blocks as $parsed_block ) {
			if ( $parsed_block['blockName'] === 'core/image' ) {
				$img_src = wp_get_attachment_image_src( $parsed_block['attrs']['id'], $parsed_block['attrs']['sizeSlug'] );
				
				$image_object = new Generic( 'ImageObject' );
				$image_object->set_schema_property( 'url', $img_src[0] );
				$image_object->set_schema_property( 'contentUrl', $img_src[0] );
				$image_object->set_schema_property( 'width', $img_src[1] );
				$image_object->set_schema_property( 'height', $img_src[2] );
				$image_object->set_schema_property( 'caption', $this->parse_image_block_caption( $parsed_block['innerHTML'] ) );

				$attached_images[] = $image_object;
 			}
		}

		return $attached_images;
	}

	/**
	 * Parses image blocks for captions.
	 * 
	 * @param string $inner_html The inner HTML of the image block.
	 * 
	 * @return string|null
	 */
	public function parse_image_block_caption( $inner_html ) {
		preg_match_all( '/(?>\<figcaption\>)(.*)(?>\<\/figcaption\>)/m', $inner_html, $matches, PREG_SET_ORDER, 0 );
		
		if ( ! empty( $matches ) ) {
			return $matches[0][1];
		}
		
		return null;
	}

	/**
	 * Builds a schema object based on the the post.
	 *
	 * @return \Really_Rich_Results\Schema\Thing
	 */
	public function build_schema() {
		$primary_schema = Schema::create( $this->post );

		$post_content_images = $this->get_content_image_blocks();

		if ( ! empty( $post_content_images ) ) {
			foreach ( $post_content_images as $post_content_image ) {
				$this->parts[] = Schema::create( $post_content_image, new ImageObject() )->get_schema();
			}
		}

		if ( ! empty( $this->parts ) ) {
			$primary_schema->set_schema_prop( 'hasPart', $this->parts );
		}

		return $primary_schema;
	}
}
