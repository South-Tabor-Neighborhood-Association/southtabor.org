<?php
add_action('widgets_init', 'search_load_widgets');

function search_load_widgets()
{
	register_widget('Search_Widget');
}

class Search_Widget extends WP_Widget {
	
	function Search_Widget()
	{
		$widget_ops = array('classname' => 'search_widget', 'description' => 'A simple and clean search form widget without default widget css styling.');

		$this->WP_Widget('search_widget', '&radic; IziThemes: Search', $widget_ops);
	}
	
	function widget($args, $instance)
	{
		?>
		<div class="widget search_widget" id="<?php echo $this->get_field_id('search'); ?>">
		<form method="get" action="<?php echo home_url(); ?>">
			<input name="s" id="s" value="<?php _e('Search here...','IziThemes');?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;" type="text" class="input_text">
			<input name="submit"  value="Search" type="submit" class="button">
		</form>
		<div class="clear"></div>
		</div>
		<?php
	}
	
}
?>