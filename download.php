<?php
	//
	include_once('../../../wp-load.php');

	$vip = new WP_VIP();

	if($_GET['file']) {
		$vip->GetFile(sanitize_text_field($_GET['file']));
	}
?>