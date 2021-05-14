<?php
/**
 * Builds properties for the WebSite schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the WebSite schema type.
 *
 * @see https://schema.org/WebSite
 */
class WebSite extends CreativeWork {

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

		$this->set_schema_prop( 'issn', $this->get_issn( $source ) );
	}

	/**
	 * Gets the issn schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_issn( $source ) {
		return $source->get_schema_property( 'issn' );
	}

}
