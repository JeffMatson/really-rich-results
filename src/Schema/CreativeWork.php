<?php
/**
 * Builds properties for the CreativeWork schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the CreativeWork schema type.
 *
 * @see https://schema.org/CreativeWork
 */
class CreativeWork extends Thing {

	/**
	 * Sets schema properties from a data source object.
	 *
	 * @param object $source The source object.
	 *
	 * @see Really_Rich_Results\Data_Sources\Abstract_Data_Source
	 *
	 * @return void
	 */
	public function set_schema_props( $source ) {
		parent::set_schema_props( $source );

		$this->set_schema_prop( 'commentCount', $this->get_comment_count( $source ) );
		$this->set_schema_prop( 'discussionUrl', $this->get_discussion_url( $source ) );
		$this->set_schema_prop( 'headline', $this->get_headline( $source ) );
		$this->set_schema_prop( 'datePublished', $this->get_date_published( $source ) );
		$this->set_schema_prop( 'dateModified', $this->get_date_modified( $source ) );
		$this->set_schema_prop( 'author', $this->get_author( $source ) );
		$this->set_schema_prop( 'publisher', $this->get_publisher( $source ) );
		$this->set_schema_prop( 'abstract', $this->get_abstract( $source ) );
		$this->set_schema_prop( 'thumbnailUrl', $this->get_thumbnail_url( $source ) );
		$this->set_schema_prop( 'alternativeHeadline', $this->get_alternative_headline( $source ) );
		$this->set_schema_prop( 'inLanguage', $this->get_in_language( $source ) );
		$this->set_schema_prop( 'comment', $this->get_comment( $source ) );
		$this->set_schema_prop( 'text', $this->get_text( $source ) );
	}

	/**
	 * Gets the abstract schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_abstract( $source ) {
		return $source->get_schema_property( 'abstract' );
	}

	/**
	 * Gets the alternativeHeadline schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_alternative_headline( $source ) {
		return $source->get_schema_property( 'alternativeHeadline' );
	}

	/**
	 * Gets the author schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return object|string|null
	 */
	public function get_author( $source ) {
		$author = $source->get_schema_property( 'author' );

		if ( is_object( $author ) ) {
			$schema = Schema::create( $author, new Person() );
			return $schema->get_schema();
		}

		return $author;
	}

	/**
	 * Gets the commentCount schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return int|null
	 */
	public function get_comment_count( $source ) {
		return $source->get_schema_property( 'commentCount' );
	}

	/**
	 * Gets the dateCreated schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_date_created( $source ) {
		return $source->get_schema_property( 'dateCreated' );
	}

	/**
	 * Gets the dateModified schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_date_modified( $source ) {
		return $source->get_schema_property( 'dateModified' );
	}

	/**
	 * Gets the datePublished schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_date_published( $source ) {
		return $source->get_schema_property( 'datePublished' );
	}

	/**
	 * Gets the discussionUrl schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_discussion_url( $source ) {
		return $source->get_schema_property( 'discussionUrl' );
	}

	/**
	 * Gets the headline schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_headline( $source ) {
		return $source->get_schema_property( 'headline' );
	}

	/**
	 * Gets the inLanguage schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_in_language( $source ) {
		return $source->get_schema_property( 'inLanguage' );
	}

	/**
	 * Gets the publisher schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return Organization|string|null
	 */
	public function get_publisher( $source ) {
		$publisher = $source->get_schema_property( 'publisher' );

		if ( is_object( $publisher ) ) {
			$publisher_schema = Schema::create( $publisher, new Organization() );
			return $publisher_schema->get_schema();
		}

		return $publisher;
	}

	/**
	 * Gets the thumbnailUrl schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_thumbnail_url( $source ) {
		return $source->get_schema_property( 'thumbnailUrl' );
	}

	/**
	 * Gets the text schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_text( $source ) {
		return $source->get_schema_property( 'text' );
	}

	/**
	 * Gets the comment schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return array
	 */
	public function get_comment( $source ) {
		$comments_schema = array();
		$source_comments = $source->get_schema_property( 'comment' );

		if ( is_array( $source_comments ) ) {
			foreach ( $source_comments as $source_comment ) {
				$comment_schema    = Schema::create( $source_comment, new Comment() );
				$comments_schema[] = $comment_schema->get_schema();
			}
		}

		return $comments_schema;
	}

}
