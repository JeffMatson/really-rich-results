<?php
/**
 * Containers the base abstract class for handling data sources.
 *
 * These data source classes provide a way to unify how vastly different types
 * of data are handled, as well as store properties for later use.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

use Really_Rich_Results\Main;
use WP_Error;

/**
 * Data source abstract class.
 */
abstract class Abstract_Data_Source {

	/**
	 * Holds the original data passed to the data source.
	 *
	 * @var mixed
	 */
	protected $data = null;

	/**
	 * Holds schema properties.
	 *
	 * @var array
	 */
	protected $schema_properties = array();

	/**
	 * Holds the site config.
	 *
	 * @var mixed
	 */
	protected $site_config = null;

	/**
	 * If the data source has been processed.
	 *
	 * @var bool
	 */
	protected $processed_status = false;

	/**
	 * Holds the context in which the data source will be used.
	 *
	 * @var mixed
	 */
	protected $context = null;

	/**
	 * Sets the data that the source class will use.
	 *
	 * @param mixed $data The data that this data source should use.
	 *
	 * @return void|WP_Error
	 */
	public function set_data( $data ) {
		if ( ! empty( $this->data ) ) {
			return new WP_Error( 'really_rich_results', 'This Really Rich Results data source is already in use.', $this );
		}

		$this->data = $data;
	}

	/**
	 * Reads the data property.
	 *
	 * @return mixed
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * Maps schema properties to their associated class methods.
	 *
	 * All data sources should use this for mapping schema properties. If the
	 * property is unknown, this should return null.
	 *
	 * @see Generic
	 * @see WP_Post
	 *
	 * @api
	 *
	 * @param string $property The JSON+LD schema property to get.
	 *
	 * @return mixed
	 */
	abstract public function get_schema_property( string $property );

	/**
	 * Sets the context in which the data source is being currently used.
	 * Useful when manipulating property values in extensions.
	 *
	 * @param mixed $context The context to set.
	 *
	 * @return mixed
	 */
	public function set_context( $context ) {
		$this->context = $context;
	}

	/**
	 * Gets the context in which the data source is being currently used.
	 * Useful when manipulating property values in extensions.
	 *
	 * @return mixed
	 */
	public function get_context() {
		return $this->context;
	}

	/**
	 * Clears the current context.
	 * Used when finished processing a data source, since they could be re-used
	 * in another context.
	 *
	 * @return void
	 */
	public function clear_context() {
		$this->context = null;
	}

	/**
	 * Stores a schema property within the object.
	 *
	 * @uses self::$schema_properties
	 *
	 * @param string  $property  The schema property name.
	 * @param mixed   $value     The value to store.
	 * @param boolean $overwrite If a property is already stored, overwrite it. Defaults to false.
	 *
	 * @return void
	 */
	protected function store_schema_property( string $property, $value, $overwrite = false ) {
		if ( ! $this->has_stored_schema_property( $property ) || $overwrite === true ) {
			$this->schema_properties[ $property ] = $value;
		}
	}

	/**
	 * Gets a stored schema property from within this object.
	 *
	 * @param string $property The schema property name.
	 *
	 * @return mixed
	 */
	protected function get_stored_schema_property( string $property ) {
		if ( $this->has_stored_schema_property( $property ) ) {
			return $this->schema_properties[ $property ];
		}

		return null;
	}

	/**
	 * Checks if a schema property is already stored in the object.
	 *
	 * Note: This only checks to see if it's already stored. It does not check
	 * to see if it exists on the source type. To get schema properties, use
	 * self::get_schema_property()
	 *
	 * @param string $property The schema property name.
	 *
	 * @return bool
	 */
	protected function has_stored_schema_property( string $property ) {
		return array_key_exists( $property, $this->schema_properties );
	}

	/**
	 * Gets the site config from \Really_Rich_Results\Main for use by other data sources.
	 *
	 * @return Site
	 */
	protected function get_site_config() {
		if ( $this->site_config === null ) {
			$this->site_config = Main::get_instance()->get_site_config();
		}

		return $this->site_config;
	}

	/**
	 * Gets the processed status.
	 *
	 * @return bool
	 */
	public function get_processed_status() {
		return $this->processed_status;
	}

	/**
	 * Sets the processed status.
	 *
	 * @param bool $is_processed If the data source has been processed.
	 *
	 * @return void
	 */
	public function set_processed_status( bool $is_processed ) {
		$this->processed_status = $is_processed;
	}

	/**
	 * Get the schema type.
	 * Defaults to null unless overridden by a child class.
	 *
	 * @return null
	 */
	public function get_schema_type() {
		return null;
	}

	/**
	 * Get products associated with the data source.
	 * Defaults to an empty array unless overridden by a child class.
	 *
	 * @return array
	 */
	public function get_products() {
		return array();
	}

}
