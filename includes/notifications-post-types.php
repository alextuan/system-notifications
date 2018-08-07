<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SYS_Notifications_Post_Types
{
	public function __construct() {

		add_action( 'init', array( $this, 'register_post_type' ), 8 );

		if ( is_admin() ) {
			/* START : Update the columns for SYS Notification post type */

			// Add custom column for Notification post type
			add_filter( 'manage_edit-sys-notification_columns', array( $this, 'edit_columns' ) );
			add_filter( 'manage_sys-notification_posts_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_sys-notification_posts_custom_column', array( $this, 'custom_columns' ) );

			/* END : Update the columns for SYS Notification post type */
		}
	}

	public function register_post_type() {

		add_image_size( 'sys-notification-thumbnail', 160, 160, true );

		// Register sys-notification post type
		$labels_array = array(
							'name'               => __( 'Notification Items' ),
							'singular_name'      => __( 'Notification Item' ),
							'menu_name'          => __( 'Notification' ),
							'all_items'          => __( 'Notification Items' ),
							'add_new'            => __( 'Add Item' ),
							'add_new_item'       => __( 'Add New Notification' ),
							'edit'               => __( 'Edit' ),
							'edit_item'          => __( 'Edit Item' ),
							'new_item'           => __( 'New Notification' ),
							'view'               => __( 'View' ),
							'view_item'          => __( 'View Item' ),
							'not_found'          => __( 'No Notification Found' ),
							'not_found_in_trash' => __( 'No Notification found in Trash' ),
							'parent'             => __( 'Parent' )
						);

		$supports_array = array(
								'title',
								'editor',
								'thumbnail',
								'page-attributes'
							);

		register_post_type( 'sys-notification',
							array(
								'description'     => __( 'SYS Notification Custom Post Type' ),
								'public'          => false,
								'show_ui'         => true,
								'show_in_menu'    => true,
								'capability_type' => 'post',
								'hierarchical'    => false,
								'query_var'       => false,
								'has_archive'     => false,
								'_builtin'        => false,
								'supports'        => $supports_array,
								'labels'          => $labels_array,
								'can_export'	  => false,
							) );
	}

	/* Custom column for SYS Notification post type */

	public function edit_columns( $columns ) {

		$columns = array();

		$columns['cb']            = '<input type="checkbox" />';
		$columns['img']           = __( 'Image' );
		$columns['title']         = __( 'Name' );
		$columns['type']          = __( 'Type' );
		$columns['dismissible']   = __( 'Dismissible' );
		$columns['start_date']    = __( 'Start Date' );
		$columns['end_date']      = __( 'End Date' );

		return $columns;
	}

	public function custom_columns( $column ) {

		global $post;

		$post_id = $post->ID;

		switch ( $column ) {
			case "img" :
				$img_url = get_the_post_thumbnail_url( $post_id, 'sys-notification-thumbnail' );
				echo false !== $img_url ? '<img src="'.$img_url.'" width="40" >' : '';

				break;

			case "type" :
				$notification_type = get_post_meta( $post_id, '_sys_notification_type', true );
				echo empty( $notification_type ) ? __( 'Info' ) : ucwords( str_replace( '_', ' ', $notification_type ) );

				break;

			case "dismissible" :
				$dismissible = get_post_meta( $post_id, '_sys_notification_dismissible', true );
				echo empty( $dismissible ) ? __( 'OFF' ) : __( 'ON' );

				break;

			case "start_date" :
				$start_date = get_post_meta( $post_id, '_sys_notification_start_date', true );
				echo empty( $start_date ) ? __( 'N/A' ) : $start_date;

				break;

			case "end_date" :
				$end_date = get_post_meta( $post_id, '_sys_notification_end_date', true );
				echo empty( $end_date ) ? __( 'N/A' ) : $end_date;

				break;
		}
	}
}

new SYS_Notifications_Post_Types();

?>