<?php
/**
 * Contains the List_Array data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

use Really_Rich_Results\Common;

/**
 * The List_Array class.
 *
 * @package Really_Rich_Results\Data_Sources
 */
class List_Array extends Abstract_Data_Source {

	/**
	 * Holds list items.
	 *
	 * @var array
	 */
	protected $list_items = array();

	/**
	 * Gets a schema property.
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed
	 */
	public function get_schema_property( $property ) {
		switch ( $property ) {
			case 'listItems':
				return $this->get_list_items();
			case 'itemListOrder':
				return $this->get_order();
			case 'numberOfItems':
				return $this->get_count();
			default:
				return null;
		}
	}

	/**
	 * Adds a list item.
	 *
	 * @param mixed $list_item The list item.
	 *
	 * @return void
	 */
	public function add_list_item( $list_item ) {
		$this->list_items[] = $list_item;
	}

	/**
	 * Gets a single list item.
	 *
	 * @param mixed $key The list item key to get.
	 *
	 * @return mixed
	 */
	public function get_single_list_item( $key ) {
		if ( array_key_exists( $key, $this->list_items ) ) {
			return $this->list_items[ $key ];
		}

		return null;
	}

	/**
	 * Gets all list items.
	 *
	 * @return array
	 */
	public function get_list_items() {
		return $this->list_items;
	}

	/**
	 * Gets the list item count.
	 *
	 * @return int|null
	 */
	protected function get_count() {
		return Common::int_or_null( count( $this->list_items ) );
	}

	/**
	 * Gets the order.
	 * Currently static.
	 *
	 * @return string
	 */
	private function get_order() {
		return 'Descending';
	}
}
