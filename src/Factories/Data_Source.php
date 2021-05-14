<?php
/**
 * Contains the data source factory.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Factories;

use Really_Rich_Results\Common;

/**
 * Makes the data sources.
 *
 * @package Really_Rich_Results\Factories
 */
class Data_Source {

	/**
	 * Transforms an input into a data source object.
	 *
	 * @param mixed      $original       The input to transform.
	 * @param mixed|null $transform_into The target to be transformed into.
	 *
	 * @return object
	 */
	public static function create( $original, $transform_into = null ) {
		if ( ! empty( $transform_into ) && self::is_valid_target( $transform_into ) ) {
			$transformed = $transform_into;
		} else {
			$transformed = self::detect_transform_target( $original );
		}

		$transformed->set_data( $original );

		return $transformed;
	}

	/**
	 * Attempts to detect the transformation target of an input.
	 *
	 * @param mixed $transform_from The source to transform from.
	 *
	 * @return object
	 */
	private static function detect_transform_target( $transform_from ) {
		switch ( true ) {
			case Common::is_wp_post( $transform_from ):
				$data_source = new \Really_Rich_Results\Data_Sources\WP_Post();
				break;
			case Common::is_wp_term( $transform_from ):
				$data_source = new \Really_Rich_Results\Data_Sources\WP_Term();
				break;
			case Common::is_wp_user( $transform_from ):
				$data_source = new \Really_Rich_Results\Data_Sources\WP_User();
				break;
			case Common::is_wp_comment( $transform_from ):
				$data_source = new \Really_Rich_Results\Data_Sources\WP_Comment();
				break;
			default:
				$data_source = new \Really_Rich_Results\Data_Sources\Generic();
		}

		return apply_filters( 'really_rich_results_data_source_transform_target', $data_source, $transform_from );
	}

	/**
	 * Checks if a transformation target is valid.
	 *
	 * @param object $transform_into The data source to transform into.
	 *
	 * @return bool
	 */
	private static function is_valid_target( $transform_into ) {
		// If it's not an object, don't even bother.
		if ( ! is_object( $transform_into ) ) {
			return false;
		}

		// Make sure it's extending the abstract data source.
		return is_subclass_of( $transform_into, '\\Really_Rich_Results\\Data_Sources\\Abstract_Data_Source' );
	}

}
