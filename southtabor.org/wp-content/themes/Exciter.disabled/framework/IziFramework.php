<?php
/*
 * IziTheme.php
 * IziPanel theme framework by IziThemes.com
*/

class IziFramework {
	var $theme_name;
	var $options;
	
	public function __construct($theme_name = 'Theme Options')
	{

		// Set theme name for menus and other stuff which will require a name
		$this->theme_name = $theme_name;
		
		// Set theme options name 
		$this->option_name = str_replace(' ', '_', strtolower($theme_name)).'_options';
		
		// Add default options
		$this->default_options();

		// Get options
		$data = unserialize(base64_decode(get_option($this->option_name)));
		if(is_array($data) && count($data) >= 1){
			foreach($data as $name => $value){
				$data[$name] = stripslashes($value);	
			}
		$this->options = $data;	
		}
		
		
		add_action('init', array($this, 'init'));
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('wp_ajax_izi_upload', array($this, 'upload_files'));
		add_action('wp_ajax_izi_save_fields', array($this, 'save_fields'));
		add_action('wp_ajax_izi_reset_fields', array($this, 'reset_fields'));
	}
		
	// Set default options when theme's loaded
	public function default_options()
	{
		global $default_options;
		add_option($this->option_name, base64_encode(serialize($default_options)));
	}
	
	public function init()
	{
		// Register navigation menus
		register_nav_menu('topNav', __('Top Navigation')); 
		register_nav_menu('mainNav', __('Main Navigation'));
	}
	
	// Register theme options panel admin menu
	public function admin_menu()
	{
		$izi_icon = get_bloginfo('template_directory'). '/framework/theme_options/images/izi_icon.png';
		$object = add_object_page('Theme Options', $this->theme_name.' settings', 'manage_options', 'izi_framework', array($this, 'options_panel'), $izi_icon);
		add_action('admin_print_styles-'.$object, array($this, 'admin_scripts'));
	}
	
	
	public function admin_scripts()
	{
		wp_enqueue_style($this->theme_name, get_bloginfo('template_directory').'/framework/theme_options/style.css','','1');
		wp_enqueue_style($this->theme_name.'-uniform', get_bloginfo('template_directory').'/framework/theme_options/uniform.css','','1');

		wp_enqueue_script('jquery');
		wp_enqueue_script('ajaxupload', get_bloginfo('template_directory').'/framework/theme_options/js/ajaxupload.js');
		wp_enqueue_script('color-picker', get_bloginfo('template_directory').'/framework/theme_options/js/colorpicker.js');
		wp_enqueue_script('uniform', get_bloginfo('template_directory').'/framework/theme_options/js/jquery.uniform.min.js');
	}
	
	// All categoties to array
	public function cats_to_select($post_count = NULL)
	{
		$categories = get_categories('hide_empty=0'); 
		foreach ($categories as $cat) {
			if($post_count=='1'){
				if($cat->category_count == '0') {
					$posts_title = 'No posts!';
				} elseif($cat->category_count == '1') {
					$posts_title = '1 post';
				} else {
					$posts_title = $cat->category_count . ' posts';
				}
				
				$categories_array[$cat->cat_ID] =  $cat->cat_name . ' ( ' . $posts_title . ' )';
			} else {
				$categories_array[$cat->cat_ID] = $cat->cat_name;
			}
		  }
		return $categories_array;
	}
	
	// Create IziPanel's interface
	public function options_panel()
	{
		include_once('theme_options/index.php');
	}

	public function upload_files()
	{
		$clickedID = $_POST['data'];
		$filename = $_FILES[$clickedID];
       	$filename['name'] = preg_replace('/[^a-zA-Z0-9._\-]/', '', $filename['name']); 
		
		$override['test_form'] = false;
		$override['action'] = 'wp_handle_upload';    
		$uploaded_file = wp_handle_upload($filename,$override);
		$upload_tracking[] = $clickedID;
		$data[$clickedID] = $uploaded_file['url'];
		
		$diff = array_diff($this->options, $data);
		$diff2 = array_diff($data, $this->options);
		$diff = array_merge($diff, $diff2);
		
		if(!empty($diff)){
			$data1 = array_merge($this->options,$data);
			update_option($this->option_name, base64_encode(serialize($data1)));
		}
		
		if(!empty($uploaded_file['error'])) {
			echo 'Upload Error: ' . $uploaded_file['error'];
		}	
		else {
			echo $uploaded_file['url'];
		}
		
		die();
	}
	public function save_fields()
	{	
		$data = $_POST;
		unset($data['action']);
		update_option($this->option_name, base64_encode(serialize($data)));
	}
	

	public function reset_fields()
	{
		global $default_options;
		update_option($this->option_name, base64_encode(serialize($default_options)));
	}


