<?php
/**
 * Contains the WP_Post data source class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Data_Sources;

use Really_Rich_Results\Common;
use Really_Rich_Results\Factories\Data_Source;

/**
 * Class to process WP_Post objects.
 */
class WP_Post extends Abstract_Data_Source {

	/**
	 * Gets the post being used.
	 *
	 * @return \WP_Post
	 */
	public function get_post() {
		return $this->data;
	}

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
		$result = apply_filters( 'really_rich_results_wp_post_get_schema_property', false, $property, $this );

		if ( $result !== false ) {
			return $result;
		}

		// Check storage first.
		if ( $this->has_stored_schema_property( $property ) ) {
			return $this->get_stored_schema_property( $property );
		}

		// Map schema property names to methods.
		switch ( $property ) {
			case '@id':
				$result = $this->get_id();
				break;
			case 'abstract':
				$result = $this->get_excerpt();
				break;
			case 'articleBody':
				$result = $this->get_content();
				break;
			case 'articleSection':
				$result = $this->get_category_names();
				break;
			case 'author':
				$result = $this->get_author();
				break;
			case 'comment':
				$result = $this->get_comments();
				break;
			case 'commentCount':
				$result = $this->get_comment_count();
				break;
			case 'dateCreated':
				$result = $this->get_date_published();
				break;
			case 'dateModified':
				$result = $this->get_date_modified();
				break;
			case 'datePublished':
				$result = $this->get_date_published();
				break;
			case 'description':
				$result = $this->get_excerpt();
				break;
			case 'discussionUrl':
				$result = $this->get_comments_link();
				break;
			case 'headline':
				$result = $this->get_title();
				break;
			case 'image':
				$result = $this->get_thumbnail_url( 'full' );
				break;
			case 'itemReviewed':
				$result = $this->get_site_organization();
				break;
			case 'mainEntityOfPage':
				$result = $this->get_permalink();
				break;
			case 'name':
				$result = $this->get_title();
				break;
			case 'publisher':
				$result = $this->get_site_organization();
				break;
			case 'text':
				$result = $this->get_content( true );
				break;
			case 'thumbnailUrl':
				$result = $this->get_thumbnail_url();
				break;
			case 'url':
				$result = $this->get_permalink();
				break;
			case 'wordCount':
				$result = $this->get_word_count();
				break;
			default:
				$result = null;
				break;
		}

		$this->store_schema_property( $property, $result );

