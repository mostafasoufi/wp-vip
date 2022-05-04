<div class="wrap">
	<h2><?php _e('Settings', 'wp-vip'); ?></h2>
	<table class="form-table">
		<form method="post" action="options.php">
			<?php wp_nonce_field('update-options');?>
			<tr><th><h3><?php _e('General settins', 'wp-vip'); ?></h4></th></tr>
			<tr>
				<td><?php _e('Name the folder for files to upload', 'wp-vip'); ?></td>	
				<td>
					<input type="text" class="regular-text code" name="upload_files_folder" value="<?php echo get_option('upload_files_folder'); ?>"/>
					<p class="description"><code dir="ltr"><?php $upload_baseurl = wp_upload_dir(); echo $upload_baseurl['baseurl']; ?>/<strong>{folder-name}</strong></code></p>
				</td>
			</tr>

			<tr>
				<td>
					<p class="submit">
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="upload_files_folder" />
					<input type="submit" class="button-primary" name="Submit" value="<?php _e('Update', 'wp-vip'); ?>" />
					</p>
				</td>
			</tr>
		</form>	
	</table>
</div>