<?php
/**
 * Contains the Schema class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results;

/**
 * Utility class for creating and using schema objects.
 */
class Schema {

	/**
	 * Builds a schema from a source object.
	 *
	 * @param object      $source The data source object to build from.
	 * @param object|null $target The schema target. If not set, the target will be determined using self::get_schema_target().
	 *
	 * @return object
	 */
	public static function build_schema( $source, $target = null ) {
		$target = apply_filters( 'really_rich_results_build_schema_target', $target, $source );

		if ( ! self::is_valid_schema_target( $target ) ) {
			$target = self::get_schema_target( $source );
		}

		$target->set_schema_props( $source );

		return $target;
	}

	/**
	 * Gets the schema target.
	 *
	 * @param Data_Sources\Abstract_Data_Source $source The data source.
	 *
	 * @return Schema\Thing
	 */
	public static function get_schema_target( $source ) {
		$target_type = $source->get_schema_type();

		$target = apply_filters( 'really_rich_results_get_schema_target', null, $target_type );

		if ( self::is_valid_schema_target( $target ) ) {
			return $target;
		}

		switch ( $target_type ) {
			case 'Article':
				return new Schema\Article();
			case 'AboutPage':
				return new Schema\AboutPage();
			case 'ContactPage':
				return new Schema\ContactPage();
			case 'Review':
				return new Schema\Review();
			default:
				return new Schema\WebPage();
		}
	}

	/**
	 * Checks if a schema target is valid.
	 * Valid schema targets must be an object and extend \Really_Rich_Results\Schema\Thing.
	 *
	 * @param object $schema_target The schema target object.
	 *
	 * @return bool
	 */
	public static function is_valid_schema_target( $schema_target ) {
		return is_object( $schema_target ) && is_subclass_of( $schema_target, '\\Really_Rich_Results\\Schema\\Thing' );
	}
}
