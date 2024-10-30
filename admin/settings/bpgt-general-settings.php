<?php
/**
 * Bp add group type general setting file.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package  Bp_Add_Group_Types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $bp_grp_types;
?>
<div class="wbcom-tab-content">
	<div class="wbcom-wrapper-admin">
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'General Settings', 'bp-add-group-types' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'bpgt_general_settings' );
				do_settings_sections( 'bpgt_general_settings' );
				?>
				<div class="form-table">
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="group_types_search">
									<?php esc_html_e( 'Enable Group Type Search', 'bp-add-group-types' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'This setting will enable the group type searching on the \'domain.com/groups\' page.', 'bp-add-group-types' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
								<input type="checkbox" value="on" name="bpgt_general_settings[group_type_search_enabled]" id="bpgt_group_types_search_enabled" <?php echo ( isset( $bp_grp_types->group_type_search_enabled ) && 'on' === $bp_grp_types->group_type_search_enabled ) ? 'checked' : ''; ?> />
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
				</div>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
