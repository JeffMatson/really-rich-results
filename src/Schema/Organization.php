<?php
/**
 * Builds properties for the Organization schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the Organization schema type.
 *
 * @see https://schema.org/Organization
 */
class Organization extends Thing {

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

		$this->set_schema_prop( 'name', $this->get_name( $source ) );
		$this->set_schema_prop( 'url', $this->get_url( $source ) );
		$this->set_schema_prop( 'legalName', $this->get_legal_name( $source ) );
		$this->set_schema_prop( 'duns', $this->get_duns( $source ) );
		$this->set_schema_prop( 'logo', $this->get_logo( $source ) );
		$this->set_schema_prop( 'address', $this->get_address( $source ) );
	}

	/**
	 * Gets the name schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_name( $source ) {
		return $source->get_schema_property( 'name' );
	}

	/**
	 * Gets the legalName schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_legal_name( $source ) {
		return $source->get_schema_property( 'legalName' );
	}

	/**
	 * Gets the url schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_url( $source ) {
		return $source->get_schema_property( 'url' );
	}

	/**
	 * Gets the duns schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_duns( $source ) {
		return $source->get_schema_property( 'duns' );
	}

	/**
	 * Gets the logo schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return ImageObject|string
	 */
	public function get_logo( $source ) {
		$logo = $source->get_schema_property( 'logo' );

		if ( is_object( $logo ) ) {
			$schema = Schema::create( $logo, new ImageObject() );
			return $schema->get_schema();
		}

		return $logo;
	}

	/**
	 * Gets the address schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return PostalAddress|string
	 */
	public function get_address( $source ) {
		$address = $source->get_schema_property( 'address' );

		if ( is_object( $address ) ) {
			$schema = Schema::create( $address, new PostalAddress() );
			return $schema->get_schema();
		}

		return $address;
	}
}
