<?php
/**
 * Builds properties for the WebPage schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the WebPage schema type.
 *
 * @see https://schema.org/WebPage
 */
class WebPage extends CreativeWork {

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

		$this->set_schema_prop( 'lastReviewed', $this->get_last_reviewed( $source ) );
		$this->set_schema_prop( 'reviewedBy', $this->get_reviewed_by( $source ) );
		$this->set_schema_prop( 'primaryImageOfPage', $this->get_primary_image_of_page( $source ) );
	}

	/**
	 * Gets the lastReviewed schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_last_reviewed( $source ) {
		return $source->get_schema_property( 'lastReviewed' );
	}

	/**
	 * Gets the reviewedBy schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|array
	 */
	public function get_reviewed_by( $source ) {
		$reviewed_by = $source->get_schema_property( 'reviewedBy' );

		if ( is_object( $reviewed_by ) ) {
			$schema = Schema::create( $reviewed_by, new Organization() );
			return $schema->get_schema();
		}

		return $reviewed_by;
	}

	/**
	 * Gets the primaryImageOfPage schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|array
	 */
	public function get_primary_image_of_page( $source ) {
		$primary_image = $source->get_schema_property( 'primaryImageOfPage' );

		if ( is_object( $primary_image ) ) {
			$schema = Schema::create( $primary_image, new ImageObject() );
			return $schema->get_schema();
		}

		return $primary_image;
	}
}
