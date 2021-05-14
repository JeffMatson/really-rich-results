<?php
/**
 * Contains the Site data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

/**
 * Class to handle site-wide data.
 */
class Site extends Abstract_Data_Source {

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
			case 'abstract':
				$result = $this->get_description();
				break;
			case 'image':
				$result = $this->get_image();
				break;
			case 'name':
				$result = $this->get_name();
				break;
			case 'organization':
				$result = $this->get_organization();
				break;
			case 'potentialAction':
				$result = $this->get_potential_action();
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
	 * Gets the descrition site description.
	 *
	 * @return string|null
	 */
	protected function get_description() {
		$description = get_bloginfo( 'description' );

		if ( ! empty( $description ) ) {
			return $description;
		}

		return null;
	}

	/**
	 * Gets the site name.
	 *
	 * @return string|null
	 */
	protected function get_name() {
		$name = get_bloginfo( 'name' );

		if ( ! empty( $name ) ) {
			return $name;
		}

		return null;
	}

	/**
	 * Gets the site url.
	 *
	 * @return string
	 */
	protected function get_url() {
		return get_site_url();
	}

	/**
	 * Gets the site-wide image.
	 *
	 * @param string $size The size to get.
	 *
	 * @return Generic|null
	 */
	protected function get_image( $size = 'full' ) {
		// Check if a custom logo is defined in the customizer. If so, use that.
		if ( has_custom_logo() ) {
			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$custom_logo    = wp_get_attachment_image_src( $custom_logo_id, $size );

			if ( ! empty( $custom_logo ) ) {
				$image_object = new Generic( 'ImageObject' );
				$image_object->set_schema_property( 'url', $custom_logo[0] );
				$image_object->set_schema_property( 'contentUrl', $custom_logo[0] );
				$image_object->set_schema_property( 'width', $custom_logo[1] );
				$image_object->set_schema_property( 'height', $custom_logo[2] );

				return $image_object;
			}
		}

		// If the custom logo isn't defined, fail back to the header image.
		$header_image = get_header_image();
		if ( ! empty( $header_image ) ) {
			$image_object = new Generic( 'ImageObject' );
			$image_object->set_schema_property( 'url', $header_image );
			$image_object->set_schema_property( 'contentUrl', $header_image );
		}

		return null;
	}

	/**
	 * Gets the search URL.
	 *
	 * @return string
	 */
	private function get_search_url() {
		return trailingslashit( get_site_url() ) . '?s=';
	}

	/**
	 * Gets the potential action.
	 *
	 * Currently only used for the home page to get a SearchAction schema prop.
	 *
	 * @return Generic
	 */
	protected function get_potential_action() {
		$search_action = new Generic( 'SearchAction' );
		$search_action->set_schema_property( 'url', $this->get_url() );
		$search_action->set_schema_property( 'target', $this->get_search_url() . '{search_term_string}' );
		$search_action->set_schema_property( 'query-input', 'required name=search_term_string' );

		return $search_action;
	}

	/**
	 * Gets the site's Organization schema prop.
	 *
	 * TODO: Prolly map and loop this.
	 *
	 * @return Generic
	 */
	protected function get_organization() {
		$organization = new Generic( 'Organization' );

		$organization->set_schema_property( 'name', get_option( 'really_rich_results_org_name', get_bloginfo( 'name' ) ) );
		$organization->set_schema_property( 'legalName', get_option( 'really_rich_results_org_legal_name', get_bloginfo( 'name' ) ) );
		$organization->set_schema_property( 'url', get_option( 'really_rich_results_org_url', get_site_url() ) );
		$organization->set_schema_property( 'duns', get_option( 'really_rich_results_org_duns', false ) );
		$organization->set_schema_property( 'logo', get_option( 'really_rich_results_org_logo', $this->get_image() ) );

		$organization_address = new Generic( 'Address' );
		$organization_address->set_schema_property( 'addressCountry', get_option( 'really_rich_results_org_country' ) );
		$organization_address->set_schema_property( 'addressLocality', get_option( 'really_rich_results_org_locality' ) );
		$organization_address->set_schema_property( 'addressRegion', get_option( 'really_rich_results_org_region' ) );
		$organization_address->set_schema_property( 'postOfficeBoxNumber', get_option( 'really_rich_results_org_po_box' ) );
		$organization_address->set_schema_property( 'postalCode', get_option( 'really_rich_results_org_postal_code' ) );
		$organization_address->set_schema_property( 'streetAddress', get_option( 'really_rich_results_org_street' ) );

		$organization->set_schema_property( 'address', $organization_address );

		return $organization;
	}
}
