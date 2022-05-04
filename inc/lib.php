<?php
	function vip_formatSizeUnits($bytes) {

		if ($bytes >= 1073741824) {
			$bytes = number_format($bytes / 1073741824, 2) . ' GB';
		} elseif ($bytes >= 1048576) {
			$bytes = number_format($bytes / 1048576, 2) . ' MB';
		} elseif ($bytes >= 1024) {
			$bytes = number_format($bytes / 1024, 2) . ' KB';
		} elseif ($bytes > 1) {
			$bytes = $bytes . ' Byte';
		} elseif ($bytes == 1) {
			$bytes = $bytes . ' Byte';
		} else {
			$bytes = '0 Byte';
		}

		return $bytes;
	}

	function vip_file_type($filename) {

		$path_info = pathinfo($filename);
		return $path_info['extension'];

	}
	
	function vip_mime_type($filename) {

		$mime_types = array(
			"pdf"	=>	"application/pdf",
			"exe"	=>	"application/octet-stream",
			"zip"	=>	"application/zip",
			"docx"	=>	"application/msword",
			"doc"	=>	"application/msword",
			"xls"	=>	"application/vnd.ms-excel",
			"ppt"	=>	"application/vnd.ms-powerpoint",
			"gif"	=>	"image/gif",
			"png"	=>	"image/png",
			"jpeg"	=>	"image/jpg",
			"jpg"	=>	"image/jpg",
			"mp3"	=>	"audio/mpeg",
			"wav"	=>	"audio/x-wav",
			"mpeg"	=>	"video/mpeg",
			"mpg"	=>	"video/mpeg",
			"mpe"	=>	"video/mpeg",
			"mov"	=>	"video/quicktime",
			"avi"	=>	"video/x-msvideo",
			"3gp"	=>	"video/3gpp",
			"css"	=>	"text/css",
			"jsc"	=>	"application/javascript",
			"js"	=>	"application/javascript",
			"php"	=>	"text/html",
			"htm"	=>	"text/html",
			"html"	=>	"text/html"
		);

		$extension = strtolower(end(explode('.',$filename)));

		return $mime_types[$extension];
	}

	function vip_get_credit_by_userid($user_id) {

		global $wpdb, $table_prefix;

		$get_user = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_users WHERE `username_ID` = '".$user_id."'");

		$get_role = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$get_user->user_role."'");
		
		return $get_role->credit_required;
	}

	function vip_get_payments_by_userid($user_id) {

		global $wpdb, $table_prefix;

		$get_payment = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_payments WHERE `username_ID` = '".$user_id."'");
		foreach($get_payment as $payments)
			$total += $payments->payment_price;

		return $total;
	}

	function vip_get_user_role($column) {

		global $wpdb, $table_prefix, $current_user;

		$get_users = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_users WHERE `username_ID` = '".$current_user->ID."'");
		$get_roles = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$get_users->user_role."'");

		return $get_roles->$column;
	}

	function vip_get_file_role($file_id, $column) {

		global $wpdb, $table_prefix;

		$get_files = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_files WHERE `ID` = '".$file_id."'");
		$get_roles = $wpdb->get_row("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$get_files->file_role."'");

		return $get_roles->$column;
	}
?>