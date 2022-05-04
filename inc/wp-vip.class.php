<?php
	class WP_VIP {

		var $wpdb;
		var $table_prefix;

		public function __construct() {

			global $wpdb;
			global $table_prefix;

			$this->wpdb = $wpdb;
			$this->table_prefix = $table_prefix;

		}

		public function GetFile($file_ID) {

			$get_files = $this->wpdb->get_row("SELECT * FROM {$this->table_prefix}vip_files WHERE ID = '".$file_ID."'");

			if($get_files) {

				if( vip_get_file_role($file_ID, 'ID') == vip_get_user_role('ID') ) {

					$folder = get_option('upload_files_folder');
					$get_file_year = $this->wpdb->get_col("SELECT YEAR(file_date) FROM {$this->table_prefix}vip_files WHERE ID = '".$file_ID."'");

					$file = "../../uploads/{$folder}/" . $get_file_year[0] . "/" . $get_files->file_randname;

					$rand = rand(10000, 99999);
					$file_name = $rand . '.' . vip_file_type($get_files->file_randname);

					$file_mime = vip_mime_type($file_name);

					header("Content-Description: File Transfer");
					header("Content-Disposition: attachment; filename=$file_name");
					header("Content-Type: $file_mime");
					header("Content-Transfer-Encoding: binary");
					readfile($file);

					if(headers_sent()) {
						$this->wpdb->query("UPDATE {$this->table_prefix}vip_files SET `file_downloads` = file_downloads+1 WHERE ID = '".$file_ID."'");
					}

				} else {

					$get_roles = $this->wpdb->get_row("SELECT * FROM {$this->table_prefix}vip_roles WHERE `ID` = '".$get_files->file_role."'");

					wp_die(sprintf(__('You do not have permission to download this file. this file for <strong>%s</strong>', 'wp-vip'), $get_roles->name), __('Access Error', 'wp-vip'), array('back_link' => true));

				}

			} else {
				wp_die(__('Error! This file was not found.', 'wp-vip'), __('Not Found!', 'wp-vip'), array('back_link' => true));
			}
		}

	}