	/*  Theme Options
	/***************************************************************************/	
	
	public function upload($id, $label, $desc)
	{ ?>
	<div class='field upload'>
		<label for='<?php echo $id; ?>_label'><?php echo $label; ?></label>
		<div class='input'>
			<input class='upload' name='<?php echo $id; ?>' id='<?php echo $id; ?>_label' type='text' value='<?php echo $this->options[$id]; ?>' />
			<div id='<?php echo $id; ?>' class='upload_button'>Upload</div>
			
			<?php if($desc): ?>
			<p class='desc'><?php echo $desc; ?></p>
			<?php endif; ?>
		</div>
	</div>
<?php
	}
	
	public function textarea($id, $label, $desc)
	{ ?>
		<div class='field textarea'>
			<label for='<?php echo $id; ?>'><?php echo $label; ?></label>
			<div class='input'>
				<textarea name='<?php echo $id; ?>' id='<?php echo $id; ?>'><?php echo $this->options[$id]; ?></textarea>
			<?php if($desc): ?>
			<p class='desc'><?php echo $desc; ?></p>
			<?php endif; ?>
			</div>
		</div>
	<?php }
	
	public function text($id, $label, $desc)
	{ ?>
		<div class='field text'>
			<label for='<?php echo $id; ?>'><?php echo $label; ?></label>
			<div class='input'>
				<input name='<?php echo $id; ?>' type='text' id='<?php echo $id; ?>' value='<?php echo $this->options[$id]; ?>' />
			<?php if($desc): ?>
			<p class='desc'><?php echo $desc; ?></p>
			<?php endif; ?>
			</div>
		</div>
	<?php }
	
	public function checkbox($id, $label)
	{ ?>
		<?php
		if($this->options[$id] == 'On') {
			$checked = 'checked="checked"';
		}
		?>
		<div class='field checkbox'>
			<div class='input'><label for='<?php echo $id; ?>'>
				<input name='<?php echo $id; ?>' type='hidden' value='Off' />
				<input <?php echo $checked; ?> name='<?php echo $id; ?>' id='<?php echo $id; ?>' type='checkbox' value='On' /><?php echo $label; ?></label>
			</div>
		</div>
	<?php }
	
	public function select($id, $opts = array(), $label = '', $desc = '')
	{ ?>
		<div class='field select'>
			<label for='<?php echo $id; ?>'><?php echo $label; ?></label>
			<div class='input'>
				<select name='<?php echo $id; ?>' id='<?php echo $id; ?>'>
					<?php 
					foreach($opts as $key => $value): ?>
					<?php
					if($this->options[$id] == $key) {
						$selected = 'selected="selected"';
					} else {
						$selected = '';
					}
					?>
					<option <?php echo $selected; ?> value='<?php echo $key; ?>'><?php echo $value; ?></option>
					<?php endforeach; ?>
				</select>
			<?php if($desc): ?>
			<p class='desc'><br /><?php echo $desc; ?></p>
			<?php endif; ?>
			</div>
		</div>
	<?php }
	
	public function radio($id, $opts = array(), $title = '', $desc = '')
	{ ?>
		<div class='field checkbox'>
			<p style='margin-top: 0;'><strong><?php echo $title; ?></strong></p>
			<div class='input'>
				<?php foreach($opts as $key => $value): ?>
				<?php
				if($this->options[$id] == $key) {
					$checked = 'checked="checked"';
				} else {
					$checked = '';
				}
				?>
				<label><input name="<?php echo $id; ?>" id="<?php echo $id; ?>" type="radio" value="<?php echo $key; ?>" <?php echo $checked; ?> /> <?php echo $value; ?></label>
				<?php endforeach; ?>
				<?php if($desc): ?>
				<div class="clear"></div>
				<p class='desc'><?php echo $desc; ?></p>
				<?php endif; ?>
			</div>	
		</div>
	<?php }
	
	public function checkboxes($ids = array(), $title = '') { ?>
		<div class='field checkboxes'>
			<p style='margin-top: 0;'><strong><?php echo $title; ?></strong></p>
			
			<?php foreach($ids as $id => $label): ?>
				<?php
				if($this->options[$id] == 'On') {
					$checked = 'checked="checked"';
				} else {
					$checked = '';
				}
				?>
				<div class='input'><label for='<?php echo $id; ?>' class="multi"><input name='<?php echo $id; ?>' type='hidden' value='Off' /><input <?php echo $checked; ?> name='<?php echo $id; ?>' id='<?php echo $id; ?>' type='checkbox' value='On' /><?php echo $label; ?></label></div>
			<?php endforeach; ?>
			
		</div>
	<?php }
	
