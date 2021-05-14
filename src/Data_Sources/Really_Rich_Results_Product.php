<?php
/**
 * Contains the Product data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

/**
 * The data source for products within Really Rich Results.
 *
 * @package Really_Rich_Results\Data_Sources
 */
class Really_Rich_Results_Product extends Abstract_Data_Source {

	/**
	 * Holds the original data.
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * Gets a schema property and maps it to a class method.
	 *
	 * To avoid duplicate processing, this checks to see if it's already stored
	 * in the object. If not, properties are mapped to class methods, then
	 * stored for later use. If a property isn't found and has no associated
	 * method, it will fail gracefully by returning null.
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed
	 */
	public function get_schema_property( $property ) {
		$result = apply_filters( 'really_rich_results_product_get_schema_property', false, $property, $this );

		if ( $result !== false ) {
			return $result;
		}

		// Check storage first.
		if ( $this->has_stored_schema_property( $property ) ) {
			return $this->get_stored_schema_property( $property );
		}

		// Map schema property names to methods.
		switch ( $property ) {
			case 'description':
				$result = $this->get_description();
				break;
			case 'image':
				$result = $this->get_image();
				break;
			case 'name':
				$result = $this->get_name();
				break;
			case 'offers':
				$result = $this->get_offers();
				break;
			case 'sku':
				$result = $this->get_sku();
				break;
			default:
				$result = null;
				break;
		}

		$this->store_schema_property( $property, $result );

		return $result;
	}

	/**
	 * Checks if a property exists in the data source.
	 *
	 * @param string $property The property name.
	 *
	 * @return bool
	 */
	public function has_product_property( $property ) {
		return array_key_exists( $property, $this->data );
	}

	/**
	 * Gets a property if it exists. Otherwise, returns null.
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed
	 */
	public function get_product_property( $property ) {
		if ( $this->has_product_property( $property ) ) {
			return $this->data[ $property ];
		}

		return null;
	}

	/**
	 * Sets a product property to be used in the data source.
	 *
	 * @param string $property  The property name.
	 * @param mixed  $value     The value to store.
	 * @param bool   $overwrite If existing properties should be overwritten.
	 *                          Defaults to false.
	 *
	 * @return void
	 */
	public function set_product_property( $property, $value, $overwrite = false ) {
		if ( $this->has_product_property( $property ) && ! $overwrite ) {
			return;
		}

		$this->data[ $property ] = $value;
	}

	/**
	 * Sets multiple product properties from an array.
	 *
	 * @param array $properties The properties to set.
	 * @param bool  $overwrite  If existing properties should be overwritten.
	 *                          Defaults to false.
	 *
	 * @return void
	 */
	public function merge_product_properties( $properties, $overwrite = false ) {
		if ( $overwrite ) {
			$this->data = array_merge( $this->data, $properties );
		} else {
			$this->data = array_merge( $properties, $this->data );
		}
	}

	/**
	 * Gets the product name property.
	 *
	 * @return string
	 */
	protected function get_name() {
		return $this->get_product_property( 'name' );
	}

	/**
	 * Gets the offers.
	 * Alias for self::get_offer() until multiple offers are supported here.
	 *
	 * @return array
	 */
	protected function get_offers() {
		return array( $this->get_offer() );
	}

	/**
	 * Gets and builds the product offer property.
	 *
	 * @return Generic
	 */
	protected function get_offer() {
		$offer = new Generic( 'Offer' );
		$offer->set_schema_property( 'price', $this->get_price() );
		$offer->set_schema_property( 'availability', $this->get_availability() );
		$offer->set_schema_property( 'url', $this->get_url() );
		$offer->set_schema_property( 'priceCurrency', $this->get_currency() );

		return $offer;
	}

	/**
	 * Gets the price property.
	 *
	 * @return string|int
	 */
	protected function get_price() {
		return $this->get_product_property( 'price' );
	}

	/**
	 * Gets the currency property.
	 *
	 * @return string
	 */
	protected function get_currency() {
		return $this->get_product_property( 'currency' );
	}

	/**
	 * Gets the availability property.
	 *
	 * @return string
	 */
	protected function get_availability() {
		return $this->get_product_property( 'availability' );
	}

	/**
	 * Gets the product image property.
	 *
	 * @return string
	 */
	protected function get_image() {
		return $this->get_product_property( 'image' );
	}

	/**
	 * Gets the product SKU property.
	 *
	 * @return string
	 */
	protected function get_sku() {
		return $this->get_product_property( 'sku' );
	}

	/**
	 * Gets the product description property.
	 *
	 * @return string
	 */
	protected function get_description() {
		return $this->get_product_property( 'description' );
	}

	/**
	 * Gets the product URL property.
	 *
	 * @return string
	 */
	protected function get_url() {
		return $this->get_product_property( 'url' );
	}

}
