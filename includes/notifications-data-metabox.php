<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SYS_Notifications_Data_Metabox
{
	public function __construct() {

		if ( is_admin() ) {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
			add_action( 'save_post', array( $this, 'save' ) );
		}
	}

	public function add_meta_box( $post_type ) {

        if ( 'sys-notification' === $post_type ) {
			add_meta_box(
				'sys_notification_data_meta_box',
				__( 'SYS Notification Item Data' ),
				array( $this, 'output' ),
				$post_type,
				'normal',
				'high'
			);
		}

	}

	/**
	 * Output the Item Data
	 */
	public function output( $post ) {

		$post_id = $post->ID;

		$notification_type = get_post_meta( $post_id, '_sys_notification_type', true );
		$dismissible       = get_post_meta( $post_id, '_sys_notification_dismissible', true );
		$start_date        = get_post_meta( $post_id, '_sys_notification_start_date', true );
		$end_date          = get_post_meta( $post_id, '_sys_notification_end_date', true );
		?>
		<div class="metabox-panel-wrap">

			<div class="metabox-panel metabox-options-panel">
				<div class="options_group">
					<table class="form-table">

						<tr>
							<td>
								<label for="_sys_notification_type"><?php echo __( 'Notification Type' ); ?></label>
							</td>
							<td class="forminp forminp-select">
								<select
									name="_sys_notification_type"
									id="_sys_notification_type"
									style="width: 150px;"
								>
									<option value="info" selected="selected"><?php echo __( 'Info' ); ?></option>
									<option value="warning" <?php selected( $notification_type, 'warning' );?> >
										<?php echo __( 'Warning' ); ?></option>
									<option value="danger" <?php selected( $notification_type, 'danger' );?> >
										<?php echo __( 'Danger' ); ?></option>
							   </select>
							</td>
						</tr>

						<tr>
							<td>
								<label for="_sys_notification_dismissible"> <?php echo __( 'Allow Dismiss' ); ?></label>
							</td>
							<td class="forminp forminp-select">
								<input
									name="_sys_notification_dismissible"
	                                id="_sys_notification_dismissible"
	                                type="checkbox"
									value="1"
									<?php checked( $dismissible, '1' ); ?>
								/> <label for="_sys_notification_dismissible"><span class="description" style="margin-left:5px;"><?php echo __( 'ON' ); ?></span></label>
							</td>
						</tr>

						<tr>
							<td>
								<label for="_sys_notification_start_date"> <?php echo __( 'Start Date' ); ?></label>
							</td>
							<td class="forminp forminp-select">
								<input
									name="_sys_notification_start_date"
	                                id="_sys_notification_start_date"
	                                type="text"
									value="<?php echo esc_attr( $start_date ); ?>"
								/>
							</td>
						</tr>

						<tr>
							<td>
								<label for="_sys_notification_end_date"> <?php echo __( 'End Date' ); ?></label>
							</td>
							<td class="forminp forminp-select">
								<input
									name="_sys_notification_end_date"
	                                id="_sys_notification_end_date"
	                                type="text"
									value="<?php echo esc_attr( $end_date ); ?>"
								/>
							</td>
						</tr>
						
					</table>

				</div>
			</div>

			<?php
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'sys_notification_metabox_action', 'sys_notification_metabox_nonce_field' );
			?>

			<div class="clear"></div>

		</div>

		<div style="clear: both;"></div>
		<?php
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		// Check if our nonce is set.
		if ( ! isset( $_POST['sys_notification_metabox_nonce_field'] ) || ! check_admin_referer( 'sys_notification_metabox_action', 'sys_notification_metabox_nonce_field' ) ) {
			return $post_id;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check if current user have permission for edit the post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		update_post_meta( $post_id, '_sys_notification_type', $_POST['_sys_notification_type'] );
		update_post_meta( $post_id, '_sys_notification_start_date', $_POST['_sys_notification_start_date'] );
		update_post_meta( $post_id, '_sys_notification_end_date', $_POST['_sys_notification_end_date'] );

		$dismissible = 0;
		if ( isset( $_POST['_sys_notification_dismissible'] ) ) {
			$dismissible = 1;
		}
		update_post_meta( $post_id, '_sys_notification_dismissible', $dismissible );
	}

}

new SYS_Notifications_Data_Metabox();

?>