	function get_skin_config($file){
		$fh = fopen($file, 'r');
		$data = fread($fh, filesize($file));
		$data = str_replace(' ','',$data);
		$tempArray = explode("\n",trim(str_replace("\t",'',$data)));
		$array = array();
		foreach($tempArray as $line){
			$tempLine = explode("=", $line);
			$array[$tempLine[0]] = $tempLine[1];
		}
		fclose($fh);
		return $array;
	}
	
	public function get_skins($id){
		
		$dir = TEMPLATEPATH.'/skins/';
		if(is_dir($dir)){
			if($handle = opendir($dir)){
				// Grab Folders
				while (false !== ($dirTwo = readdir($handle))){
					if($dirTwo != '.' && $dirTwo != '..'){
						// Check For Folder
						if(is_dir(str_replace('//','/',dirname(__FILE__).'/').'../skins/'.$dirTwo)){
							// Open Folders
							if($handleTwo = opendir(str_replace('//','/',dirname(__FILE__).'/').'../skins/'.$dirTwo)){
								// Grab Files
								while(false !== ($file = readdir($handleTwo))){
									$ext = substr(strrchr($file, '.'), 1); // File Extension

									if($file == 'style.css'){
										
										//conf.izi file
										$conf = $this->get_skin_config($dir.$dirTwo.'/conf.izi');
										$dataconf ='';
										foreach($conf as $key => $value){
											$dataconf .= $key.'='.$value;
										}

										if(is_file(str_replace('//','/',dirname(__FILE__).'/').'../skins/'.$dirTwo.'/screenshot.png')){
											$image = get_bloginfo('template_directory').'/skins/'.$dirTwo.'/screenshot.png';
										} else {
											$image = get_bloginfo('template_directory').'/framework/theme_options/images/screenshot-na.png';	
										}
										if($this->options[$id]==$dirTwo) $selected = "selected";
										else  $selected = "";
										$output .=' <img id="'.$dirTwo.'" '.$dataconf.' src="'.$image.'" alt="skin" class="'.$selected.'" /> ';
									}
								}
								closedir($handleTwo);
							}
						}
					}
				}		
				closedir($handle);
			} 
		}
		echo $output;
	?>
	<input type='hidden' name='<?php echo $id; ?>' value='<?php echo $this->options[$id]; ?>' />
	<?php
	}
		
	public function images($id, $images = array(), $label, $desc = '') { ?>
		<div class='field images'>
			<label><?php echo $label; ?></label>
			<div class='input'>
				<?php foreach($images as $name => $image): ?>
				<?php
				if($this->options[$id] == $name) {
					$selected = 'selected';
				} else {
					$selected = '';
				}
				?>
				<img src='<?php echo $image ?>' class='<?php echo $selected; ?>' id='<?php echo $name; ?>' alt='<?php echo ucwords(str_replace('_', ' ', $id)); ?>' title='<?php echo ucwords(str_replace('_', ' ', $id)); ?>' />
				<?php endforeach; ?>
				<input type='hidden' name='<?php echo $id; ?>' value='<?php echo $this->options[$id]; ?>' />
			
			<?php if($desc): ?>
			<p class='desc'><?php echo $desc; ?></p>
			<?php endif; ?>
			</div>
		</div>
	<?php }
	
	public function colorpicker($id, $label, $desc = '') { ?>
		<script type='text/javascript'>
		jQuery(document).ready(function() {
			// Colorpicker
			jQuery('#colorpicker_<?php echo $id; ?> .colorSelector').ColorPicker({
				color: '#<?php echo $this->options[$id]; ?>',
				onShow: function (colpkr) {
					jQuery(colpkr).fadeIn(500);
					return false;
				},
				onHide: function (colpkr) {
					jQuery(colpkr).fadeOut(500);
					return false;
				},
				onChange: function (hsb, hex, rgb) {
					jQuery('#colorpicker_<?php echo $id; ?> .colorSelector div').css('backgroundColor', '#' + hex);
					jQuery('#colorpicker_<?php echo $id; ?> input').val(hex);
				}
			});
		});
		</script>
		<div class='field colorpicker_wrapper' id='colorpicker_<?php echo $id; ?>'>
			<label name='<?php echo $id; ?>'><?php echo $label; ?></label>
			<div class='input'>
				<input type='text' value='<?php echo $this->options[$id]; ?>' name='<?php echo $id; ?>' id='<?php echo $id; ?>' />
				<div class="colorSelector"><div style='background-color: #<?php echo $this->options[$id]; ?>;'></div></div>
			<?php if($desc): ?>
			<p class='desc'><?php echo $desc; ?></p>
			<?php endif; ?>
			</div>
		</div>
	<?php }
}

include (TEMPLATEPATH . '/framework/default-options.php');

$theme_data = get_theme_data( TEMPLATEPATH .'/style.css');
$theme = new IziFramework($theme_data['Name']);
$options = $theme->options;	
?>