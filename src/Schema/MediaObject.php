<?php
/**
 * Builds properties for the MediaObject schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the MediaObject schema type.
 *
 * @see https://schema.org/MediaObject
 */
class MediaObject extends Thing {

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

		$this->set_schema_prop( 'height', $this->get_height( $source ) );
		$this->set_schema_prop( 'width', $this->get_width( $source ) );
		$this->set_schema_prop( 'contentUrl', $this->get_content_url( $source ) );
	}

	/**
	 * Gets the height schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return int
	 */
	public function get_height( $source ) {
		return $source->get_schema_property( 'height' );
	}

	/**
	 * Gets the width schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return int
	 */
	public function get_width( $source ) {
		return $source->get_schema_property( 'width' );
	}

	/**
	 * Gets the contentUrl schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_content_url( $source ) {
		return $source->get_schema_property( 'contentUrl' );
	}
}
