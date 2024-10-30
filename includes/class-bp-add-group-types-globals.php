<?php
/**
 * The file that defines the global variable of the plugin
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Add_Group_Types
 * @subpackage Bp_Add_Group_Types/includes
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */

if ( ! class_exists( 'Bp_Add_Group_Types_Globals' ) ) :
	/**
	 * The file that defines the global variable of the plugin
	 *
	 * @link       https://wbcomdesigns.com/
	 * @since      1.0.0
	 *
	 * @package    Bp_Add_Group_Types
	 * @subpackage Bp_Add_Group_Types/includes
	 * @author     Wbcom Designs <admin@wbcomdesigns.com>
	 */
	class Bp_Add_Group_Types_Globals {
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		/**
		 * Enable the group type search functionality on front-end.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $group_type_search_enabled
		 */
		public $group_type_search_enabled;

		/**
		 * The change in the search template
		 *
		 * @since    1.0.0
		 * @access   public
		 * @var      string    $group_type_search_template
		 */
		public $group_type_search_template;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {

			$this->plugin_name = 'bp-add-group-types';
			$this->version     = '1.0.0';
			$this->setup_plugin_global();
		}

		/**
		 * Include the following files that make up the plugin:
		 *
		 * - Bp_Add_Group_Types_Globals.
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function setup_plugin_global() {
			global $bp_grp_types;

			$bpgt_settings = get_option( 'bpgt_general_settings' );

			$this->group_type_search_enabled = 'off';
			if ( isset( $bpgt_settings['group_type_search_enabled'] ) ) {
				$this->group_type_search_enabled = $bpgt_settings['group_type_search_enabled'];
			}

			$bpgt_search_settings             = get_option( 'bpgt_search_settings' );
			$this->group_type_search_template = 'both';
			if ( isset( $bpgt_search_settings['group_type_search_template'] ) ) {
				$this->group_type_search_template = $bpgt_search_settings['group_type_search_template'];
			}
		}
	}

endif;
