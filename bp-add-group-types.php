<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Bp_Add_Group_Types
 *
 * @wordpress-plugin
 * Plugin Name:       Wbcom Designs - BuddyPress Create Group Type
 * Plugin URI:        https://wbcomdesigns.com/
 * Description:       This plugin adds a new feature to add Group Types for BuddyPress Groups. This allows an easy categorization of BP Groups.
 * Version:           2.8.0
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bp-add-group-types
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
* Constants used in the plugin
*/
if ( ! defined( 'BP_GROUP_TYPE_PLUGIN_VERSION' ) ) {
	define( 'BP_GROUP_TYPE_PLUGIN_VERSION', '2.8.0' );
}
if ( ! defined( 'BP_GROUP_TYPE_PLUGIN_BASENAME' ) ) {
	define( 'BP_GROUP_TYPE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'BP_GROUP_TYPE_PLUGIN_PATH' ) ) {
	define( 'BP_GROUP_TYPE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'BP_GROUP_TYPE_PLUGIN_URL' ) ) {
	define( 'BP_GROUP_TYPE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bp-add-group-types-activator.php
 */
function activate_bp_add_group_types() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-add-group-types-activator.php';
	Bp_Add_Group_Types_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bp-add-group-types-deactivator.php
 */
function deactivate_bp_add_group_types() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-bp-add-group-types-deactivator.php';
	Bp_Add_Group_Types_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_bp_add_group_types' );
register_deactivation_hook( __FILE__, 'deactivate_bp_add_group_types' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-bp-add-group-types.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bp_add_group_types() {

	$plugin = new Bp_Add_Group_Types();
	$plugin->run();

}

add_action( 'bp_loaded', 'bpgt_plugin_init' );

/**
 * Check plugin requirement on plugins loaded
 * this plugin requires BuddyPress to be installed and active
 */
function bpgt_plugin_init() {
	if ( bp_group_type_check_config() ) {
		run_bp_add_group_types();
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bpgt_plugin_links' );
	}
}

/**
 * Check Configuration on plugin activation.
 */
function bp_group_type_check_config() {
	global $bp;
	$check  = array();
	$config = array(
		'blog_status'    => false,
		'network_active' => false,
		'network_status' => true,
	);
	if ( get_current_blog_id() == bp_get_root_blog_id() ) {
		$config['blog_status'] = true;
	}

	$network_plugins = get_site_option( 'active_sitewide_plugins', array() );

	// No Network plugins.
	if ( empty( $network_plugins ) ) {

		// Looking for BuddyPress and bp-activity plugin.
		$check[] = $bp->basename;
	}
	$check[] = BP_GROUP_TYPE_PLUGIN_BASENAME;

	// Are they active on the network ?
	$network_active = array_diff( $check, array_keys( $network_plugins ) );

	// If result is 1, your plugin is network activated
	// and not BuddyPress or vice & versa. Config is not ok.
	if ( count( $network_active ) == 1 ) {
		$config['network_status'] = false;
	}

	// We need to know if the plugin is network activated to choose the right
	// notice ( admin or network_admin ) to display the warning message.
	$config['network_active'] = isset( $network_plugins[ BP_GROUP_TYPE_PLUGIN_BASENAME ] );

	// if BuddyPress config is different than bp-activity plugin.
	if ( ! $config['blog_status'] || ! $config['network_status'] ) {

		$warnings = array();
		if ( ! bp_core_do_network_admin() && ! $config['blog_status'] ) {
			add_action( 'admin_notices', 'bpgt_same_blog' );
			$warnings[] = __( 'Buddypress Create Group Types requires to be activated on the blog where BuddyPress is activated.', 'bp-add-group-types' );
		}

		if ( bp_core_do_network_admin() && ! $config['network_status'] ) {
			add_action( 'admin_notices', 'bpgt_same_network_config' );
			$warnings[] = __( 'BuddyPress Create Group Types and BuddyPress need to share the same network configuration.', 'bp-add-group-types' );
		}

		if ( ! empty( $warnings ) ) :
			return false;
		endif;
		$bpgs_active = in_array( 'buddypress-group-type-search/buddypress-groups-search.php', get_site_option( 'active_sitewide_plugins' ), true );
		if ( current_user_can( 'activate_plugins' ) && true === $bpgs_active ) {
			add_action( $config['network_active'] ? 'network_admin_notices' : 'admin_notices', 'bpgts_remove_plugin_admin_notice' );
		}
		if ( ! bp_is_active( 'groups' ) ) {
			add_action( $config['network_active'] ? 'network_admin_notices' : 'admin_notices', 'bpgt_plugin_require_group_component_admin_notice' );
		}

		// Display a warning message in network admin or admin.
	}
	return true;
}

/**
 * Display admin notice when BuddyPress is not activated.
 */
function bpgt_same_blog() {
	echo '<div class="error"><p>'
	. esc_html__( 'BuddyPress Create Group Types requires to be activated on the blog where BuddyPress is activated.', 'bp-add-group-types' )
	. '</p></div>';
}

/**
 * Display admin notice when BuddyPress is not share the network configuration.
 */
function bpgt_same_network_config() {
	echo '<div class="error"><p>'
	. esc_html__( 'BuddyPress Create Group Types and BuddyPress need to share the same network configuration.', 'bp-add-group-types' )
	. '</p></div>';
}

/**
 * Function to through admin notice if BuddyPress Group Type Search is active.
 */
function bpgts_remove_plugin_admin_notice() {
	$bpgt_plugin  = esc_html__( 'BuddyPress Create Group Types', 'bp-add-group-types' );
	$bpgts_plugin = esc_html__( 'BuddyPress Group Type Search', 'bp-add-group-types' );

	echo '<div class="error"><p>'
	/* translators: %1$s: BuddyPress Create Group Types,%2$s: BuddyPress Group Type Search,%3$s: BuddyPress Group Type Search */
	. sprintf( esc_html__( '%1$s do not require %2$s to be installed and active as it contains functions of %3$s plugin.', 'bp-add-group-types' ), '<strong>' . esc_html( $bpgt_plugin ) . '</strong>', '<strong>' . esc_html( $bpgts_plugin ) . '</strong>', '<strong>' . esc_html( $bpgts_plugin ) . '</strong>' )
	. '</p></div>';
}

/**
 * Check for BuddyPress and display notice to admin.
 */
function bpgt_requires_buddypress() {
	if ( ! class_exists( 'Buddypress' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		add_action( 'admin_notices', 'bpgt_plugin_admin_notice' );
	}
}

add_action( 'admin_init', 'bpgt_requires_buddypress' );

/**
 * Function to through admin notice if BuddyPress is not active.
 */
function bpgt_plugin_admin_notice() {
	$bpgt_plugin = esc_html__( 'BuddyPress Create Group Types', 'bp-add-group-types' );
	$bp_plugin   = esc_html__( 'BuddyPress', 'bp-add-group-types' );
	echo '<div class="error"><p>'
	/* translators: %1$s: BuddyPress Create Group Types,%2$s: BuddyPress */
	. sprintf( esc_html__( '%1$s is ineffective as it requires %2$s to be installed and active.', 'bp-add-group-types' ), '<strong>' . esc_html( $bpgt_plugin ) . '</strong>', '<strong>' . esc_html( $bp_plugin ) . '</strong>' )
	. '</p></div>';
	if ( null !== filter_input( INPUT_GET, 'activate' ) ) {
		$activate = filter_input( INPUT_GET, 'activate' );
			unset( $activate );
	}
}



/**
 * Function to through admin notice if BuddyPress group components is not active.
 */
function bpgt_plugin_require_group_component_admin_notice() {
	$bpgt_plugin  = esc_html__( 'BuddyPress Create Group Types', 'bp-add-group-types' );
	$bp_component = esc_html__( 'Groups Component', 'bp-add-group-types' );
	if ( ! bp_is_active( 'groups' ) ) {
		echo '<div class="error"><p>'
		/* translators: %1$s: BuddyPress Create Group Types,%2$s: Groups Component */
		. sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be active.', 'bp-add-group-types' ), '<strong>' . esc_html( $bpgt_plugin ) . '</strong>', '<strong>' . esc_html( $bp_component ) . '</strong>' )
		. '</p></div>';
		if ( null !== filter_input( INPUT_GET, 'activate' ) ) {
			$activate = filter_input( INPUT_GET, 'activate' );
			unset( $activate );
		}
	}
}

/**
 * Function to set plugin action links.
 *
 * @param array $links Plugin settings links array.
 */
function bpgt_plugin_links( $links ) {

	if ( bp_is_network_activated() ) {
		$group_url = get_admin_url( bp_get_root_blog_id(), 'edit-tags.php?taxonomy=bp_group_type' );
	} else {
		$group_url = bp_get_admin_url( add_query_arg( array( 'taxonomy' => 'bp_group_type' ), 'edit-tags.php' ) );
	}

	$bpgt_links = array(
		'<a href="' . $group_url . '">' . esc_html__( 'Settings', 'bp-add-group-types' ) . '</a>',
		'<a href="https://wbcomdesigns.com/contact/" target="_blank">' . esc_html__( 'Support', 'bp-add-group-types' ) . '</a>',
	);
	return array_merge( $links, $bpgt_links );
}



/**
 * Function to Migrate group type custom data into buddypress latest version
 *
 * @Single 2.4.0
 */
function bpgt_admin_migrate_group_type_settings() {

	global $pagenow;
	$migrate_buddypress_create_group_type = get_option( 'migrate_buddypress_create_group_type', true );
	if ( ! $migrate_buddypress_create_group_type && ( 'plugins.php' == $pagenow || 'edit-tags.php' == $pagenow ) ) {
		$query = new WP_Query(
			array(
				'post_type'     => 'bp_group_type',
				'post_per_page' => -1,
			)
		);
		$posts = $query->posts;
		if ( isset( $query->posts ) ) {
			foreach ( $query->posts as $post ) {
				$type_id            = get_post_meta( $post->ID, 'type_id', true );
				$bp_group_type_term = get_term_by( 'slug', $type_id, 'bp_group_type' );

				if ( ! empty( $bp_group_type_term ) ) {
					$bp_group_type_id = $bp_group_type_term->term_id;

					$display_as_tab        = get_post_meta( $post->ID, 'display_as_tab', true );
					$show_in_list          = get_post_meta( $post->ID, 'show_in_list', true );
					$singular_name         = get_post_meta( $post->ID, 'singular_name', true );
					$has_directory         = get_post_meta( $post->ID, 'has_directory', true );
					$has_directory_slug    = get_post_meta( $post->ID, 'has_directory_slug', true );
					$show_in_create_screen = get_post_meta( $post->ID, 'show_in_create_screen', true );
					$create_screen_checked = get_post_meta( $post->ID, 'create_screen_checked', true );
					$_bp_group_type_key    = get_post_meta( $post->ID, '_bp_group_type_key', true );

					$display_as_tab        = ( 'on' == $display_as_tab ) ? 1 : 0;
					$has_directory         = ( 'on' == $has_directory ) ? 1 : 0;
					$show_in_create_screen = ( 'on' == $show_in_create_screen ) ? 1 : 0;
					$show_in_list          = ( 'on' == $show_in_list ) ? 1 : 0;
					$create_screen_checked = ( 'on' == $create_screen_checked ) ? 1 : 0;

					update_term_meta( $bp_group_type_id, 'bp_group_type_display_as_tab', $display_as_tab );
					update_term_meta( $bp_group_type_id, 'bp_type_show_in_list', $show_in_list );
					update_term_meta( $bp_group_type_id, 'bp_group_type_show_in_list', $show_in_list );

					update_term_meta( $bp_group_type_id, 'bp_type_singular_name', $singular_name );
					update_term_meta( $bp_group_type_id, 'bp_type_name', $singular_name );
					update_term_meta( $bp_group_type_id, 'bp_type_has_directory', $has_directory );
					update_term_meta( $bp_group_type_id, 'bp_type_show_in_create_screen', $show_in_create_screen );
					update_term_meta( $bp_group_type_id, 'bp_type_show_in_create_screen', $show_in_create_screen );
					update_term_meta( $bp_group_type_id, 'bp_type_directory_slug', $has_directory_slug );
					update_term_meta( $bp_group_type_id, 'bp_group_type_create_screen_checked', $show_in_create_screen );
				}
			}
		}

		update_option( 'migrate_buddypress_create_group_type', true );

	}
}
add_action( 'admin_init', 'bpgt_admin_migrate_group_type_settings' );

/**
 * Display Group type View Link when has directory on.
 *
 * @param array $actions Row Actions.
 * @param array $tags Group Object.
 *
 * @return Array
 */
function bpgt_group_type_view_action( $actions, $tags ) {
	$group_type = $tags->name;

	$group_type_object = bp_groups_get_group_type_object( $tags->name );

	if ( empty( $group_type_object->has_directory ) ) {
		return $actions;
	}

	$actions['view'] = sprintf(
		'<a href="%s">%s</a>',
		esc_url( bp_get_group_type_directory_permalink( $group_type ) ),
		__( 'View', 'bp-add-group-types' )
	);

	return $actions;
}
add_filter( 'bp_group_type_row_actions', 'bpgt_group_type_view_action', 10, 2 );

/**
 * Redirect to plugin settings page after activated
 *
 * @since  1.0.0
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function bp_group_type_activation_redirect_settings( $plugin ) {

	if ( class_exists( 'BuddyPress' ) && plugin_basename( __FILE__ ) == $plugin ) {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) {
			wp_redirect( admin_url( 'admin.php?page=bp-add-group-types' ) );
			exit;
		}
	}
}
add_action( 'activated_plugin', 'bp_group_type_activation_redirect_settings' );
