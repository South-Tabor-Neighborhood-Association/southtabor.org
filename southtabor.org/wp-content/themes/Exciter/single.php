<?php get_header(); ?>
		<!-- BEGIN MAIN -->
			<div id="main">
			
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<div class="post-heading">
					<h1><?php the_title(); ?></h1>
					<span class="heading-author"><?php the_author_posts_link(); ?></span> 
					<span class="heading-date"><?php the_time('F j, Y'); ?></span>
					<span class="heading-comments"><?php comments_popup_link(__('No Comments &#187;','IziThemes'), __('1 Comment &#187;','IziThemes'), '% '.__('Comments &#187;','IziThemes')); ?></span>
					<?php edit_post_link(__('Edit this post','IziThemes'),'| <span>','</span>'); ?>
				</div>
				
				<?php 
				if(get_post_meta($post->ID, 'izi_featured_image', true)!='default'){
					if(get_post_meta($post->ID, 'izi_featured_image', true)!='disable'){
						$show_image = true;
					}else{
						$show_image = false;
					}
				}else{
					if($options['posts_featured'] == 'On'){
						$show_image = true;
					}else{
						$show_image = false;
					}
				} 
				?>
				<?php if(has_post_thumbnail() & $show_image==true): ?>
					<div class="post-thumb">
						<?php $fullImage = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
						<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), '612x300'); ?>
						
						<a rel="prettyPhoto" href='<?php echo $fullImage[0]; ?>' title="<?php the_title_attribute(); ?>"><img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>" width='612' height='300' /></a>
						<?php if(get_post_meta($post->ID,'izi_image_desc',true)!=''){?>
						<div class="image_desc"><?php echo get_post_meta($post->ID,'izi_image_desc',true);?></div>
						<?php } ?>
					</div>
				<?php endif; ?>
				
				<div class="post-entry">
					<?php the_content(); ?>
					<?php wp_link_pages(); ?>
					<div class='clear'></div>
				</div>
				
				<?php if(
					$options['twitter'] == 'On' ||
					$options['facebook'] == 'On' ||
					$options['digg'] == 'On' ||
					$options['stumbleupon'] == 'On' ||
					$options['reddit'] == 'On' ||
					$options['tumblr'] == 'On' ||
					$options['email'] == 'On' ||
					$options['google'] == 'On'
				): ?>
				<div class='post-share'>
					<?php if($options['twitter'] == 'On'): ?>
					<div class='twitter-share share-widget'>
						<a href="http://twitter.com/share" class="twitter-share-button" data-text='<?php the_title(); ?>' data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</div>
					<?php endif; ?>
					<?php if($options['facebook'] == 'On'): ?>
					<div class='facebook-share share-widget'>
						<iframe src="http://www.facebook.com/plugins/like.php?app_id=149766198425277&amp;href=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=90" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:50px; height:60px;" allowTransparency="true"></iframe>
					</div>
					<?php endif; ?>
					<?php if($options['digg'] == 'On'): ?>
					<div class='digg-share share-widget'>
						<script type="text/javascript">
						(function() {
						var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
						s.type = 'text/javascript';
						s.async = true;
						s.src = 'http://widgets.digg.com/buttons.js';
						s1.parentNode.insertBefore(s, s1);
						})();
						</script>
						<a class="DiggThisButton DiggMedium" href="http://digg.com/submit?url=<?php echo urlencode(get_permalink($post->ID)); ?>&amp;title=<?php echo urlencode(get_the_title()); ?>"></a>
					</div>
					<?php endif; ?>
					<?php if($options['stumbleupon'] == 'On'): ?>
					<div class='stumbleupon-share share-widget'>
						<script src="http://www.stumbleupon.com/hostedbadge.php?s=5"></script>
					</div>
					<?php endif; ?>
					<?php if($options['reddit'] == 'On'): ?>
					<div class='reddit-share share-widget'>
						<script type="text/javascript" src="http://www.reddit.com/static/button/button2.js"></script>
					</div>
					<?php endif; ?>
					<?php if($options['tumblr'] == 'On'): ?>
					<div class='tumblr-share share-widget'>
						<a href="http://www.tumblr.com/share" title="Share on Tumblr" style="display:inline-block; text-indent:-9999px; overflow:hidden; width:62px; height:20px; background:url('http://platform.tumblr.com/v1/share_2.png') top left no-repeat transparent;">Share on Tumblr</a>
					</div>
					<?php endif; ?>
					<?php if($options['email'] == 'On'): ?>
					<div class='email-share share-widget'>
						<a href="mailto:?subject=<?php the_title(); ?>&amp;body=<?php the_permalink(); ?>"><img src='<?php bloginfo('template_directory'); ?>/images/email-share.png' alt='Email Share' /></a>
					</div>
					<?php endif; ?>
					<?php if($options['google'] == 'On'): ?>
					<div class='google-share share-widget'>
						<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
						<g:plusone size="tall"></g:plusone>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				<div class="post-meta">
					<?php if($options['tags'] == 'On'): ?> 
					<div class="post-meta-tags"><?php the_tags('<strong>' . __("TAGS", 'IziThemes') . '</strong> <span class="meta-arrow">&raquo;</span> ',', '); ?></div>
					<?php endif; ?>
					
					<?php if($options['categories'] == 'On'): ?> 
					<div class="post-meta-cats"><strong><?php _e('POSTED IN', 'IziThemes'); ?></strong> <span class="meta-arrow">&raquo;</span> <?php the_category(', ') ?></div>
					<?php endif; ?>	
				</div>
				
				<?php if($options['author'] == 'On'): ?> 
				<div class="post-author">
					<?php echo get_avatar(get_the_author_meta('email'), '70'); ?>
					<h5><em><?php _e('About the author:', 'IziThemes'); ?></em> <?php the_author_posts_link(); ?> <small><?php _e('View all posts by'); ?> <?php the_author_posts_link(); ?></small></h5>
					<div class="author-descrip"><?php the_author_meta("description"); ?></div>
					<?php if(get_the_author_meta('twitter') || get_the_author_meta('facebook')): ?>
					<p>
						<br />
						<?php if(get_the_author_meta('twitter')): ?>
						<a href='http://twitter.com/<?php echo get_the_author_meta('twitter'); ?>'>Twitter</a>
						<?php endif; ?>
						
						<?php if(get_the_author_meta('twitter') || get_the_author_meta('facebook')): ?>
						-
						<?php endif; ?>
						
						<?php if(get_the_author_meta('facebook')): ?>
						<a href='http://facebook.com/<?php echo get_the_author_meta('facebook'); ?>'>Facebook</a>
						<?php endif; ?>
					</p>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				
				
				
				<?php
				if($options['related'] == 'On' ){
					$this_post = $post->ID; // get ID of current post
					$category = get_the_category($this_post); //get first current category ID
					$related_posts = get_posts('exclude='.$this_post.'&numberposts=4&orderby=rand&category=' . $category[0]->cat_ID . '');
					if($related_posts){ ?>
					
					<div class='homepage-widget'>
						<div class="block large">
							<h3><?php _e('Related Posts','IziThemes');?></h3>
								<?php
								$endRow = 0;
								$columns = 2; // number of columns
								$hloopRow1 = 0; // first row flag
								$count = 1;
								?>
								<div class="related-posts">
								<div class="row">
								<?php foreach( $related_posts as $post ) : setup_postdata($post);?>
									<?php if($endRow == 0  && $hloopRow1++ != 0) echo " <div class=\"row\">"; //?>
									<div class="related-item<?php if($count%$columns==0) echo ' last';?>">
									<?php if(has_post_thumbnail()): ?>
									<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($related_post->ID), '60x60'); ?>
									
									<a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>" width='60' height='60' /></a>		
									<?php else: ?>
									
									<a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><img src="<?php bloginfo('template_directory'); ?>/images/thumb-small.png" alt="<?php the_title_attribute(); ?>" width='60' height='60' /></a>
									<?php endif; ?>
									<h4><a href='<?php the_permalink(); ?>' title='<?php the_title_attribute(); ?>'><?php the_title(); ?></a></h4>
									<span class="block-meta"><?php the_time('F j, Y'); ?></span>
									<p><?php echo text_trim(get_the_excerpt(),25,'...'); ?></p>
									</div>
								<?php  
								$endRow++;			
								if($endRow >= $columns) { ?>
										<div class="clear"></div></div>
								<?php 
								$endRow = 0;
								} //end if
								$count++;
								?>
								<?php endforeach; ?>
								<?php
								if($endRow != 0) {
								while ($endRow < $columns) {
								if($endRow==($columns-1)) echo('<div class="related-item last">&nbsp;</div>');
								else  echo('<div class="related-item">&nbsp;</div>');
								
								$endRow++;
								}
								echo("<div class=\"clear\"></div></div>");
								}?>
								</div><!-- .related-posts -->
						
						</div>
					</div>
				
				<?php 
					} 
				}
				?>
				<?php wp_reset_query(); ?>
				
				<div id="comments">
				
					<?php comments_template(); ?>
					
				</div>
				
				<?php endwhile; endif; ?>
			
			</div>
			<!-- END MAIN -->
			
<?php get_sidebar(); ?>
			
<?php get_footer(); ?>