<?php
/**
 * Contains the abstract class for content types.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Content_Types;

/**
 * Content type abstract class.
 *
 * @package Really_Rich_Results\Content_Types
 */
abstract class Abstract_Content_Type {

	/**
	 * Base abstract function to use for building the content type's schema.
	 *
	 * @return mixed
	 */
	abstract public function build_schema();

	/**
	 * Builds the schema object and returns the JSON-encoded string.
	 *
	 * @uses self::build_schema()
	 *
	 * @return string
	 */
	public function get_json() {
		return $this->build_schema()->get_json();
	}
}
