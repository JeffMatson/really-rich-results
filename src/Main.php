<?php
/**
 * Contains the main Really Rich Results class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results;

/**
 * Main Really Rich Results class.
 *
 * @package Really_Rich_Results
 */
class Main {

	/**
	 * Stores an instance of this class.
	 *
	 * @var Main|null
	 */
	public static $instance = null;

	/**
	 * The current version of RRR.
	 *
	 * @var string
	 */
	protected static $version = RRR_VERSION;

	/**
	 * The site-wide config used by RRR.
	 *
	 * @var Data_Sources\Site|null
	 */
	private $site_config = null;

	/**
	 * The schema type for the primary page content.
	 *
	 * @var string
	 */
	private $primary_content_type;

	/**
	 * The current page's primary content.
	 *
	 * @var object
	 */
	private $primary_content;

	/**
	 * Stores any posts found. Used for processing schema objects.
	 *
	 * @var array
	 */
	private $found_posts = array();

	/**
	 * Stores built schema objects.
	 *
	 * @var Schema\Thing[]
	 */
	public $schema_objects = array();

	/**
	 * Gets an instance of this class. Typical singleton.
	 *
	 * @return Main
	 */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new Main();
		}

		return self::$instance;
	}

	/**
	 * Actions to run on plugin init.
	 *
	 * @return void
	 */
	public function init() {
		do_action( 'really_rich_results_init' );

		$post_settings   = new Admin\Post_Settings();
		$plugin_settings = new Admin\Plugin_Settings();
		$routes          = new Routes();

		if ( is_admin() ) {
			add_action( 'init', array( new Admin\Settings(), 'init' ) );
		} else {
			add_action( 'init', array( $this, 'init_public' ) );
		}

		add_action( 'init', array( $post_settings, 'init' ) );
		add_action( 'admin_init', array( $plugin_settings, 'init' ) );
		add_action( 'rest_api_init', array( $plugin_settings, 'init' ) );
		add_action( 'rest_api_init', array( $routes, 'register_routes' ) );
	}

	/**
	 * Actions to run when on the frontend.
	 *
	 * @return void
	 */
	public function init_public() {
		add_action( 'wp', array( $this, 'set_primary_content' ) );
		add_action( 'the_post', array( $this, 'collect_queried_posts' ) );
		add_action( 'wp_footer', array( $this, 'output_schema' ), 99 );
	}

	/**
	 * Gets the site-wide values for schema props.
	 *
	 * Checks to see if it's already stored here. If not, sets it and returns
	 * the data source.
	 *
	 * @return Data_Sources\Site
	 */
	public function get_site_config() {
		if ( $this->site_config === null ) {
			$this->site_config = new Data_Sources\Site();
		}

		return $this->site_config;
	}

	/**
	 * Sets the primary content to base schema objects on.
	 *
	 * @return void
	 */
	public function set_primary_content() {
		$this->primary_content      = Factories\Data_Source::create( get_queried_object() );
		$this->primary_content_type = $this->get_primary_content_type();
	}

	/**
	 * Checks if an object is a valid content type.
	 *
	 * Valid content types are expected to be extensions of the Abstract_Content_Type class.
	 * If any other class is passed to this, false will be returned.
	 *
	 * @param object $content_type The content type to check.
	 *
	 * @return bool
	 */
	private function is_valid_content_type( $content_type ) {
		// If it's not an object, don't even bother.
		if ( ! is_object( $content_type ) ) {
			return false;
		}

		// Make sure it's extending the abstract content type.
		return is_subclass_of( $content_type, '\\Really_Rich_Results\\Content_Types\\Abstract_Content_Type' );
	}

	/**
	 * Checks if a data source is registered as the primary content type.
	 *
	 * @param object $data_source The data source object.
	 *
	 * @return bool
	 */
	private function is_primary_content( $data_source ) {
		$is_primary_content = false;

		$primary_content_data = $this->primary_content->get_data();
		$current_content_data = $data_source->get_data();

		if ( Common::is_wp_post( $primary_content_data ) && Common::is_wp_post( $current_content_data ) ) {
			$is_primary_content = $primary_content_data->ID === $current_content_data->ID;
		}

		return apply_filters( 'really_rich_results_is_primary_content', $is_primary_content, $this->primary_content, $data_source );
	}

	/**
	 * Determines the primary content type being viewed.
	 *
	 * TODO: Still hacky but maybe a switch feels better?
	 *
	 * @return string
	 */
	private function get_primary_content_type() {

		switch ( true ) {
			// If we're on the home page.
			case is_home():
				// Check for home page.
				switch ( true ) {
					case is_front_page():
						// Is the home page in blog or page format?
						switch ( get_option( 'show_on_front' ) ) {
							case 'page':
								return 'index';
							default:
								return 'blog_index';
						}
					default:
						return 'blog';
				}
			case is_singular():
				// Check for single or single index.
				switch ( true ) {
					case is_front_page():
						return 'index';
					default:
						return 'single';
				}
			case is_category():
				// Check for category archive.
				return 'archive';
			case is_tax():
				// Check for taxonomy archive.
				return 'archive';
			default:
				return 'unknown';
		}
	}

	/**
	 * Collects all queried posts for later schema processing.
	 *
	 * @param WP_Post $post The post object found.
	 *
	 * @return void
	 */
	public function collect_queried_posts( $post ) {
		if ( Common::is_wp_post( $post ) ) {
			if ( ! array_key_exists( $post->ID, $this->found_posts ) ) {
				$this->found_posts[ $post->ID ] = Factories\Data_Source::create( $post );
			}
		}
	}

	/**
	 * Builds a content type template from a data source.
	 *
	 * If a content type template is passed through the really_rich_results_build_content_type
	 * filter, that template will be used. Otherwise, a standard content type
	 * template will be used.
	 *
	 * @param Data_Sources\Abstract_Data_Source        $data_source  The data source to process.
	 * @param Content_Types\Abstract_Content_Type|null $content_type The detected primary content type.
	 *
	 * @return object A built schema object.
	 */
	public function build_content_type( Data_Sources\Abstract_Data_Source $data_source, $content_type = null ) {
		$custom_builder = apply_filters( 'really_rich_results_build_content_type', null, $data_source, $content_type, $this );

		// Check if a custom builder was passed through the really_rich_results_build_content_type filter.
		if ( $this->is_valid_content_type( $custom_builder ) ) {
			return $custom_builder;
		}

		// Fall back to a standard content type.
		return $this->build_standard_content_type( $data_source, $content_type );
	}

	/**
	 * Builds a standard content type template.
	 *
	 * TODO: This feels dumb. Maybe refactor.
	 *
	 * @param Data_Sources\Abstract_Data_Source $data_source  The data source object.
	 * @param string                            $content_type The content type.
	 *
	 * @return object|null
	 */
	public function build_standard_content_type( $data_source, $content_type ) {
		switch ( $content_type ) {
			case 'single':
				return $this->build_single( $data_source );
			case 'archive':
				return $this->build_archive( $data_source, $this->found_posts );
			case 'blog':
				return $this->build_archive( $data_source, $this->found_posts );
			case 'index':
				return $this->build_single( $data_source );
			case 'blog_index':
				return $this->build_archive( $data_source, $this->found_posts );
			default:
				return null;
		}
	}

	/**
	 * Builds the primary schema object.
	 *
	 * @return object|null
	 */
	public function build_primary_schema_object() {
		$primary_content_type = $this->primary_content_type;
		$primary_content      = $this->primary_content;

		if ( empty( $primary_content ) ) {
			return null;
		}

		$content_type_builder = $this->build_content_type( $primary_content, $primary_content_type );

		$primary_content->set_processed_status( true );

		if ( empty( $content_type_builder ) ) {
			return null;
		}

		return $content_type_builder->build_schema();
	}

	/**
	 * Builds schema objects.
	 *
	 * @return void
	 */
	public function build_schema_objects() {
		$primary_schema_object = $this->build_primary_schema_object();

		foreach ( $this->found_posts as $found_post ) {
			if ( ! $found_post->get_processed_status() ) {
				$this->schema_objects[] = Factories\Schema::create( $found_post );
			}
		}

		if ( ! empty( $primary_schema_object ) ) {
			$this->schema_objects[] = $primary_schema_object;
		}
	}

	/**
	 * Builds a schema object for the Single content type.
	 *
	 * @param object $data_source The data source to build the content type from.
	 *
	 * @return Content_Types\Single
	 */
	public function build_single( $data_source ) {
		$single = new Content_Types\Single();

		// Set the primary content.
		$single->set_primary_content( $data_source );

		$products = $data_source->get_products();
		if ( ! empty( $products ) ) {
			foreach ( $products as $product ) {
				$this->schema_objects[] = Factories\Schema::create( $product, new Schema\Product() );
			}
		}

		// Add all other unprocessed posts as a part of the main schema.
		foreach ( $this->found_posts as $found_post ) {

			// Make sure we're not processing the main schema object as a part of itself.
			if ( $this->is_primary_content( $found_post ) ) {
				$found_post->set_processed_status( true );
			}

			if ( ! $found_post->get_processed_status() ) {
				$schema = Factories\Schema::create( $found_post );
				$single->add_part( $schema->get_schema() );
				$found_post->set_processed_status( true );
			}
		}

		return $single;
	}

	/**
	 * Builds a schema object for the Archive content type.
	 *
	 * @param Data_Sources\Abstract_Data_Source $primary_data_source The primary data source.
	 * @param Data_Sources\Abstract_Data_Source $archive_posts       Array of data sources to use as child elements.
	 *
	 * @return Content_Types\Archive
	 */
	public function build_archive( $primary_data_source, $archive_posts ) {
		$content_type = new Content_Types\Archive();

		// Set the primary content.
		$content_type->set_primary_content( $primary_data_source );

		// Add archive items and part of the main archive.
		foreach ( $archive_posts as $archive_post ) {
			$content_type->add_archive_post( $archive_post );
			$archive_post->set_processed_status( true );
		}

		return $content_type;
	}

	/**
	 * Builds and outputs the schema objects into the WordPress footer.
	 *
	 * @return void
	 */
	public function output_schema() {

		$this->build_schema_objects();

		$schema_objects = apply_filters( 'really_rich_results_pre_output_schema_json', $this->schema_objects );

		foreach ( $schema_objects as $schema_object ) {
			if ( Types::is_schema_object( $schema_object ) && method_exists( $schema_object, 'get_schema' ) ) {
				echo '<script type="application/ld+json">' . wp_json_encode( $schema_object->get_schema() ) . '</script>';
			}
		}

		do_action( 'really_rich_results_add_schema_output' );

		if ( $this->primary_content_type === ( 'index' || 'blog_index' ) ) {
			$this->output_website_schema();
		}
	}

	/**
	 * Outputs the WebSite schema object JSON-LD.
	 *
	 * @return void
	 */
	private function output_website_schema() {
		$website = Factories\Schema::create( $this->get_site_config(), new Schema\WebSite() );
		echo '<script type="application/ld+json">' . wp_json_encode( $website->get_schema() ) . '</script>';
	}

}
