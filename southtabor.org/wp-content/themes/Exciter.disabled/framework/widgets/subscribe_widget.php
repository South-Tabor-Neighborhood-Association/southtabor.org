<?php
add_action('widgets_init', 'subscribe_load_widgets');

function subscribe_load_widgets()
{
	register_widget('Subscribe_Widget');
}

class Subscribe_Widget extends WP_Widget {
	
	function Subscribe_Widget()
	{
		$widget_ops = array('classname' => 'subcribe_widget', 'description' => '');

		$control_ops = array('id_base' => 'izi_subscribe-widget');

		$this->WP_Widget('izi_subscribe-widget', '&radic; IziThemes: Newsletter', $widget_ops, $control_ops);
	}
	
	function widget($args, $instance)
	{
		global $options;
		extract($args);
		
		$title = $instance['title'];
		$text = $instance['text'];
		$input_text = $instance['input_text'];
		$button_text = $instance['button_text'];
		$feedburner_id = $instance['feedburner_id'];

		echo $before_widget;
		?>
		<!-- BEGIN WIDGET -->
		<?php
		if($title) {
			echo $before_title.$title.$after_title;
		}
		?>
		<div class="text"><?php echo $instance['text']; ?></div>
		<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $instance['feedburner_id']; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
			<input type="text" value="<?php echo $instance['input_text']; ?>" 
				name="email" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" class="input_text" />
			
			<input type="submit" value="<?php echo $instance['button_text']; ?>" name="submit" class="button"  />

			<input type="hidden" value="<?php echo $instance['feedburner_id']; ?>" name="uri" />
			<input type="hidden" name="loc" value="en_US" />
		</form>
		<div class="clear"></div>	
		<!-- END WIDGET -->
		<?php
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		
		$instance['title'] = $new_instance['title'];
		$instance['text'] = $new_instance['text'];
		$instance['input_text'] = $new_instance['input_text'];
		$instance['button_text'] = $new_instance['button_text'];
		$instance['feedburner_id'] = $new_instance['feedburner_id'];
		
		return $instance;
	}

	function form($instance)
	{
		$defaults = array('title' => 'Newsletter', 'text' => 'Get the latest deals, reviews & articles. Tailored for the destinations you choose.', 'input_text' => 'Enter your email', 'button_text' => 'Sign up!', 'feedburner_id' => 'izithemes');
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('text'); ?>">Description:</label>
			<textarea class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $instance['text']; ?></textarea>
			
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('input_text'); ?>">Input Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('input_text'); ?>" name="<?php echo $this->get_field_name('input_text'); ?>" value="<?php echo $instance['input_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('button_text'); ?>">Button Text:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" value="<?php echo $instance['button_text']; ?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('feedburner_id'); ?>">Feedburner ID:</label>
			<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('feedburner_id'); ?>" name="<?php echo $this->get_field_name('feedburner_id'); ?>" value="<?php echo $instance['feedburner_id']; ?>" />
		</p>
		
		
		
		
	<?php }
}

?>