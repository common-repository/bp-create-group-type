<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Bp_Add_Group_Types
 * @subpackage Bp_Add_Group_Types/includes
 */

if ( ! class_exists( 'Bp_Add_Group_Types' ) ) :
	/**
	 * The core plugin class.
	 *
	 * This is used to define internationalization, admin-specific hooks, and
	 * public-facing site hooks.
	 *
	 * Also maintains the unique identifier of this plugin as well as the current
	 * version of the plugin.
	 *
	 * @since      1.0.0
	 * @package    Bp_Add_Group_Types
	 * @subpackage Bp_Add_Group_Types/includes
	 * @author     Wbcom Designs <admin@wbcomdesigns.com>
	 */
	class Bp_Add_Group_Types {


		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Bp_Add_Group_Types_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

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
			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();
			$this->define_globals();
			$this->define_public_hooks();
		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Bp_Add_Group_Types_Loader. Orchestrates the hooks of the plugin.
		 * - Bp_Add_Group_Types_I18n. Defines internationalization functionality.
		 * - Bp_Add_Group_Types_Admin. Defines all hooks for the admin area.
		 * - Bp_Add_Group_Types_Public. Defines all hooks for the public side of the site.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-add-group-types-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-add-group-types-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bp-add-group-types-admin.php';

			/**
			 * The class responsible for defining the global variable of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bp-add-group-types-globals.php';

			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/wbcom/wbcom-admin-settings.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bp-add-group-types-public.php';

			/**
			 * The class responsible for admin review notice that apper after 7 days.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bp-group-type-admin-feedback.php';

			$this->loader = new Bp_Add_Group_Types_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Bp_Add_Group_Types_I18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new Bp_Add_Group_Types_I18n();

			$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {
			$plugin_admin = new Bp_Add_Group_Types_Admin( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

			$this->loader->add_action( 'admin_menu', $plugin_admin, 'bpgt_add_submenu_page' );

			$this->loader->add_action( 'admin_init', $plugin_admin, 'bpgt_plugin_settings' );

			// Register Group Type custom post type.
			$this->loader->add_action( 'init', $plugin_admin, 'register_bp_group_types_cpt' );

			// Add the Group Type management page to the BP Groups menu item.
			// $this->loader->add_action( bp_core_admin_hook(), $plugin_admin, 'relocate_cpt_admin_screen', 99 );

			// Customize the post type input form.
			$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_meta_box' );

			// Register saved group types.

			/*
			 * Comment register group types
			 * @since 2.4.0
			 */
			// $this->loader->add_action( 'bp_groups_register_group_types', $plugin_admin, 'register_group_types', 12 );

			// Listen for AJAX Type ID checks.
			$this->loader->add_action( 'wp_ajax_check-bp-type-id', $plugin_admin, 'ajax_check_type_id' );

			// Save meta when posts are saved.
			$this->loader->add_action( 'save_post', $plugin_admin, 'save' );

			$this->loader->add_filter( 'bp_get_group_type_tax_labels', $plugin_admin, 'bp_create_groups_type_taxo_custom_fields', 10 );

			$this->loader->add_filter( 'bp_get_type_metadata_schema', $plugin_admin, 'bp_create_groups_type_metadata_schema', 10, 2 );
			$this->loader->add_action( 'bp_group_type_edit_form_fields', $plugin_admin, 'bpgt_bp_group_type_edit_form_fields', 20, 2 );

			$this->loader->add_action( 'in_admin_header', $plugin_admin, 'bp_create_groups_type_hide_all_admin_notices_from_setting_page' );

		}

		/**
		 * Register all of the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks() {

			global $bp_grp_types;
			$plugin_public      = new Bp_Add_Group_Types_Public( $this->get_plugin_name(), $this->get_version() );
			$bp_template_option = bp_get_option( '_bp_theme_package_id' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			if ( isset( $bp_grp_types->group_type_search_enabled ) && 'on' === $bp_grp_types->group_type_search_enabled ) {
				$this->loader->add_action( 'bp_directory_groups_search_form', $plugin_public, 'bpgt_modified_group_search_form', 10, 1 );
			}

			$this->loader->add_action( 'bp_ajax_querystring', $plugin_public, 'bpgt_alter_bp_ajax_querystring', 100, 2 );
			$this->loader->add_filter( 'bp_nouveau_get_groups_directory_nav_items', $plugin_public, 'bpgt_nouveau_display_directory_tabs', 10, 1 );
			$this->loader->add_action( 'bp_groups_directory_group_types', $plugin_public, 'bb_display_directory_tabs' );

			$this->loader->add_filter( 'bp_before_has_groups_parse_args', $plugin_public, 'bb_set_has_groups_type_arg', 10, 2 );
			$this->loader->add_action( 'bp_directory_groups_item', $plugin_public, 'bb_group_directory_show_group_type' );

			add_shortcode( 'bp_group', array( $plugin_public, 'bp_group_type_short_code' ) );
			add_shortcode( 'bp_group_tab_shortcode', array( $plugin_public, 'bp_group_type_short_code_in_group_tab' ) );
			$this->loader->add_filter( 'bp_groups_get_group_types', $plugin_public, 'bpgt_groups_get_group_types', 10, 3 );
		}

		/**
		 * Registers a global variable of the plugin - bp-group-types
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function define_globals() {
			global $bp_grp_types;
			$bp_grp_types = new Bp_Add_Group_Types_Globals( $this->get_plugin_name(), $this->get_version() );
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Bp_Add_Group_Types_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}
	}

endif;
