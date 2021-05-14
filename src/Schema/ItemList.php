<?php
/**
 * Builds properties for the ItemList schema object.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Schema;

/**
 * Schema class for the ItemList schema type.
 *
 * @see https://schema.org/ItemList
 */
class ItemList extends Thing {

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
		parent::set_schema_props( $source );

		$this->set_schema_prop( 'itemListElement', $this->get_item_list_element( $source ) );
		$this->set_schema_prop( 'itemListOrder', $this->get_item_list_order( $source ) );
		$this->set_schema_prop( 'numberOfItems', $this->get_number_of_items( $source ) );
	}

	/**
	 * Gets the itemListElement schema property that contains the list items.
	 *
	 * @param object $source The data source.
	 *
	 * @return array
	 */
	public function get_item_list_element( $source ) {
		$item_list = array();

		// Loop through the data source's list items.
		foreach ( $source->get_schema_property( 'listItems' ) as $list_item ) {

			// Check if the items have properties.
			if ( is_array( $list_item ) ) {

				// Set up the list item schema.
				$schema = new ListItem();
				$schema->set_schema_prop( '@type', 'ListItem' );

				// Loop through properties and assign them to the schema.
				foreach ( $list_item as $prop_name => $prop_value ) {
					$schema->set_schema_prop( $prop_name, $prop_value );
				}

				// Get the schema markup and add it to the list.
				$item_list[] = $schema->get_schema();
			} elseif ( is_object( $list_item ) ) {
				$item_list[] = $list_item->get_schema();
			} else {
				$item_list[] = $list_item;
			}
		}

		return $item_list;
	}

	/**
	 * Gets the itemListOrder schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return string
	 */
	public function get_item_list_order( $source ) {
		return $source->get_schema_property( 'itemListOrder' );
	}

	/**
	 * Gets the numberOfItems schema property from the data source.
	 *
	 * @param object $source The data source.
	 *
	 * @return int
	 */
	public function get_number_of_items( $source ) {
		return $source->get_schema_property( 'numberOfItems' );
	}
}
