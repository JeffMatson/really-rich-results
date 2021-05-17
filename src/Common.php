<?php
/**
 * Contains the Common class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results;

/**
 * Common helper functions shared within Really Rich Results.
 */
class Common {

	/**
	 * Checks if an object is a WP_Post object.
	 *
	 * @param \WP_Post $object The object to check.
	 *
	 * @return boolean
	 */
	public static function is_wp_post( $object ) {
		return is_object( $object ) && get_class( $object ) === 'WP_Post';
	}

	/**
	 * Checks if an object is a WP_User object.
	 *
	 * @param \WP_User $object The object to check.
	 *
	 * @return boolean
	 */
	public static function is_wp_user( $object ) {
		return is_object( $object ) && get_class( $object ) === 'WP_User';
	}

	/**
	 * Checks if the object is a WP_Term object.
	 *
	 * @param \WP_Term $object The object to check.
	 *
	 * @return boolean
	 */
	public static function is_wp_term( $object ) {
		return is_object( $object ) && get_class( $object ) === 'WP_Term';
	}

	/**
	 * Checks if the object is a WP_Comment object.
	 *
	 * @param \WP_Comment $object The object to check.
	 *
	 * @return boolean
	 */
	public static function is_wp_comment( $object ) {
		return is_object( $object ) && get_class( $object ) === 'WP_Comment';
	}

	/**
	 * Used for checking if a variable contains a string. If not, return null.
	 *
	 * @param string $string The string.
	 *
	 * @return string|null
	 */
	public static function string_or_null( $string ) {
		if ( is_string( $string ) ) {
			return $string;
		}

		return null;
	}

	/**
	 * Used for checking if a variable is an integer (or at least numeric).
	 * If numeric, returns an interger. If not, returns null.
	 *
	 * @param string $int The integer.
	 *
	 * @return int|null
	 */
	public static function int_or_null( $int ) {
		if ( is_numeric( $int ) ) {
			return intval( $int );
		}

		return null;
	}

	/**
	 * Gets an array prop. If it doesn't exist, returns null.
	 *
	 * @param string $prop  The prop.
	 * @param array  $array The array to check.
	 *
	 * @return mixed|null
	 */
	public static function array_prop_or_null( $prop, $array ) {
		if ( ! is_string( $prop ) || ! is_array( $array ) ) {
			return null;
		}

		if ( array_key_exists( $prop, $array ) ) {
			return $array[ $prop ];
		}

		return null;
	}

	/**
	 * Converts a date string to the format expected by schema markup.
	 *
	 * @param string $date The date string.
	 *
	 * @return string
	 */
	public static function convert_date( $date ) {
		$date_time_obj = new \DateTime( $date );
		return $date_time_obj->format( \DateTime::ISO8601 );
	}
}
