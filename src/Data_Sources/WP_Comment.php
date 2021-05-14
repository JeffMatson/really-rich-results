<?php
/**
 * Contains the WP_Comment data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

use Really_Rich_Results\Common;
use Really_Rich_Results\Main;

/**
 * Class of handling WP_Comment objects.
 *
 * @package Really_Rich_Results\Data_Sources
 */
class WP_Comment extends Abstract_Data_Source {

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
		switch ( $property ) {
			case '@id':
				return $this->get_id();
			case 'author':
				return $this->get_author();
			case 'dateCreated':
				return $this->get_date();
			case 'text':
				return $this->get_content();
			case 'url':
				return $this->get_permalink();
			default:
				return null;
		}
	}

	/**
	 * Gets the comment ID.
	 *
	 * @return string
	 */
	private function get_id() {
		return $this->data->comment_ID;
	}

	/**
	 * Gets the comment's permalink.
	 *
	 * @return string
	 */
	private function get_permalink() {
		return get_comment_link( $this->data );
	}

	/**
	 * Gets the author, if available.
	 *
	 * @return object
	 */
	private function get_author() {
		if ( intval( $this->data->user_id ) !== 0 ) {
			$wp_user = get_user_by( 'ID', $this->data->user_id );
			return \Really_Rich_Results\Factories\Data_Source::create( $wp_user );
		}

		return $this->build_generic_author();
	}

	/**
	 * Builds a generic author if a user doesn't exist.
	 *
	 * @return Generic
	 */
	private function build_generic_author() {
		$author = new Generic( 'Person' );

		$author->set_schema_property( 'name', $this->data->comment_author );
		$author->set_schema_property( 'url', $this->data->comment_author_url );
		$author->set_schema_property( 'image', $this->get_avatar_url() );

		return $author;
	}

	/**
	 * Gets the avatar URL.
	 *
	 * @return null|string
	 */
	private function get_avatar_url() {
		$avatar_url = get_avatar_url( $this->data->comment_author_email );

		if ( ! $avatar_url ) {
			return null;
		}

		return $avatar_url;
	}

	/**
	 * Gets the comment date.
	 *
	 * @return string
	 */
	private function get_date() {
		return Common::convert_date( $this->data->comment_date_gmt );
	}

	/**
	 * Gets the comment content.
	 *
	 * @return string
	 */
	private function get_content() {
		return esc_html( $this->data->comment_content );
	}
}
