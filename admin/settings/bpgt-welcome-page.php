<?php
/**
 * This file is used for rendering and saving plugin welcome settings.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package  Bp_Add_Group_Types
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
				<p class="wbcom-welcome-description">
				<?php esc_html_e( 'BuddyPress Create Group Type plugin offers the feature for BuddyPress users to create Group Types. When the users create a group within the BuddyPress social network website, they can categorize those groups under specific group types. Once selected, the group type is displayed in the group cover area of the group’s pages.', 'bp-add-group-types' ); ?>
				</p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">

		<div class="wbcom-welcome-support-info">
			<h3><?php esc_html_e( 'Help &amp; Support Resources', 'bp-add-group-types' ); ?></h3>
			<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'bp-add-group-types' ); ?></p>

			<div class="wbcom-support-info-wrap">
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'bp-add-group-types' ); ?></h3>
						<p><?php esc_html_e( 'We have prepared an extensive guide on BuddyPress Group Types to learn all aspects of the plugin. You will find most of your answers here.', 'bp-add-group-types' ); ?></p>
						<a href="<?php echo esc_url( 'https://docs.wbcomdesigns.com/doc_category/buddypress-create-group-type/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'bp-add-group-types' ); ?></a>
					</div>
				</div>

				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'bp-add-group-types' ); ?></h3>
						<p><?php esc_html_e( 'We strive to offer the best customer care via our support center. Once your theme is activated, you can ask us for help anytime.', 'bp-add-group-types' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'bp-add-group-types' ); ?></a>
					</div>
				</div>
				<div class="wbcom-support-info-widgets">
					<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'bp-add-group-types' ); ?></h3>
						<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'bp-add-group-types' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'bp-add-group-types' ); ?></a>
					</div>
				</div>
			</div>
		</div>
		</div>

	</div><!-- .wbcom-welcome-content -->
</div><!-- .wbcom-welcome-main-wrapper -->
