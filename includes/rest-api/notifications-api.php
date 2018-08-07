<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SYS_Notifications_API {

	public function __construct() {

		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	public function rest_api_init() {

		$namespace = 'sys_notification/v1';

		$rest_base = '/get/';

		register_rest_route( $namespace, $rest_base, array(
			array(
				'methods'  => WP_REST_Server::READABLE,
				'callback' => array( $this, 'get_notifications' )
			)
		) );
	}

	public function get_notifications() {

		$all_notifications = get_posts(
			array(
				'post_type'      => 'sys-notification',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'numberposts'    => -1,

				'meta_query' => array(
					'relation' => 'AND',
					array(
						'key'     => '_sys_notification_start_date',
						'value'   => date( 'Y-m-d H:i:s' ),
						'type'    => 'DATETIME',
						'compare' => '<=',
					),
					array(
						'key'     => '_sys_notification_end_date',
						'value'   => date( 'Y-m-d H:i:s' ),
						'type'    => 'DATETIME',
						'compare' => '>=',
					),
				)
			)
		);

		$available_notifications = array();

		if ( ! empty( $all_notifications ) ) {
			foreach ( $all_notifications as $notification ) {

				$post_id = $notification->ID;

				$notification_type = get_post_meta( $post_id, '_sys_notification_type', true );
				$dismissible       = get_post_meta( $post_id, '_sys_notification_dismissible', true );
				$start_date        = get_post_meta( $post_id, '_sys_notification_start_date', true );
				$end_date          = get_post_meta( $post_id, '_sys_notification_end_date', true );

				$img_url = get_the_post_thumbnail_url( $post_id, 'sys-notification-thumbnail' );

				$available_notifications[ $post_id ] = array(
					'id'                   => $post_id,
					'title'                => esc_attr( $notification->post_title ),
					'message'              => wpautop( $notification->post_content ),
					'img_url'              => ( false !== $img_url ? $img_url : '' ),
					'type'                 => $notification_type,
					'dismissible'          => $dismissible,
					'start_date'           => strtotime( $start_date ),
					'end_date'             => strtotime( $end_date ),
				);
			}
		}

		return $available_notifications;
	}

}

new SYS_Notifications_API();

?>