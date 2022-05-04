<div class="wrap">
	<h2><?php _e('Users', 'wp-vip'); ?></h2>
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Username', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('User name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('User role', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Necessary valid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Total payments', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</thead>

			<tbody>
			<?php
				global $wpdb, $table_prefix;
				$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_users");

				if(count($get_result ) > 0) {
					foreach($get_result as $gets) {
						$i++; ?>
						<tr class="<?php echo $i % 2 == 0 ? 'alternate':'author-self'; ?>" valign="middle" id="link-2">
							<th class="check-column" scope="row"><input type="checkbox" name="column_ID[]" value="<?php echo $gets->ID ; ?>" /></th>
							<td class="username column-username">
								<?php
									$user_info = get_userdata($gets->username_ID);
									echo get_avatar($user_info->user_email, 32);
									echo $user_info->user_login;
								?>
							</td>
							<td class="column-name"><?php $user_info = get_userdata($gets->username_ID); echo $user_info->user_firstname; ?></td>
							<td class="column-name">
								<?php
									$get_role = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles WHERE `ID` = '".$gets->user_role."'");
									echo $get_role[0]->name;
								?>
							</td>
							<td class="column-name">
								<?php echo number_format(vip_get_credit_by_userid($gets->username_ID), 0); ?>
							</td>
							<td class="column-name">
								<?php echo number_format(vip_get_payments_by_userid($gets->username_ID), 0); ?>
							</td>
							<td class="column-name">
								<?php
									if(vip_get_payments_by_userid($gets->username_ID) >= vip_get_credit_by_userid($gets->username_ID)) {
										echo '<img src="'.plugins_url('wp-vip/images/1.png').'" alt="active"/>';
									} else {
										echo '<img src="'.plugins_url('wp-vip/images/0.png').'" alt="deactive"/>';
									}
								?>
							</td>
							<td class="column-name"><a href="?page=vip/users&action=edit&ID=<?php echo $gets->ID; ?>"><?php _e('Edit', 'wp-vip'); ?></a></td>
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
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Username', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('User name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('User role', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Necessary valid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="15%"><?php _e('Total payments', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Status', 'wp-vip'); ?></th>
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
			$get_users = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_users WHERE ID = '".$_GET['ID']."'");
			$get_roles = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");
		?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Edit user', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_user_name" class="label_td"><?php _e('Select user', 'wp-vip'); ?></label></td>
				<td>
					<?php wp_dropdown_users(array('name' => 'vip_user_name', 'selected' => $get_users[0]->username_ID)); ?> 
					<p class="description"><?php _e('Select the desired user.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_user_role"><?php _e('Select role', 'wp-vip'); ?></label></td>
				<td>
					<?php
						echo '<select name="vip_user_role" id="vip_user_role">';
						foreach($get_roles as $roles) {
							echo '<option value="'.$roles->ID.'"'.(($get_users[0]->user_role == $roles->ID)?'selected="selected"':'').'>'.$roles->name.'</option>';
						}
						echo '</select>';
					?>
					<p class="description"><?php _e('Choose this user role.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_edit_user" value="<?php _e('Edit', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } else { ?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Add new user', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_user_name" class="label_td"><?php _e('Select user', 'wp-vip'); ?></label></td>
				<td>
					<?php wp_dropdown_users(array('name' => 'vip_user_name')); ?> 
					<p class="description"><?php _e('Select the desired user.', 'wp-vip'); ?></p>
				</td>
			</tr>
			
			<tr>
				<td><label class="label_td" for="vip_user_role"><?php _e('Select role', 'wp-vip'); ?></label></td>
				<td>
					<?php
						$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_roles");
						
						echo '<select name="vip_user_role" id="vip_user_role">';
						foreach($get_result as $results) {
							echo '<option value="'.$results->ID.'">'.$results->name.'</option>';
						}
						echo '</select>';
					?>
					<p class="description"><?php _e('Choose this user role.', 'wp-vip'); ?></p>
				</td>
			</tr>
				
			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_add_user" value="<?php _e('Add', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } ?>
</div>