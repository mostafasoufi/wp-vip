<div class="wrap">
	<h2><?php _e('Roles', 'wp-vip'); ?></h2>
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Role name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Necessary valid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Total users', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</thead>

			<tbody>
			<?php
				global $wpdb, $table_prefix;
				$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");

				if(count($get_result ) > 0) {
					foreach($get_result as $gets) {
						$i++; ?>
						<tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="column_ID[]" value="<?php echo $gets->ID ; ?>" /></th>
							<td class="column-name"><?php echo $gets->name; ?></td>
							<td class="column-name"><?php echo number_format($gets->credit_required, 0); ?></td>
							<td class="column-name">
								<?php
									$get_users = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_users WHERE `user_role` = '".$gets->ID."'");
									echo count($get_users);
								?>
							</td>
							<td class="column-name"><a href="?page=vip/roles&action=edit&ID=<?php echo $gets->ID; ?>"><?php _e('Edit', 'wp-vip'); ?></a></td>
						</tr>
						<?php
					}
				} else { ?>
						<tr>
							<td colspan="5"><?php _e('Not Found!', 'wp-vip'); ?></td>
						</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Role name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Necessary valid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="30%"><?php _e('Total users', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</tfoot>
		</table>

		<div class="tablenav">
			<div class="alignleft actions">
				<select name="action">
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
			$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles where `ID` = '".$_GET['ID']."'");
		?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Edit role', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_name_role" class="label_td"><?php _e('Role name', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_name_role" id="vip_name_role" value="<?php echo $get_result[0]->name; ?>"/>
					<p class="description"><?php _e('Enter the role name.', 'wp-vip'); ?></p>
				</td>
			</tr>
			
			<tr>
				<td><label class="label_td" for="vip_credit_role"><?php _e('Role valid', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_credit_role" id="vip_credit_role" dir="ltr" value="<?php echo $get_result[0]->credit_required; ?>"/>
					<p class="description"><?php _e('Enter the credit for this role.', 'wp-vip'); ?></p>
				</td>
			</tr>
				
			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_edit_role" value="<?php _e('Edit', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } else { ?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Add new role', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_name_role" class="label_td"><?php _e('Role name', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_name_role" id="vip_name_role"/>
					<p class="description"><?php _e('Enter the role name.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_credit_role"><?php _e('Role valid', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_credit_role" id="vip_credit_role" dir="ltr"/>
					<p class="description"><?php _e('Enter the credit for this role.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_add_role" value="<?php _e('Add', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } ?>
</div>