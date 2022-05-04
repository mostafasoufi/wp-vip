<?php
	function vip_post_box() {
		add_meta_box('wb-meta-box', __('User role', 'wp-vip'), 'vip_meta_box', 'post', 'normal', 'high');
	}
	add_action('add_meta_boxes', 'vip_post_box');

	function vip_meta_box($post) {

		global $wpdb, $table_prefix;

		$values = get_post_custom($post->ID);
		wp_nonce_field('vip_nonce', 'meta_box_nonce');

		$get_roles = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");

		include_once('/../setting/meta-box.php');
	}

	function vip_post_save($post_ID) {

		if( isset( $_POST['vip_post_role'] ) )
		update_post_meta($post_ID, 'post_role', sanitize_text_field($_POST['vip_post_role']));

	}
	add_action('save_post', 'vip_post_save');

	function vip_post($content) {

		global $post, $wpdb, $table_prefix;

		$post_role = get_post_meta($post->ID, "post_role", true);

		if($post_role) {

			if( $post_role == vip_get_user_role('ID') ){
				return $content;
			} else {
				$get_roles = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$post_role."'");
				return sprintf("این پست مختص کاربران %s است.", $get_roles->name);
			}

		} else {

			return $content;
		}
	}
	add_filter('the_content', 'vip_post');
?>