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
	 * Checks if an org name is valid.
	 *
	 * @param string $org_name The org name to check.
	 *
	 * @return bool
	 */
	public static function is_valid_org_name( $org_name ) {
		return is_string( $org_name ) && ! empty( $org_name );
	}

	/**
	 * Checks if a URL is valid.
	 *
	 * @param string $url The URL to check.
	 *
	 * @return bool
	 */
	public static function is_valid_url( $url ) {
		if ( is_string( $url ) && ! empty( $url ) ) {
			return wp_http_validate_url( $url ) !== false;
		}

		return false;
	}

	/**
	 * Checks if a DUNS number is valid.
	 * Must be 9 digits.
	 *
	 * @param string $duns The 9 digit DUNS number.
	 *
	 * @return bool
	 */
	public static function is_valid_duns( $duns ) {
		return is_string( $duns ) && is_numeric( $duns ) && strlen( $duns ) === 9;
	}

	/**
	 * Checks if a sameAs array is valid.
	 *
	 * @param array $same_as The array to check.
	 *
	 * @return bool
	 */
	public static function is_valid_same_as( $same_as ) {
		if ( ! is_array( $same_as ) ) {
			return false;
		}

		foreach ( $same_as as $value ) {
			if ( ! self::is_valid_url( $value ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Checks if an address is valid.
	 * Most of the address validation is quite dumb at the moment. To be improved over time.
	 *
	 * @param array $address Array of address items.
	 *
	 * @return bool
	 */
	public static function is_valid_address( $address ) {
		// Street should always exist.
		if ( ! array_key_exists( 'street', $address ) || empty( $address['street'] ) ) {
			return false;
		}

		// Basic PO Box validation.
		if ( array_key_exists( 'poBox', $address ) && ! self::is_valid_address_po_box( $address['poBox'] ) ) {
			return false;
		}

		// Basic city validation.
		if ( array_key_exists( 'city', $address ) && ! self::is_valid_address_city( $address['city'] ) ) {
			return false;
		}

		// Basic state validation.
		if ( array_key_exists( 'state', $address ) && ! self::is_valid_address_state( $address['city'] ) ) {
			return false;
		}

		// Basic postal code validation.
		if ( array_key_exists( 'postalCode', $address ) && ! self::is_valid_address_postal_code( $address['postalCode'] ) ) {
			return false;
		}

		// Basic country validation.
		if ( array_key_exists( 'country', $address ) && ! self::is_valid_address_country( $address['country'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if an address is valid.
	 *
	 * @param string $street The street.
	 *
	 * @return bool
	 */
	public static function is_valid_address_street( $street ) {
		return is_string( $street );
	}

	/**
	 * Checks if a po box value is valid.
	 *
	 * @param string $po_box The PO Box.
	 *
	 * @return bool
	 */
	public static function is_valid_address_po_box( $po_box ) {
		return is_string( $po_box );
	}

	/**
	 * Checks if a city value is valid.
	 *
	 * @param string $city The city.
	 *
	 * @return bool
	 */
	public static function is_valid_address_city( $city ) {
		return is_string( $city );
	}

	/**
	 * Checks if a state value is valid.
	 *
	 * @param string $state The state.
	 *
	 * @return bool
	 */
	public static function is_valid_address_state( $state ) {
		return is_string( $state );
	}

	/**
	 * Checks if a postal code value is valid.
	 *
	 * @param string $postal_code The postal code.
	 *
	 * @return bool
	 */
	public static function is_valid_address_postal_code( $postal_code ) {
		return is_string( $postal_code );
	}

	/**
	 * Checks if a country value is valid.
	 *
	 * @param string $country The country.
	 *
	 * @return bool
	 */
	public static function is_valid_address_country( $country ) {
		return is_string( $country );
	}
}
