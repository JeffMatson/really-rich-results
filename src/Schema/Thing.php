<?php
/**
 * Builds properties for the Thing schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

use Really_Rich_Results\Factories\Schema;

/**
 * Schema class for the Thing schema type.
 *
 * This is the base schema type that all other types depend on, per the schema.org
 * spec. Therefore all other schema classes should extend this base class.
 *
 * @see https://schema.org/Thing
 */
class Thing {

	/**
	 * Stores the schema properties for the current object type.
	 *
	 * @var array
	 */
	protected $schema = array();

	/**
	 * Standard schema context string.
	 *
	 * @var array
	 */
	protected $context = 'https://schema.org';

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
		$this->set_schema_prop( '@context', $this->get_context() );
		$this->set_schema_prop( '@type', $this->get_type() );
		$this->set_schema_prop( '@id', $this->get_id( $source ) );
		$this->set_schema_prop( 'additionalType', $this->get_additional_type( $source ) );
		$this->set_schema_prop( 'alternateName', $this->get_alternate_name( $source ) );
		$this->set_schema_prop( 'description', $this->get_description( $source ) );
		$this->set_schema_prop( 'disambiguatingDescription', $this->get_disambiguating_description( $source ) );
		$this->set_schema_prop( 'identifier', $this->get_identifier( $source ) );
		$this->set_schema_prop( 'image', $this->get_image( $source ) );
		$this->set_schema_prop( 'mainEntityOfPage', $this->get_main_entity_of_page( $source ) );
		$this->set_schema_prop( 'name', $this->get_name( $source ) );
		$this->set_schema_prop( 'sameAs', $this->get_same_as( $source ) );
		$this->set_schema_prop( 'subjectOf', $this->get_subject_of( $source ) );
		$this->set_schema_prop( 'url', $this->get_url( $source ) );
		$this->set_schema_prop( 'potentialAction', $this->get_potential_action( $source ) );

		do_action( 'really_rich_results_set_schema_props', $this, $source );
	}

	/**
	 * Sets the context on the source if not already defined.
	 *
	 * @param object $source  The data source object.
	 * @param string $context The context string.
	 *
	 * @return void
	 */
	protected function set_context_if_empty( $source, $context ) {
		if ( empty( $source->get_context() ) ) {
			$source->set_context( $context );
		}
	}

	/**
	 * Gets the @id schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_id( $source ) {
		if ( ! empty( $source->get_schema_property( '@id' ) ) ) {
			return $this->schema_url_base() . $source->get_schema_property( '@id' );
		}

		return null;
	}

	/**
	 * Gets the URL base for building the schema ID.
	 *
	 * @see Thing::get_id()
	 *
	 * @return string
	 */
	public function schema_url_base() {
		return esc_url( trailingslashit( get_site_url() ) . 'Schema/' . $this->get_type() . '/' );
	}

	/**
	 * Getter for the schema array.
	 *
	 * @return array
	 */
	public function get_schema() {
		return $this->schema;
	}

	/**
	 * Gets schema properties.
	 *
	 * @param string $output The output type.
	 *
	 * @return string|false|array
	 */
	public function get_schema_properties( $output = 'array' ) {
		if ( $output === 'json' ) {
			return wp_json_encode( $this->schema );
		}

		return $this->schema;
	}

	/**
	 * Gets the schema and converts it to JSON.
	 *
	 * @return string
	 */
	public function get_json() {
		return wp_json_encode( $this->schema );
	}

	/**
	 * Gets the schema type. Based on the class name.
	 *
	 * @return string
	 */
	public function get_type() {
		$reflection = new \ReflectionClass( $this );
		return $reflection->getShortName();
	}

	/**
	 * Gets the schema context string.
	 *
	 * @return string
	 */
	public function get_context() {
		return $this->context;
	}

	/**
	 * Gets the additionalType schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_additional_type( $source ) {
		return $source->get_schema_property( 'additionalType' );
	}

	/**
	 * Gets the alternateName schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_alternate_name( $source ) {
		return $source->get_schema_property( 'alternateName' );
	}

	/**
	 * Gets the description schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_description( $source ) {
		return $source->get_schema_property( 'description' );
	}

	/**
	 * Gets the disambiguatingDescription schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_disambiguating_description( $source ) {
		return $source->get_schema_property( 'disambiguatingDescription' );
	}

	/**
	 * Gets the identifier schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_identifier( $source ) {
		return $source->get_schema_property( 'identifier' );
	}

	/**
	 * Gets the image schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string|ImageObject
	 */
	public function get_image( $source ) {
		$image = $source->get_schema_property( 'image' );

		if ( is_object( $image ) ) {
			$schema = \Really_Rich_Results\Factories\Schema::create( $image, new ImageObject() );
			return $schema->get_schema();
		}

		return $image;
	}

	/**
	 * Gets the mainEntityOfPage schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_main_entity_of_page( $source ) {
		return $source->get_schema_property( 'mainEntityOfPage' );
	}

	/**
	 * Gets the name schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_name( $source ) {
		return $source->get_schema_property( 'name' );
	}

	/**
	 * Gets the potentialAction schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string|SearchAction
	 */
	public function get_potential_action( $source ) {
		$potential_action = $source->get_schema_property( 'potentialAction' );

		if ( is_object( $potential_action ) ) {
			$schema = Schema::create( $potential_action, new SearchAction() );
			return $schema->get_schema();
		}

		return null;
	}

	/**
	 * Gets the sameAs schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_same_as( $source ) {
		return $source->get_schema_property( 'sameAs' );
	}

	/**
	 * Gets the subjectOf schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_subject_of( $source ) {
		return $source->get_schema_property( 'subjectOf' );
	}

	/**
	 * Gets the url schema property from the data source.
	 *
	 * @param object $source The data source object.
	 *
	 * @return string
	 */
	public function get_url( $source ) {
		return $source->get_schema_property( 'url' );
	}

	/**
	 * Sets a schema prop on the current schema object.
	 *
	 * @param string $prop  The property to get.
	 * @param mixed  $value The value to set.
	 *
	 * @return void
	 */
	public function set_schema_prop( $prop, $value = null ) {
		if ( ! empty( $value ) || $value === 0 ) {

			if ( is_object( $value ) ) {
				$this->schema[ $prop ] = $value->get_schema();
			} else {
				$this->schema[ $prop ] = $value;
			}
		}
	}

	/**
	 * Deletes a schema prop from the current schema object.
	 *
	 * @param string $prop The property to delete.
	 *
	 * @return void
	 */
	public function delete_schema_prop( $prop ) {
		if ( array_key_exists( $prop, $this->schema ) ) {
			unset( $this->schema[ $prop ] );
		}
	}

}