		return $result;
	}

	/**
	 * Gets the post ID.
	 *
	 * @return int
	 */
	private function get_id() {
		return $this->data->ID;
	}

	/**
	 * Gets the post title.
	 *
	 * @return string
	 */
	private function get_title() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		return apply_filters( 'the_title', $this->data->post_title );
	}

	/**
	 * Gets the post's author.
	 *
	 * @return WP_User|null
	 */
	private function get_author() {
		if ( $this->show_author() ) {
			$author = get_user_by( 'ID', $this->data->post_author );

			if ( ! $author ) {
				return null;
			}

			return Data_Source::create( $author );
		}

		return null;
	}

	/**
	 * Gets the post content.
	 *
	 * @param bool $strip_tags If tags should be stripped.
	 *
	 * @return string
	 */
	private function get_content( $strip_tags = false ) {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
		$content = apply_filters( 'the_content', $this->data->post_content );

		if ( $strip_tags ) {
			$content = wp_strip_all_tags( $content );
		}

		return $content;
	}

	/**
	 * Gets the content's word count.
	 *
	 * @return string
	 */
	private function get_word_count() {
		// Get the post content, sans tags, and do a word count.
		$word_count = str_word_count( $this->get_content( true ) );

		// Exclude the property if the word count is zero.
		if ( empty( $word_count ) ) {
			return null;
		}

		return $word_count;
	}

	/**
	 * Gets the post excerpt.
	 *
	 * @return string
	 */
	private function get_excerpt() {
		return get_the_excerpt( $this->data );
	}

	/**
	 * Gets the post's categories.
	 *
	 * @return array
	 */
	private function get_categories() {
		return get_the_category( $this->data->ID );
	}

	/**
	 * Gets the post's primary category.
	 *
	 * @return string
	 */
	private function get_primary_category() {
		$categories = $this->get_categories();

		if ( empty( $categories ) ) {
			return null;
		}

		return $categories[0];
	}

	/**
	 * Gets the categories and returns only names.
	 *
	 * @return array
	 */
	private function get_category_names() {
		$category_names = array();
		$categories     = $this->get_categories();

		foreach ( $categories as $category ) {
			$category_names[] = $category->name;
		}

		return $category_names;
	}

	/**
	 * Gets the post's comments.
	 *
	 * @param array $comment_params Additional params to pass.
	 *
	 * @return array
	 */
	private function get_comments( $comment_params = array() ) {
		$comments_processed = array();

		$defaults = array(
			'post_id' => $this->data->ID,
			'status'  => 'approve',
			'type'    => 'comment',
		);

		$comments_raw = get_comments( array_merge( $defaults, $comment_params ) );

		foreach ( $comments_raw as $comment ) {
			$comments_processed[] = Data_Source::create( $comment );
		}

		return $comments_processed;
	}

	/**
	 * Gets the link to the post's comments.
	 *
	 * Returns null if there are no comments.
	 *
	 * @return string|null
	 */
	private function get_comments_link() {
		if ( empty( $this->get_schema_property( 'commentCount' ) ) ) {
			return null;
		}

		return get_comments_link( $this->data->ID );
	}

	/**
	 * Gets the comment count.
	 *
	 * Note: This differs from the normal comment_count property on the core
	 * WP_Post object. It only counts comments and doesn't include pinbacks or
	 * postbacks.
	 *
	 * @return int|null
	 */
	private function get_comment_count() {
		$comment_count = count( $this->get_schema_property( 'comment' ) );

		if ( empty( $comment_count ) ) {
			return null;
		}

		return $comment_count;
	}

	/**
	 * Gets the post's published date.
	 *
	 * @return string
	 */
	private function get_date_published() {
		return Common::convert_date( $this->data->post_date_gmt );
	}

	/**
	 * Gets the post's last modified date.
	 *
	 * @return string
	 */
	private function get_date_modified() {
		return Common::convert_date( $this->data->post_modified_gmt );
	}

	/**
	 * Gets the post's featured image URL.
	 *
	 * @return string
	 */
	private function get_thumbnail_url() {
		$thumbnail_url = get_the_post_thumbnail_url( $this->data );

		if ( ! $thumbnail_url ) {
			return null;
		}

		return $thumbnail_url;
	}

	/**
	 * Gets the post's permalink.
	 *
	 * @return string
	 */
	private function get_permalink() {
		return get_permalink( $this->data );
	}

	/**
	 * Gets the organization from the global site settings.
	 *
	 * @return \Really_Rich_Results\Data_Sources\Generic
	 */
	private function get_site_organization() {
		return $this->get_site_config()->get_schema_property( 'organization' );
	}

	/**
	 * Gets the schema type to use with this object.
	 *
	 * @return string
	 */
	public function get_schema_type() {
		if ( ! empty( $this->get_post_setting_schema_type() ) ) {
			return $this->get_post_setting_schema_type();
		} elseif ( ! empty( $this->get_post_type_setting_schema_type() ) ) {
			return $this->get_post_type_setting_schema_type();
		} else {
			return $this->get_post_default_schema_type();
		}
	}

	/**
	 * Gets the schema type, as defined in the post meta.
	 *
	 * @return string
	 */
	public function get_post_setting_schema_type() {
		$post_schema_type = get_post_meta( $this->data->ID, 'really_rich_results_schema_type', true );

		if ( empty( $post_schema_type ) ) {
			return null;
		}

		return $post_schema_type;
	}

	/**
	 * Gets the schema type for the post type.
	 *
	 * TODO: Should use wp_parse_args here to avoid empty option values.
	 *
	 * @return string
	 */
	public function get_post_type_setting_schema_type() {
		$post_type_slug    = $this->data->post_type;
		$post_type_options = get_option( 'really_rich_results_post_type_' . $post_type_slug, array( 'schema_type' => null ) );

		return $post_type_options['schema_type'];
	}

	/**
	 * Gets a default schema type for posts.
	 *
	 * @return string
	 */
	public function get_post_default_schema_type() {
		if ( $this->data->post_type === 'post' ) {
			return 'Article';
		}

		return 'WebPage';
	}

	/**
	 * Determines if the author schema should be output.
	 *
	 * TODO: Rework this at some point to get rid of the badly written logic.
	 *
	 * @return bool
	 */
	public function show_author() {
		if ( $this->author_required() ) {
			return true;
		}

		if ( $this->get_post_setting_show_author() === 'show' ) {
			return true;
		}

		if ( $this->get_post_setting_show_author() === 'hide' ) {
			return false;
		}

		if ( $this->get_post_type_setting_show_author() === 'show' ) {
			return true;
		}

		if ( $this->get_post_type_setting_show_author() === 'hide' ) {
			return false;
		}

		return $this->get_schema_type_default_show_author();
	}

	/**
	 * Checks if the author is a required property for the schema type.
	 *
	 * @return bool
	 */
	public function author_required() {
		return $this->get_schema_type() === 'Article';
	}

	/**
	 * Gets the post settings for showing the author schema.
	 *
	 * @return string
	 */
	public function get_post_setting_show_author() {
		return get_post_meta( $this->data->ID, 'really_rich_results_schema_type', 'default' );
	}

	/**
	 * Gets the post type settings for showing the author.
	 *
	 * @return string
	 */
	public function get_post_type_setting_show_author() {
		$post_type_slug    = $this->data->post_type;
		$post_type_options = get_option( 'really_rich_results_post_type_' . $post_type_slug, array( 'show_author' => 'default' ) );

		return $post_type_options['show_author'];
	}

	/**
	 * Determines the defaults behavior for showing authors on the schema type.
	 *
	 * @return bool
	 */
	public function get_schema_type_default_show_author() {
		return $this->get_schema_type() === 'Article';
	}

	/**
	 * Gets products attached to post meta.
	 *
	 * @return array Array of product data sources.
	 */
	public function get_products() {
		$products     = array();
		$product_meta = get_post_meta( $this->data->ID, 'really_rich_results_post_product_schema', true );

		if ( ! empty( $product_meta ) ) {

			foreach ( $product_meta as $product_meta_item ) {
				$product_data_source = new Really_Rich_Results_Product();
				$product_data_source->merge_product_properties( $product_meta_item );

				$products[] = $product_data_source;
			}
		}

		return $products;
	}

}
