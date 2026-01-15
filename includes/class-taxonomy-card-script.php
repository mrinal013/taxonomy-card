<?php
/**
 * @author      Mrinal Haque
 * @copyright   (c) 2026, WP Dev Agent
 * @link        https://www.wpdevagent.com
 * @package     TaxonomyCard
 * @subpackage  Script
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( ! class_exists( 'Taxonomy_Card_Script' ) ) {
	/**
	 * Taxonomy_Card_Script class.
	 *
	 * @since 1.0.0
	 */
	class Taxonomy_Card_Script {
		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'stylesheets' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		}

		/**
		 * Frontend stylesheets
		 *
		 * @since 1.0.0
		 */
		public function stylesheets() {
		}

		/**
		 * Frontend scripts
		 *
		 * @since 1.0.0
		 */
		public function scripts() {
		}

		/**
		 * Dashboard scripts
		 *
		 * @param string $hook unique name of the page.
		 *
		 * @since 1.0.0
		 */
		public function admin_scripts( $hook ) {

			$site_url        = get_bloginfo( 'url' );
			$keys            = get_option( 'ww_settings_rest_api', array() );
			$consumer_key    = $keys['consumer_key'] ?? '';
			$consumer_secret = $keys['consumer_secret'] ?? '';

			wp_localize_script(
				'wpdevagent-taxonomy-card-editor-script',
				'admin_obj',
				array(
					'site_url'        => $site_url,
					'consumer_key'    => $consumer_key,
					'consumer_secret' => $consumer_secret,
				)
			);
		}
	}
}
