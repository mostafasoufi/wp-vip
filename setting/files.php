<?php if(get_option('upload_files_folder')) { ?>

<script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#doaction').click(function() {
			var action = jQuery('#action').val();
			
			if(action == 'trash') {
				var agree = confirm('<?php _e('File is deleted on the server! Are you sure?', 'wp-vip'); ?>');

				if(agree)
					return true;
				else
					return false;
			}
		})

	});
</script>

<div class="wrap">
	<h2><?php _e('Files', 'wp-vip'); ?></h2>
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('File name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('File Size', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('File type', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Date added files', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Role', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Downloads', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</thead>

			<tbody>
			<?php
				global $wpdb, $table_prefix;
				$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_files");

				if(count($get_result ) > 0) {
					foreach($get_result as $gets) {
						$i++; ?>
						<tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="column_ID[]" value="<?php echo $gets->ID; ?>" /></th>
							<td class="username column-username">
								<a href="<?php echo bloginfo('url'); ?>/wp-content/plugins/wp-vip/download.php?file=<?php echo $gets->ID; ?>">
									<?php echo $gets->file_name; ?>
								</a>
							</td>
							<td class="column-name"><?php echo vip_formatSizeUnits($gets->file_size); ?></td>
							<td class="column-name"><?php echo $gets->file_type; ?></td>
							<td class="column-name"><?php echo $gets->file_date; ?></td>
							<td class="column-name">
								<?php
									$get_role = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$gets->file_role."'");
									echo $get_role[0]->name;
								?>
							</td>
							<td class="column-name"><?php echo $gets->file_downloads; ?></td>
							<td class="column-name"><a href="?page=vip/files&action=edit&ID=<?php echo $gets->ID; ?>"><?php _e('Edit', 'wp-vip'); ?></a></td>
						</tr>
						<?php
					}
				} else { ?>
						<tr>
							<td colspan="8"><?php _e('Not Found!', 'wp-vip'); ?></td>
						</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('File name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('File Size', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('File type', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Date added files', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Role', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Downloads', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</tfoot>
		</table>

		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action" id="action">
					<option selected="selected"><?php _e('Bulk Actions', 'wp-vip'); ?></option>
					<option value="trash"><?php _e('Remove', 'wp-vip'); ?></option>
				</select>
				<input value="<?php _e('Apply', 'wp-vip'); ?>" name="doaction" id="doaction" class="button-secondary action" type="submit"/>
			</div>
			<br class="clear">
		</div>
	</form>

	<?php if($_GET['action'] == 'edit') { ?>

		<?php
			$folder = get_option('upload_files_folder');

			$get_files = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_files WHERE ID = '".$_GET['ID']."'");
			$get_file_year = $wpdb->get_col("SELECT YEAR(file_date) FROM {$table_prefix}vip_files WHERE ID = '".$_GET['ID']."'");
			$get_roles = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");
		?>

	<form action="" method="post" enctype="multipart/form-data">
		<table>
			<tr><td colspan="2"><h3><?php _e('Edit file', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_file_name" class="label_td"><?php _e('File name', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_file_name" id="vip_file_name" value="<?php echo $get_files[0]->file_name; ?>"/>
					<p class="description"><?php _e('Enter the file name.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td"><?php _e('File path', 'wp-vip'); ?></label></td>
				<td>
					<p class="description">
						<code>
							<?php echo get_bloginfo('url') . "/wp-content/uploads/{$folder}/" . $get_file_year[0] . "/" . $get_files[0]->file_randname; ?>
						</code>
					</p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_file_role"><?php _e('Role', 'wp-vip'); ?></label></td>
				<td>
					<?php
						echo '<select name="vip_file_role" id="vip_file_role">';
						foreach($get_roles as $roles) {
							echo '<option value="'.$roles->ID.'"'.(($get_files[0]->file_role == $roles->ID)?'selected="selected"':'').'>'.$roles->name.'</option>';
						}
						echo '</select>';
					?>
					<p class="description"><?php _e('Select the desired role for this file.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_edit_file" value="<?php _e('Edit', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } else { ?>

	<form action="" method="post" enctype="multipart/form-data">
		<table>
			<tr><td colspan="2"><h3><?php _e('Add new file', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_file_name" class="label_td"><?php _e('File name', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_file_name" id="vip_file_name"/>
					<p class="description"><?php _e('Enter the file name.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label for="vip_file_upload" class="label_td"><?php _e('Select file', 'wp-vip'); ?></label></td>
				<td>
					<input type="file" name="vip_file_upload" id="vip_file_upload"/>
					<p class="description"><?php _e('Select the desired file.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_file_role"><?php _e('Role', 'wp-vip'); ?></label></td>
				<td>
					<?php
						$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");

						echo '<select name="vip_file_role" id="vip_file_role">';
						foreach($get_result as $results) {
							echo '<option value="'.$results->ID.'">'.$results->name.'</option>';
						}
						echo '</select>';
					?>
					<p class="description"><?php _e('Select the desired role for this file.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_add_file" value="<?php _e('Add', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } ?>
</div>

<?php } else { ?>

<div class="wrap">
	<h2><?php _e('Files', 'wp-vip'); ?></h2>
	<div class="error"><p><?php _e('Please specify the path to upload files.', 'wp-vip'); ?></div></p>
</div>
<?php } ?>