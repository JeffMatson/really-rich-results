<?php
/**
 * Contains the Generic data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

/**
 * Class to handle generic data sources.
 */
class Generic extends Abstract_Data_Source {

	/**
	 * Stores the source type. Defaults to Generic via the constructor.
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Contructor. Optionally takes the source type.
	 *
	 * @param string $type The type to assign.
	 */
	public function __construct( $type = 'Generic' ) {
		$this->type = $type;
	}

	/**
	 * Get the data source type.
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Sets the type.
	 * This should only be used if the type changes or isn't known when instantiated.
	 *
	 * @param string $type The type to assign.
	 *
	 * @return void
	 */
	public function set_type( string $type ) {
		$this->type = $type;
	}

	/**
	 * Gets a schema property from the object.
	 *
	 * @see Abstract_Data_Source::get_stored_schema_property()
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed
	 */
	public function get_schema_property( $property ) {
		return $this->get_stored_schema_property( $property );
	}

	/**
	 * Sets a schema property on the object.
	 *
	 * @see Abstract_Data_Source::store_schema_property()
	 *
	 * @param string $property The property name.
	 * @param mixed  $value    The value.
	 *
	 * @return void
	 */
	public function set_schema_property( $property, $value ) {
		$this->store_schema_property( $property, $value, true );
	}

	/**
	 * Gets products from a generic data source.
	 * Just a placeholder for now until generic supports products.
	 *
	 * @return array
	 */
	public function get_products() {
		return array();
	}
}
