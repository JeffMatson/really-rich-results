<?php
/**
 * Contains the WP_Term data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

use WP_Error;

/**
 * Data source class for handling WP_Term objects.
 *
 * @package Really_Rich_Results\Data_Sources
 */
class WP_Term extends Abstract_Data_Source {

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
		// Check storage first.
		if ( $this->has_stored_schema_property( $property ) ) {
			return $this->get_stored_schema_property( $property );
		}

		// Map schema property names to methods.
		switch ( $property ) {
			case '@id':
				$result = $this->get_id();
				break;
			case 'abstract':
				$result = $this->get_description();
				break;
			case 'description':
				$result = $this->get_description();
				break;
			case 'name':
				$result = $this->get_name();
				break;
			case 'url':
				$result = $this->get_url();
				break;
			default:
				$result = null;
				break;
		}

		$this->store_schema_property( $property, $result );

		return $result;
	}

	/**
	 * Gets the schema type.
	 * Returns a static value, but will likely change later.
	 *
	 * @return string
	 */
	public function get_schema_type() {
		return 'WebPage';
	}

	/**
	 * Gets the term description.
	 *
	 * @return string
	 */
	protected function get_description() {
		return $this->data->description;
	}

	/**
	 * Gets the term ID.
	 *
	 * @return string
	 */
	protected function get_id() {
		return $this->data->term_id;
	}

	/**
	 * Gets the term name.
	 *
	 * @return string
	 */
	protected function get_name() {
		return $this->data->name;
	}

	/**
	 * Gets the term URL.
	 *
	 * @return string|WP_Error
	 */
	protected function get_url() {
		return get_term_link( $this->data );
	}
}
