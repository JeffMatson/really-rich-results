<?php
/**
 * Contains the main Settings class.
 *
 * @package Really_Rich_Results
 */

namespace Really_Rich_Results\Admin;

use WP_Error;

/**
 * Main Really Rich Results settings class.
 *
 * @package Really_Rich_Results\Admin
 */
class Settings {

	/**
	 * Actions to run on the `init` action hook.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Gets asset files (js, css, deps).
	 *
	 * @param string $slug The file slug.
	 * @param string $type The asset type. Valid values are css or js.
	 *
	 * @return array
	 */
	private function get_asset( $slug, $type ) {
		if ( $type === 'js' ) {
			$asset        = require_once RRR_PLUGIN_DIR_PATH . 'assets/dist/' . $slug . '.asset.php';
			$asset['url'] = RRR_PLUGIN_DIR_URL . 'assets/dist/' . $slug . '.js';
		} elseif ( $type === 'css' ) {
			$asset = array(
				'url' => RRR_PLUGIN_DIR_URL . 'assets/dist/' . $slug . '.css',
			);
		} else {
			$asset = new WP_Error( 'really_rich_results_bad_asset_type', __( 'Tried to get an asset of an unknown type', 'really-rich-results' ), $type );
		}

		return $asset;
	}

	/**
	 * Initializes the menu page in wp-admin.
	 *
	 * @return void
	 */
	public function admin_menu() {
		add_submenu_page(
			'tools.php',
			__( 'Really Rich Results', 'really-rich-results' ),
			'R&sup3; Schema',
			'manage_options',
			'really_rich_results',
			array( $this, 'render_plugin_settings_page' )
		);
	}

	/**
	 * Enqueues scripts required for settings pages.
	 *
	 * @param string $hook The path returned by the hook.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook ) {
		switch ( $hook ) {
			case 'tools_page_really_rich_results':
				$this->enqueue_plugin_settings_scripts();
				break;
			case 'post.php':
				$this->enqueue_post_editor_scripts();
				break;
		}
	}

	/**
	 * Enqueues scripts and styles for the Really Rich Results plugin settings page.
	 *
	 * @return void
	 */
	private function enqueue_plugin_settings_scripts() {
		$plugin_settings_js  = $this->get_asset( 'plugin-settings', 'js' );
		$plugin_settings_css = $this->get_asset( 'plugin-settings', 'css' );

		// Register plugin settings scripts.
		wp_register_script(
			'really_rich_results_plugin_settings',
			$plugin_settings_js['url'],
			$plugin_settings_js['dependencies'],
			$plugin_settings_js['version'],
			true
		);

		// Enqueue plugin settings scripts and styles.
		wp_enqueue_script( 'really_rich_results_plugin_settings' );
		wp_enqueue_style( 'really-rich-results-plugin-settings', $plugin_settings_css['url'], array( 'wp-components' ), RRR_VERSION );
	}

	/**
	 * Enqueues scripts and styles used by RRR in the post editor.
	 *
	 * @return void
	 */
	private function enqueue_post_editor_scripts() {
		$post_settings_js  = $this->get_asset( 'post-settings', 'js' );
		$post_settings_css = $this->get_asset( 'post-settings', 'css' );

		// Register post settings scripts.
		wp_register_script(
			'really_rich_results_post_settings',
			$post_settings_js['url'],
			$post_settings_js['dependencies'],
			$post_settings_js['version'],
			true
		);

		// Enqueue post settings scripts and styles.
		wp_enqueue_script( 'really_rich_results_post_settings' );
		wp_enqueue_style( 'really-rich-results-post-settings', $post_settings_css['url'], array( 'wp-components' ), RRR_VERSION );
	}

	/**
	 * Renders the div for the settings page.
	 *
	 * @return void
	 */
	public function render_plugin_settings_page() {
		echo '<div id="root-really-rich-results-settings"></div>';
	}
}
