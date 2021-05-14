<?php
/**
 * Builds properties for the ImageObject schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the ImageObject schema type.
 *
 * @see https://schema.org/ImageObject
 */
class ImageObject extends MediaObject {

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

		$this->set_schema_prop( 'caption', $this->get_caption( $source ) );
	}

	/**
	 * Gets the caption schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string|null
	 */
	public function get_caption( $source ) {
		return $source->get_schema_property( 'caption' );
	}
}
