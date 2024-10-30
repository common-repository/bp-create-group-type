<?php
/**
 * Bp add group type search setting file.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package  Bp_Add_Group_Types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed firectly.
}
global $bp_grp_types;
?>
<div class="wbcom-tab-content">
	<div class="wbcom-wrapper-admin">
		<div class="wbcom-admin-title-section">
				<h3><?php esc_html_e( 'Group Type Search Settings', 'bp-add-group-types' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'bpgt_search_settings' );
				do_settings_sections( 'bpgt_search_settings' );
				?>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
						<label for="group-types-search-filter">
							<?php esc_html_e( 'Search Template Content', 'bp-add-group-types' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'This setting will change the group search template in frontend.', 'bp-add-group-types' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<label class="bupr-switch">
							<p>
								<input type="radio" value="textbox" name="bpgt_search_settings[group_type_search_template]" id="bpgt_group_type_search_textbox" <?php echo ( isset( $bp_grp_types->group_type_search_template ) && 'textbox' === $bp_grp_types->group_type_search_template ) ? 'checked' : ''; ?> required/>
								<label for="bpgt_group_type_search_textbox"><?php esc_html_e( 'Textbox', 'bp-add-group-types' ); ?><span class="group_type_search_templatedescription">&nbsp;&nbsp;[<?php esc_html_e( 'BuddyPress Group Search Textbox.', 'bp-add-group-types' ); ?>]</span></label>
							</p>
							<p>
								<input type="radio" value="select" name="bpgt_search_settings[group_type_search_template]" id="bpgt_group_type_search_select" <?php echo ( isset( $bp_grp_types->group_type_search_template ) && 'select' === $bp_grp_types->group_type_search_template ) ? 'checked' : ''; ?> />
								<label for="bpgt_group_type_search_select"><?php esc_html_e( 'Group Type Selectbox', 'bp-add-group-types' ); ?></label>
							</p>
							<p>
								<input type="radio" value="both" name="bpgt_search_settings[group_type_search_template]" id="bpgt_group_type_search_both" <?php echo ( isset( $bp_grp_types->group_type_search_template ) && 'both' === $bp_grp_types->group_type_search_template ) ? 'checked' : ''; ?> />
								<label for="bpgt_group_type_search_both"><?php esc_html_e( 'Both', 'bp-add-group-types' ); ?></label>
							</p>	
							<div class="bupr-slider bupr-round"></div>
						</label>
					</div>
				</div>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
