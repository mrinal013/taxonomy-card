<?php
/**
 * @author      Mrinal Haque
 * @copyright   (c) 2026, WP Dev Agent
 * @link        https://www.wpdevagent.com
 * @package     TaxonomyCard
 * @subpackage  Settings
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Taxonomy_Card_Settings' ) ) {
	/**
	 * Settings class
	 *
	 * @since 1.0.0
	 */
	class Taxonomy_Card_Settings {

		private string $admin_url;
		private mixed $options_taxonomy_card;
		private string $option_group_rest_api;
		private string $option_page_taxonomy_card;

		public function __construct() {

			$this->admin_url                 = 'admin.php?page=taxonomy-card';
			$this->option_group_rest_api     = 'taxonomy_card_settings_rest_api';
			$this->option_page_taxonomy_card = 'taxonomy-card';

			$this->options_taxonomy_card = get_option( $this->option_group_rest_api );

			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'options_init' ) );
		}

		public function add_plugin_page(): void {
			// This page will be under "WooCommerce"
			add_menu_page(
				__( 'Taxonomy card Settings', 'taxonomy-card' ),
				__( 'Taxonomy card', 'taxonomy-card' ),
				'manage_options',
				'taxonomy-card',
				array( $this, 'create_admin_page' ),
				'dashicons-category',
				90
			);
		}


		public function create_admin_page(): void {

			$active_page = sanitize_text_field( ( $_GET['tab'] ?? 'restapi' ) ); // set default tab ?>

			<div class="wrap">
				<h1><?php esc_html_e( 'Taxonomy Card Settings', 'taxonomy-card' ); ?></h1>
				<?php settings_errors(); ?>

				<form method="post" action="options.php">
				<?php

					settings_fields( $this->option_group_rest_api );
					do_settings_sections( $this->option_page_taxonomy_card );

					submit_button();
				?>
				</form>

			</div>
			<?php
		}



		/**
		 * Initialize Options on Settings Page
		 */
		public function options_init(): void {

			$this->options_rest_api();

			register_setting(
				$this->option_group_rest_api, // Option group
				$this->option_group_rest_api, // Option name
				array( $this, 'sanitize' ) // Sanitize
			);
		}

		/**
		 * Page Finance, Section Settings
		 */
		public function options_rest_api(): void {
			$section = 'rest_api_settings';

			add_settings_section(
				$section, // ID
				esc_html__( 'Rest API Settings', 'taxonomy-card' ),
				'', // Callback
				$this->option_page_taxonomy_card // Page
			);

			$id = 'consumer_key';

			add_settings_field(
				$id,
				esc_html__( 'Consumer key', 'taxonomy-card' ),
				array( $this, 'option_input_text_cb' ), // general call back for input text.
				$this->option_page_taxonomy_card,
				$section,
				array(
					'option_group' => $this->option_group_rest_api,
					'id'           => $id,
					'value'        => $this->options_taxonomy_card[ $id ] ?? '',
					'placeholder'  => '',
					'width'        => '300px',
				)
			);

			$id = 'consumer_secret';
			add_settings_field(
				$id,
				esc_html__( 'Consumer secret', 'taxonomy-card' ),
				array( $this, 'option_input_text_cb' ), // general call back for input text.
				$this->option_page_taxonomy_card,
				$section,
				array(
					'option_group' => $this->option_group_rest_api,
					'id'           => $id,
					'value'        => $this->options_taxonomy_card[ $id ] ?? '',
					'placeholder'  => '',
					'width'        => '300px',
				)
			);
		}



		/**
		 * General Input Field Text
		 *
		 * @param array $args
		 */
		public function option_input_text_cb( array $args ): void {

			$option_group = ( isset( $args['option_group'] ) ) ? $args['option_group'] : '';
			$id           = ( isset( $args['id'] ) ) ? $args['id'] : '';
			$value        = ( isset( $args['value'] ) ) ? $args['value'] : '';
			$suffix       = ( isset( $args['suffix'] ) ) ? ' ' . $args['suffix'] : '';
			$placeholder  = ( isset( $args['placeholder'] ) ) ? $args['placeholder'] : '';
			$description  = ( isset( $args['description'] ) ) ? $args['description'] : '';
			$password     = ( isset( $args['password'] ) ) ? $args['password'] : false;
			$width        = ( isset( $args['width'] ) ) ? $args['width'] : '';

			$type = 'text';
			if ( $password ) {
				$type = 'password';
			}

			printf(
				'<input type="%6$s" id="%1$s" name="%3$s[%1$s]" value="%2$s" placeholder="%4$s" style="width: %5$s"/>%7$s',
				$id,
				$value,
				$option_group,
				$placeholder,
				$width,
				$type,
				$suffix
			);

			if ( ! empty( $description ) ) {
				echo '<p class="description">' . $description . '</p>';
			}
		}



		/**
		 * Sanitizes a string from user input
		 * Checks for invalid UTF-8, Converts single < characters to entities, Strips all tags, Removes line breaks, tabs, and extra whitespace, Strips octets
		 *
		 * @param array|null $input
		 *
		 * @return array
		 */
		public function sanitize( ?array $input ): array {

			$new_input = array();

			foreach ( $input as $key => $value ) {

				if ( 'ftp-directory' == $key ) {

					// add leading slash
					$new_input[ $key ] = '/' . ( ltrim( sanitize_text_field( $value ), '/\\' ) );

				} else {

						$new_input[ $key ] = self::sanitize_text_or_array_field( $value );

				}
			}

			return $new_input;
		}

		/**
		 * Recursive sanitation for text or array
		 *
		 * @param $array_or_string (array|string)
		 *
		 * @since  1.0.0
		 */
		private function sanitize_text_or_array_field( $array_or_string ) {

			if ( is_string( $array_or_string ) ) {

				$array_or_string = sanitize_text_field( $array_or_string );

			} elseif ( is_array( $array_or_string ) ) {
				foreach ( $array_or_string as $key => &$value ) {
					if ( is_array( $value ) ) {
						$value = self::sanitize_text_or_array_field( $value );
					} else {
						$value = sanitize_text_field( $value );
					}
				}
			}

			return $array_or_string;
		}
	}

}
