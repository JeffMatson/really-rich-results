<?php
/**
 * Builds properties for the Review schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the Review schema type.
 *
 * @see https://schema.org/Review
 */
class Review extends CreativeWork {

	/**
	 * Sets schema properties from a data source object.
	 *
	 * @param object $source The source object.
	 *
	 * @see \Really_Rich_Results\Data_Sources\Abstract_Data_Source
	 *
	 * @return void
	 */
	public function set_schema_props( $source ) {
		$this->set_context_if_empty( $source, $this );

		parent::set_schema_props( $source );

		$this->set_schema_prop( 'reviewBody', $this->get_review_body( $source ) );
		$this->set_schema_prop( 'itemReviewed', $this->get_item_reviewed( $source ) );

		$source->clear_context();
	}

	/**
	 * Gets the reviewBody schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_review_body( $source ) {
		return $source->get_schema_property( 'reviewBody' );
	}

	/**
	 * Gets the itemReviewed schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return mixed
	 */
	public function get_item_reviewed( $source ) {
		$item_reviewed = $source->get_schema_property( 'itemReviewed' );

		// Check if it's an organization data source and build Organization schema.
		if ( is_object( $item_reviewed ) && $item_reviewed->get_type() === 'Organization' ) {
			$reviewed_schema = Schema::create( $item_reviewed, new Organization() );
			return $reviewed_schema->get_schema();
		}

		return null;
	}
}
