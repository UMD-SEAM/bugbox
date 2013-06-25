<p>
	<label for="sk_title">
		<?php _e('Title:', 'sk'); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
	</label>
</p>

<p>
	<label for="sk_num"><?php
		_e('Items per page','sk'); ?>: <input type="text" id="<?php echo $this->get_field_id('items'); ?>" name="<?php echo $this->get_field_name('items'); ?>" style="width: 30px;" value="<?php echo $instance['items']; ?>">
	</label>
</p>

<p>
	<label for="sk_rss">
		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('rss'); ?>" name="<?php echo $this->get_field_name('rss'); ?>"<?php if((int)$instance['rss']) echo " checked"; ?>/> <?php _e('Show RSS feed', 'sk'); ?>
	</label>
</p>

<p>
	<label>
		<?php printf(__('You can <a href="%s">edit</a> the general config settings in <strong>Settings / Schreikasten</strong>.', 'sk'), "options-general.php?page=skconfig"); ?>
	</label>
</p>
