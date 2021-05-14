<?php
/**
 * Builds properties for the SearchAction schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the SearchAction schema type.
 *
 * @see https://schema.org/SearchAction
 */
class SearchAction extends Action {

	/**
	 * Sets schema properties from a data source object.
	 *
	 * @param object $source The source object.
	 *
	 * @see Really_Rich_Results\Data_Sources\Abstract_Data_Source
	 *
	 * @return void
	 */
	public function set_schema_props( $source ) {
		/**
		 * We're doing some non-standard property stuff here. Google uses a modified
		 * SearchAction property for their Sitelinks searchbox. While I agree
		 * that the schema.org standard should be adhered to, Google is still
		 * the primary consumer of schema markup and needs to be accounted for.
		 *
		 * @see https://developers.google.com/search/docs/data-types/sitelinks-searchbox
		 */
		$this->set_schema_prop( '@type', 'SearchAction' );
		$this->set_schema_prop( 'url', $this->get_url( $source ) );
		$this->set_schema_prop( 'target', $this->get_target( $source ) );
		$this->set_schema_prop( 'query-input', $this->get_query_input( $source ) );
	}

	/**
	 * Gets the query-input schema property from the data source.
	 *
	 * Note: This is a non-standard property introduced by Google. It may change
	 * in the future and diverges from the normal schema.org spec.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_query_input( $source ) {
		return $source->get_schema_property( 'query-input' );
	}

	/**
	 * Gets the target schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_target( $source ) {
		return $source->get_schema_property( 'target' );
	}

	/**
	 * Gets the url schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_url( $source ) {
		return $source->get_schema_property( 'url' );
	}

}
