<?php
/**
 * Contains the WP_User data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

/**
 * Class to convert WP_User objects into a usable data source.
 *
 * @package Really_Rich_Results\Data_Sources
 */
class WP_User extends Abstract_Data_Source {

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
			case 'familyName':
				$result = $this->get_last_name();
				break;
			case 'givenName':
				$result = $this->get_first_name();
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
	 * Gets the user id property.
	 *
	 * @return string
	 */
	private function get_id() {
		return $this->data->ID;
	}

	/**
	 * Gets the user's display name.
	 *
	 * @return string
	 */
	private function get_name() {
		return $this->data->display_name;
	}

	/**
	 * Gets the user's first name.
	 *
	 * @return string
	 */
	private function get_first_name() {
		return $this->data->first_name;
	}

	/**
	 * Gets the user's last name.
	 *
	 * @return string
	 */
	private function get_last_name() {
		return $this->data->last_name;
	}

	/**
	 * Gets the user's URL.
	 *
	 * @return string
	 */
	private function get_url() {
		return get_author_posts_url( $this->data->ID );
	}
}
