<?php
/**
 * Builds properties for the PostalAddress schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the PostalAddress schema type.
 *
 * @see https://schema.org/PostalAddress
 */
class PostalAddress extends Thing {

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

		$this->set_schema_prop( 'addressCountry', $this->get_country( $source ) );
		$this->set_schema_prop( 'addressLocality', $this->get_locality( $source ) );
		$this->set_schema_prop( 'addressRegion', $this->get_region( $source ) );
		$this->set_schema_prop( 'postOfficeBoxNumber', $this->get_po_box_number( $source ) );
		$this->set_schema_prop( 'postalCode', $this->get_postal_code( $source ) );
		$this->set_schema_prop( 'streetAddress', $this->get_street_address( $source ) );
	}

	/**
	 * Gets the addressCountry schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_country( $source ) {
		return $source->get_schema_property( 'addressCountry' );
	}

	/**
	 * Gets the addressLocality schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_locality( $source ) {
		return $source->get_schema_property( 'addressLocality' );
	}

	/**
	 * Gets the addressRegion schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_region( $source ) {
		return $source->get_schema_property( 'addressRegion' );
	}

	/**
	 * Gets the postOfficeBoxNumber schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_po_box_number( $source ) {
		return $source->get_schema_property( 'postOfficeBoxNumber' );
	}

	/**
	 * Gets the postalCode schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_postal_code( $source ) {
		return $source->get_schema_property( 'postalCode' );
	}

	/**
	 * Gets the streetAddress schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_street_address( $source ) {
		return $source->get_schema_property( 'streetAddress' );
	}
}
