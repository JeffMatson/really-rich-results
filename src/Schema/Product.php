<?php
/**
 * Builds properties for the Product schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the Product schema type.
 *
 * @see https://schema.org/Product
 */
class Product extends Thing {

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

		$this->set_schema_prop( 'offers', $this->get_offers( $source ) );
		$this->set_schema_prop( 'sku', $this->get_sku( $source ) );
	}

	/**
	 * Gets the offers property.
	 *
	 * @param object $source The data source.
	 *
	 * @return array
	 */
	public function get_offers( $source ) {
		$offers        = $source->get_schema_property( 'offers' );
		$offers_schema = array();

		if ( ! empty( $offers ) ) {
			foreach ( $offers as $offer ) {
				if ( is_object( $offer ) && $offer->get_type() === 'Offer' ) {
					$offer_schema    = Schema::create( $offer, new Offer() );
					$offers_schema[] = $offer_schema->get_schema();
				}
			}
		}

		return $offers_schema;
	}

	/**
	 * Gets the sku property.
	 *
	 * @param object $source The data source.
	 *
	 * @return mixed
	 */
	public function get_sku( $source ) {
		return $source->get_schema_property( 'sku' );
	}
}
