<?php
/**
 * Contains the schema factory class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Factories;

/**
 * Utility class for creating and using schema objects.
 */
class Schema {

	/**
	 * Builds schema from a data source.
	 *
	 * @param object      $data_source The data source.
	 * @param object|null $target      The schema target.
	 *
	 * @return object
	 */
	public static function create( $data_source, $target = null ) {
		if ( empty( $target ) ) {
			$target = self::detect_target( $data_source );
		}

		$target->set_schema_props( $data_source );

		return $target;
	}

	/**
	 * Detects the appropriate target based on the data source.
	 *
	 * @param mixed $source The data source to check.
	 *
	 * @return object
	 */
	private static function detect_target( $source ) {
		$target_type = $source->get_schema_type();

		$target = apply_filters( 'really_rich_results_get_schema_target', null, $target_type );

		if ( is_object( $target ) ) {
			return $target;
		}

		if ( ! empty( $target_type ) && is_string( $target_type ) && class_exists( '\\Really_Rich_Results\\Schema\\' . $target_type ) ) {
			$target_classname = '\\Really_Rich_Results\\Schema\\' . $target_type;
			return new $target_classname();
		}

		return new \Really_Rich_Results\Schema\WebPage();
	}
}
