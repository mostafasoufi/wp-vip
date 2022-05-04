<label for="roles"><?php _e('Special users', 'wp-vip'); ?></label>
	<?php
		echo '<select name="vip_post_role" id="roles">';
			echo '<option value="">' . __('All users', 'wp-vip') . '</option>';
			foreach($get_roles as $roles) {
				echo '<option value="'.$roles->ID.'"'.((get_post_meta($post->ID, "post_role", true) == $roles->ID)?'selected="selected"':'').'>'.$roles->name.'</option>';
			}
		echo '</select>';
	?>
<p class="description"><?php _e('Post only role for users is displayed.', 'wp-vip'); ?></p>