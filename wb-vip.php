<?php
/*
Plugin Name: Wordpress VIP
Plugin URI: http://iran98.org/category/wordpress/wp-vip/
Description: Pay per download for VIP user in wordpress
Version: 1.0.1
Author: Wordpress Parsi
Author URI: http://wp-parsi.com/
License: GPL2
*/
	define('VIP_VERSION', '1.0');

	load_plugin_textdomain('wp-vip','wp-content/plugins/wp-vip/langs');

	include_once('inc/lib.php');
	include_once('inc/post.php');
	include_once('inc/wp-vip.class.php');

	function vip_install() {

		global $vip_db_version, $table_prefix, $wpdb;

		$table = $table_prefix . "vip_roles";
		$roles_table_query = (
			"CREATE TABLE ".$table."(
				ID INT(10) NOT NULL auto_increment,
				name VARCHAR(20) NOT NULL,
				credit_required INT(20) NOT NULL,
				PRIMARY KEY(ID)
			) CHARSET=utf8"
		);

		$table = $table_prefix . "vip_users";
		$users_table_query = (
			"CREATE TABLE ".$table."(
				ID INT(10) NOT NULL auto_increment,
				username_ID INT(10) NOT NULL,
				user_role INT(10) NOT NULL,
				PRIMARY KEY(ID)
			) CHARSET=utf8"
		);

		$table = $table_prefix . "vip_files";
		$files_table_query = (
			"CREATE TABLE ".$table."(
				ID INT(10) NOT NULL auto_increment,
				file_name TEXT,
				file_randname VARCHAR(20) NOT NULL,
				file_date DATE NOT NULL,
				file_size VARCHAR(20),
				file_type VARCHAR(20),
				file_role VARCHAR(20) NOT NULL,
				file_downloads INT(10),
				PRIMARY KEY(ID)
			) CHARSET=utf8"
		);

		$table = $table_prefix . "vip_payments";
		$payments_table_query = (
			"CREATE TABLE ".$table."(
				ID INT(10) NOT NULL auto_increment,
				username_ID INT(10) NOT NULL,
				payment_date DATE NOT NULL,
				payment_price INT(10) NOT NULL,
				payment_type VARCHAR(20),
				PRIMARY KEY(ID)
			) CHARSET=utf8"
		);

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		dbDelta($roles_table_query);
		dbDelta($users_table_query);
		dbDelta($files_table_query);
		dbDelta($payments_table_query);

		add_option('vip_db_version', 'vip_db_version');
	}
	register_activation_hook(__FILE__,'vip_install');

	function vip_setting() {
		if (function_exists('add_options_page')) {
			add_menu_page(__('Wordpress VIP', 'wp-vip'), __('Wordpress VIP', 'wp-vip'), 'manage_options', 'vip/setting', 'vip_setting_permission', plugin_dir_url( __FILE__ ).'/images/vip-16.png');
			add_submenu_page('vip/setting', __('Setting', 'wp-vip'), __('Setting', 'wp-vip'), 'manage_options', 'vip/setting', 'vip_setting_permission');
			add_submenu_page('vip/setting', __('Roles', 'wp-vip'), __('Roles', 'wp-vip'), 'manage_options', 'vip/roles', 'vip_roles_permission');
			add_submenu_page('vip/setting', __('Users', 'wp-vip'), __('Users', 'wp-vip'), 'manage_options', 'vip/users', 'vip_users_permission');
			add_submenu_page('vip/setting', __('Files', 'wp-vip'), __('Files', 'wp-vip'), 'manage_options', 'vip/files', 'vip_files_permission');
			add_submenu_page('vip/setting', __('Payments', 'wp-vip'), __('Payments', 'wp-vip'), 'manage_options', 'vip/payments', 'vip_payments_permission');
		}
	}
	add_action('admin_menu', 'vip_setting');

	function vip_setting_permission() {
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		include_once('setting/setting.php');
	}

	function vip_roles_permission() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		global $wpdb, $table_prefix;

		if($_POST['doaction']) {

			$get_IDs = implode(",", $_POST['column_ID']);
			$check_ID = $wpdb->query("SELECT * FROM {$table_prefix}vip_roles WHERE ID='".$get_IDs."'");

			switch($_POST['action']) {
				case 'trash':
					if($check_ID) {
						$check = $wpdb->query("DELETE FROM {$table_prefix}vip_roles WHERE ID IN (".$get_IDs.")");

						if($check) {
							echo "<div class='updated'><p>" . __('The role(s) was successfully deleted.', 'wp-vip') . "</div></p>";
						} else {
							echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
						}

					} else {
						echo "<div class='error'><p>" . __('Not Found!', 'wp-vip') . "</div></p>";
					}
				break;
			}
		}

		if(isset($_POST['vip_add_role'])) {

			$name = $_POST['vip_name_role'];
			$credit = $_POST['vip_credit_role'];

			if($name && $credit) {

				$check_name = $wpdb->query("SELECT * FROM {$table_prefix}vip_roles WHERE name='".$name."'");

				if(!$check_name) {

					$check = $wpdb->query("INSERT INTO {$table_prefix}vip_roles (name, credit_required) VALUES ('".$name."', '".$credit."')");

					if($check) {
						echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> role was added successfully', 'wp-vip'), $name) . "</div></p>";
					} else {
						echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
					}

				} else {
					echo "<div class='error'><p>" . __('Error! This role has already been added.', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		if(isset($_POST['vip_edit_role'])) {

			$name = $_POST['vip_name_role'];
			$credit = $_POST['vip_credit_role'];

			if($name && $credit) {

				$check = $wpdb->query("UPDATE {$table_prefix}vip_roles SET `name` = '".$name."', `credit_required` = '".$credit."' WHERE `ID` = '".$_GET['ID']."'");

				if($check) {
					echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> role was editing successfully', 'wp-vip'), $name) . "</div></p>";
				} else {
					echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		include_once('setting/role.php');
	}

	function vip_users_permission() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		global $wpdb, $table_prefix;

		if($_POST['doaction']) {

			$get_IDs = implode(",", $_POST['column_ID']);
			$check_ID = $wpdb->query("SELECT * FROM {$table_prefix}vip_users WHERE ID='".$get_IDs."'");

			switch($_POST['action']) {
				case 'trash':
					if($check_ID) {
						$check = $wpdb->query("DELETE FROM {$table_prefix}vip_users WHERE ID IN (".$get_IDs.")");

						if($check) {
							echo "<div class='updated'><p>" . __('The user(s) was successfully deleted.', 'wp-vip') . "</div></p>";
						} else {
							echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
						}

					} else {
						echo "<div class='error'><p>" . __('Not Found!', 'wp-vip') . "</div></p>";
					}
				break;
			}
		}

		if(isset($_POST['vip_add_user'])) {

			$user_ID = $_POST['vip_user_name'];
			$role_ID = $_POST['vip_user_role'];
			
			$user_info = get_userdata($user_ID);
			$user_info = $user_info->user_firstname;

			if($user_ID && $role_ID) {

				$check_name = $wpdb->query("SELECT * FROM {$table_prefix}vip_users WHERE username_ID = '".$user_ID."'");

				if(!$check_name) {

					$check = $wpdb->query("INSERT INTO {$table_prefix}vip_users (username_ID, user_role) VALUES ('".$user_ID."', '".$role_ID."')");

					if($check) {
						echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> user was added successfully', 'wp-vip'), $user_info) . "</div></p>";
					} else {
						echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
					}

				} else {
					echo "<div class='error'><p>" . __('Error! This user has already been added.', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		if(isset($_POST['vip_edit_user'])) {

			$user_ID = $_POST['vip_user_name'];
			$role_ID = $_POST['vip_user_role'];

			$user_info = get_userdata($user_ID);
			$user_info = $user_info->user_firstname;

			if($user_ID && $role_ID) {

				$check = $wpdb->query("UPDATE {$table_prefix}vip_users SET `username_ID` = '".$user_ID."', `user_role` = '".$role_ID."' WHERE `ID` = '".$_GET['ID']."'");

				if($check) {
					echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> user was editing successfully', 'wp-vip'), $user_info) . "</div></p>";
				} else {
					echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		include_once('setting/users.php');
	}

	function vip_files_permission() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		global $wpdb, $table_prefix;

		if($_POST['doaction']) {

			$get_IDs = implode(",", $_POST['column_ID']);
			$check_ID = $wpdb->query("SELECT * FROM {$table_prefix}vip_files WHERE ID='".$get_IDs."'");

			switch($_POST['action']) {
				case 'trash':
					if($check_ID) {

						$get_files = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_files WHERE ID IN (".$get_IDs.")");
						$get_file_year = $wpdb->get_col("SELECT YEAR(file_date) FROM {$table_prefix}vip_files WHERE ID = '".$get_IDs."'");
						foreach ($get_files as $files) {
							$file = unlink('../wp-content/uploads/vip-files/' . $get_file_year[0] . '/' . $files->file_randname);
						}

						$check = $wpdb->query("DELETE FROM {$table_prefix}vip_files WHERE ID IN (".$get_IDs.")");

						if($check && $file) {
							echo "<div class='updated'><p>" . __('The role(s) was successfully deleted.', 'wp-vip') . "</div></p>";
						} else {
							echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
						}

					} else {
						echo "<div class='error'><p>" . __('Not Found!', 'wp-vip') . "</div></p>";
					}
				break;
			}
		}

		if(isset($_POST['vip_add_file'])) {

			$file_name = $_POST['vip_file_name'];
			$file_upload = $_FILES['vip_file_upload'];
			$file_role = $_POST['vip_file_role'];

			if($file_name && !$file_upload['error'] && $file_role) {

				$check_name = $wpdb->query("SELECT * FROM {$table_prefix}vip_files WHERE file_name = '".$file_name."'");

				if(!$check_name) {

					$folder = get_option('upload_files_folder');
					$date = date('Y-m-d' ,current_time('timestamp',0));
					$year = date('Y' ,current_time('timestamp',0));
					$rand = rand(10000, 99999);

					$file_randname = "file-{$rand}." . vip_file_type($file_upload['name']);
					
					if(!is_dir("../wp-content/uploads/{$folder}/")) {
						mkdir("../wp-content/uploads/{$folder}/", 0777);
						rename("../wp-content/uploads/{$folder}/", "../wp-content/uploads/{$folder}/");
					}

					if(!is_dir("../wp-content/uploads/{$folder}/{$year}/")) {
						mkdir("../wp-content/uploads/{$folder}/{$year}/", 0777);
					}

					$result_upload = move_uploaded_file($file_upload['tmp_name'], "../wp-content/uploads/{$folder}/{$year}/{$file_randname}");

					if($result_upload) {

						$check = $wpdb->query("INSERT INTO {$table_prefix}vip_files (file_name, file_randname, file_date, file_size, file_type, file_role, file_downloads) VALUES ('".$file_name."', '".$file_randname."', '".$date."', '".$file_upload['size']."', '".$file_upload['type']."', '".$file_role."', '0')");

						if($check) {
							echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> file was added successfully', 'wp-vip'), $file_name) . "</div></p>";
						} else {
							echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
						}

					} else {
						echo "<div class='error'><p>" . __('Upload error! An unknown error has occurred.', 'wp-vip') . "</div></p>";
					}

				} else {
					echo "<div class='error'><p>" . __('Error! This file has already been added.', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}

		}

		if(isset($_POST['vip_edit_file'])) {

			$file_name = $_POST['vip_file_name'];
			$file_role = $_POST['vip_file_role'];

			if($file_name && $file_role) {

				$check = $wpdb->query("UPDATE {$table_prefix}vip_files SET `file_name` = '".$file_name."', `file_role` = '".$file_role."' WHERE `ID` = '".$_GET['ID']."'");

				if($check) {
					echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> file was editing successfully', 'wp-vip'), $file_name) . "</div></p>";
				} else {
					echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		include_once('setting/files.php');
	}

	function vip_payments_permission() {

		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		global $wpdb, $table_prefix;

		if($_POST['doaction']) {

			$get_IDs = implode(",", $_POST['column_ID']);
			$check_ID = $wpdb->query("SELECT * FROM {$table_prefix}vip_payments WHERE ID='".$get_IDs."'");

			switch($_POST['action']) {
				case 'trash':
					if($check_ID) {
						$check = $wpdb->query("DELETE FROM {$table_prefix}vip_payments WHERE ID IN (".$get_IDs.")");

						if($check) {
							echo "<div class='updated'><p>" . __('The payment(s) was successfully deleted.', 'wp-vip') . "</div></p>";
						} else {
							echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
						}

					} else {
						echo "<div class='error'><p>" . __('Not Found!', 'wp-vip') . "</div></p>";
					}
				break;
			}
		}

		if(isset($_POST['vip_add_payment'])) {

			$user_ID = $_POST['vip_user_name'];
			$payment_price = $_POST['vip_payment_price'];
			$payment_type = $_POST['vip_payment_type'];

			$date = date('Y-m-d' ,current_time('timestamp',0));
			
			$user_info = get_userdata($user_ID);
			$user_info = $user_info->user_firstname;

			if($user_ID && $payment_price && $payment_type) {

				$check = $wpdb->query("INSERT INTO {$table_prefix}vip_payments (username_ID, payment_date, payment_price, payment_type) VALUES ('".$user_ID."', '".$date."', '".$payment_price."', '".$payment_type."')");

				if($check) {
					echo "<div class='updated'><p>" . sprintf(__('Payment <strong>%s</strong> added successfully to user <strong>%s</strong>', 'wp-vip'), $payment_price, $user_info) . "</div></p>";
				} else {
					echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		if(isset($_POST['vip_edit_payment'])) {

			$user_ID = $_POST['vip_user_name'];
			$payment_price = $_POST['vip_payment_price'];
			$payment_type = $_POST['vip_payment_type'];

			$user_info = get_userdata($user_ID);
			$user_info = $user_info->user_firstname;

			if($user_ID && $payment_price && $payment_type) {

				$check = $wpdb->query("UPDATE {$table_prefix}vip_payments SET `username_ID` = '".$user_ID."', `payment_price` = '".$payment_price."', `payment_type` = '".$payment_type."' WHERE `ID` = '".$_GET['ID']."'");

				if($check) {
					echo "<div class='updated'><p>" . sprintf(__('<strong>%s</strong> payment was editing successfully', 'wp-vip'), $user_info) . "</div></p>";
				} else {
					echo "<div class='error'><p>" . __('Failed! Unknown error has occurred', 'wp-vip') . "</div></p>";
				}

			} else {
				echo "<div class='error'><p>" . __('Error! Please complete the required fields.', 'wp-vip') . "</div></p>";
			}
		}

		include_once('setting/payments.php');
	}
?>