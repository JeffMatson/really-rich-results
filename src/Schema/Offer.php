<?php
/**
 * Builds properties for the Offer schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the Offer schema type.
 *
 * @see https://schema.org/Offer
 */
class Offer extends Thing {

	/**
	 * Map of schema availability types.
	 *
	 * @var array
	 */
	private $availability_types = array(
		'in_stock'             => 'https://schema.org/InStock',
		'sold_out'             => 'https://schema.org/SoldOut',
		'out_of_stock'         => 'https://schema.org/OutOfStock',
		'in_store_only'        => 'https://schema.org/InStoreOnly',
		'online_only'          => 'https://schema.org/OnlineOnly',
		'pre_order'            => 'https://schema.org/PreOrder',
		'pre_sale'             => 'https://schema.org/PreSale',
		'discontinued'         => 'https://schema.org/Discontinued',
		'limited_availability' => 'https://schema.org/LimitedAvailability',
	);

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

		$this->set_schema_prop( 'price', $this->get_price( $source ) );
		$this->set_schema_prop( 'priceCurrency', $this->get_currency( $source ) );
		$this->set_schema_prop( 'availability', $this->get_availability( $source ) );
	}

	/**
	 * Gets the price schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return int
	 */
	public function get_price( $source ) {
		return $source->get_schema_property( 'price' );
	}

	/**
	 * Gets the priceCurrency schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_currency( $source ) {
		return $source->get_schema_property( 'priceCurrency' );
	}

	/**
	 * Gets the availability schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return array|string
	 */
	public function get_availability( $source ) {
		$availability = $source->get_schema_property( 'availability' );

		if ( ! empty( $availability ) && array_key_exists( $availability, $this->availability_types ) ) {
			return $this->availability_types[ $availability ];
		}

		return $availability;
	}

}
