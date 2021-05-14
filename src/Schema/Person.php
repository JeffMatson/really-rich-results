<?php
/**
 * Builds properties for the Person schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the Person schema type.
 *
 * @see https://schema.org/Person
 */
class Person extends Thing {

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

		$this->set_schema_prop( 'givenName', $this->get_given_name( $source ) );
		$this->set_schema_prop( 'familyName', $this->get_family_name( $source ) );
		$this->set_schema_prop( 'name', $this->get_name( $source ) );
	}

	/**
	 * Gets the givenName schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_given_name( $source ) {
		return $source->get_schema_property( 'givenName' );
	}

	/**
	 * Gets the familyName schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_family_name( $source ) {
		return $source->get_schema_property( 'familyName' );
	}

}
