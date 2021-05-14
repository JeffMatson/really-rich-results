<?php
/**
 * Contains functions for validating various settings and properties.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results;

/**
 * Type checking class.
 *
 * @package Really_Rich_Results
 */
class Types {

	/**
	 * Checks if an object is a valid schema type.
	 * Schema types must extend Really_Rich_Results\Schema\Thing.
	 *
	 * @param Schema\Thing $schema_object The object to validate.
	 *
	 * @return bool
	 */
	public static function is_schema_object( $schema_object ) {
		// If it's not an object, don't even bother.
		if ( ! is_object( $schema_object ) ) {
			return false;
		}

		// Make sure it's extending the abstract content type.
		return is_subclass_of( $schema_object, '\\Really_Rich_Results\\Schema\\Thing' );
	}

	/**
	 * Checks if an object is a valid content type.
	 * Content types must extend Really_Rich_Results\Content_Types\Abstract_Content_Type
	 *
	 * @param Content_Types\Abstract_Content_Type $content_type The content type to check.
	 *
	 * @return bool
	 */
	public static function is_content_type( $content_type ) {
		// If it's not an object, don't even bother.
		if ( ! is_object( $content_type ) ) {
			return false;
		}

		// Make sure it's extending the abstract content type.
		return is_subclass_of( $content_type, '\\Really_Rich_Results\\Content_Types\\Abstract_Content_Type' );
	}
}
