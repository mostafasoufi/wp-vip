<div class="wrap">
	<h2><?php _e('Payments', 'wp-vip'); ?></h2>
	<form action="" method="post">
		<table class="widefat fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Username', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('User name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Date of payment', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Amount paid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Payment type', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Edit', 'wp-vip'); ?></th>
				</tr>
			</thead>

			<tbody>
			<?php
				global $wpdb, $table_prefix;
				$get_result = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_payments");

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
							<td class="column-name"><?php echo $gets->payment_date; ?></td>
							<td class="column-name"><?php echo number_format($gets->payment_price, 0); ?></td>
							<td class="column-name"><?php echo $gets->payment_type; ?></td>
							<td class="column-name"><a href="?page=vip/payments&action=edit&ID=<?php echo $gets->ID; ?>"><?php _e('Edit', 'wp-vip'); ?></a></td>
						</tr>
						<?php
					}
				} else { ?>
						<tr>
							<td colspan="7"><?php _e('Not Found!', 'wp-vip'); ?></td>
						</tr>
				<?php } ?>
			</tbody>

			<tfoot>
				<tr>
					<th id="cb" scope="col" class="manage-column column-cb check-column"><input type="checkbox" name="checkAll" value=""/></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Username', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('User name', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Date of payment', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="20%"><?php _e('Amount paid', 'wp-vip'); ?></th>
					<th scope="col" class="manage-column column-name" width="10%"><?php _e('Payment type', 'wp-vip'); ?></th>
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
			$get_users = $wpdb->get_results("SELECT * FROM {$table_prefix}vip_payments WHERE ID = '".$_GET['ID']."'");
		?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Edit payment', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_user_name" class="label_td"><?php _e('Select user', 'wp-vip'); ?></label></td>
				<td>
					<?php wp_dropdown_users(array('name' => 'vip_user_name', 'selected' => $get_users[0]->username_ID)); ?>
					<p class="description"><?php _e('The user can choose.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_payment_price"><?php _e('Amount paid', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_payment_price" id="vip_payment_price" class="code" value="<?php echo $get_users[0]->payment_price; ?>"/>
					<p class="description"><?php _e('Enter the amount paid', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_payment_type"><?php _e('Payment type', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_payment_type" id="vip_payment_type" value="<?php echo $get_users[0]->payment_type; ?>"/>
					<p class="description"><?php _e('Select the type of payment.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_edit_payment" value="<?php _e('Edit', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } else { ?>

	<form action="" method="post">
		<table>
			<tr><td colspan="2"><h3><?php _e('Add payment', 'wp-vip'); ?></h4></td></tr>
			<tr>
				<td><label for="vip_user_name" class="label_td"><?php _e('Select user', 'wp-vip'); ?></label></td>
				<td>
					<?php wp_dropdown_users(array('name' => 'vip_user_name')); ?>
					<p class="description"><?php _e('The user can choose.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_payment_price"><?php _e('Amount paid', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_payment_price" id="vip_payment_price" class="code"/>
					<p class="description"><?php _e('Enter the amount paid', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td><label class="label_td" for="vip_payment_type"><?php _e('Payment type', 'wp-vip'); ?></label></td>
				<td>
					<input type="text" name="vip_payment_type" id="vip_payment_type"/>
					<p class="description"><?php _e('Select the type of payment.', 'wp-vip'); ?></p>
				</td>
			</tr>

			<tr>
				<td colspan="2"><input type="submit" class="button-primary" name="vip_add_payment" value="<?php _e('Add', 'wp-vip'); ?>" /></td>
			</tr>
		</table>
	</form>

	<?php } ?>
</div>