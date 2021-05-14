<?php
/**
 * Builds properties for the Article schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the Article schema type.
 *
 * @see https://schema.org/Article
 */
class Article extends CreativeWork {

	/**
	 * Sets schema props from a data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return void
	 */
	public function set_schema_props( $source ) {
		parent::set_schema_props( $source );

		$this->set_schema_prop( 'articleBody', $this->get_article_body( $source ) );
		$this->set_schema_prop( 'articleSection', $this->get_article_section( $source ) );
		$this->set_schema_prop( 'wordCount', $this->get_word_count( $source ) );
	}

	/**
	 * Gets the article body.
	 *
	 * @param object $source The data source.
	 *
	 * @return mixed
	 */
	public function get_article_body( $source ) {
		return $source->get_schema_property( 'articleBody' );
	}

	/**
	 * Get the article section.
	 *
	 * @param object $source The data source.
	 *
	 * @return mixed
	 */
	public function get_article_section( $source ) {
		return $source->get_schema_property( 'articleSection' );
	}

	/**
	 * Gets the article word count.
	 *
	 * @param object $source The data source.
	 *
	 * @return mixed
	 */
	public function get_word_count( $source ) {
		return $source->get_schema_property( 'wordCount' );
	}
}
