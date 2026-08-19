<?php global $options;?>

<style type="text/css">
/* Colors */
<?php
if($options['pattern']!='nopattern' and $options['pattern']!='') $pattern = get_bloginfo('template_directory').'/images/patterns/'.$options['pattern'].'.png';
else $pattern = '';

?>
body { <?php if($options['bg_color']!=''){?>background-color: #<?php echo $options['bg_color']; ?>; <?php } ?> <?php if($pattern!=''){?>background-image: url(<?php echo $pattern;?>);background-position:center center; background-repeat:repeat; background-attachment: scroll <?php } ?> }
a{<?php if (!empty($options['link_color'])) { echo 'color: #' . $options['link_color']; } ?>}

/* Fonts */
body{<?php if ($options['content_font']!='') { echo 'font-family: ' . $options['content_font']; } ?>}
h1, h2, h3, h4, h5, h6 ,ul.tabs li a{ <?php if ($options['titles_font']!='') { echo 'font-family: ' . $options['titles_font']; } ?>;}
.sf-menu > li > a {<?php if ($options['nav_menu_font']!='') { echo 'font-family: ' . $options['nav_menu_font']; } ?>}
<?php if($options['newsletter_color']!=''){?>
.subcribe_widget{background-color: #<?php echo $options['newsletter_color'];?>}
<?php } ?>
</style>