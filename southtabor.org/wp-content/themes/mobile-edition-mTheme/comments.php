<?php  if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die ('Please do not load this page directly. Thanks!');
?>
<a name="comments" id="comments"></a>
<?php if ( post_password_required() ) : ?>
 <div class="fdx_comments_container">
 <strong><?php _e( 'This post is password protected. Enter the password to view any comments.', 'fdx-lang' ); ?>  </strong>
 </div>
<?php
return;
		endif;
?>

<?php if ( have_comments() ) : ?>
<div class="rack1"></div>
<div class="fdx_comments_respond">
<?php comments_number('No', '"1" Comment', '"%" Comments');?>

<?php if ( ! comments_open()) : ?> <div style="float: right; margin-top: 5px; margin-right: 5px"><a href="#fdx_reply"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/comment-no.png" width="20" height="20" border="0" alt="" /></a></div>
<?php else: ?>
<div style="float: right; margin-top: 5px; margin-right: 5px"><a href="#fdx_reply"><img src="<?php echo get_template_directory_uri(); ?>/images/icons/comment-yes.png" width="20" height="20" border="0" alt="" /></a></div>
<?php endif; ?>
</div>

<div id="respond">
<div class="fdx_comments_container" style="padding: 0; border: 0">
<div class="fdx_comments" id="comments">
<ol>
<?php wp_list_comments( array( 'callback' => 'fdx_comment' ) );?>
</ol>
</div>





       </div>
      </div>

<?php endif; // have_comments() ?>

<?php if ( ! comments_open()) : ?>
 <div class="rack1"></div>
 <a id="fdx_reply" name="fdx_reply"></a>
   <div class="fdx_comments_respond">	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <div style="float: left" class="navcomen"><?php previous_comments_link( __( '&larr; Older Comments', 'fdx-lang' ) ); ?></div>
			<div style="float: right; margin-right: 10px" class="navcomen"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fdx-lang' ) ); ?></div>
    		<?php endif; // check for comment navigation ?></div>
            <div class="fdx_comments_container">
                 <div align="center"><blockquote><strong><?php _e( 'Comments are closed', 'fdx-lang' ); ?>   </strong></blockquote></div>
            </div>
<?php else: ?>
    <div class="rack1"></div>
 <a id="fdx_reply" name="fdx_reply"></a>

<div class="fdx_comments_respond">	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
            <div style="float: left" class="navcomen"><?php previous_comments_link( __( '&larr; Older Comments', 'fdx-lang' ) ); ?></div>
			<div style="float: right; margin-right: 10px" class="navcomen"><?php next_comments_link( __( 'Newer Comments &rarr;', 'fdx-lang' ) ); ?></div>
    		<?php endif; // check for comment navigation ?></div>

<div class="fdx_comments_container">



<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
<p><?php _e( 'You must be', 'fdx-lang' ); ?> <a href="<?php echo wp_login_url( get_permalink() ); ?>"><strong><?php _e( 'logged in', 'fdx-lang' ); ?></strong></a> <?php _e( 'to post a comment', 'fdx-lang' ); ?>.</p>
<?php else : ?>

<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform" class="form-post">
<?php if ( is_user_logged_in() ) : ?>

<fieldset style="width: 275px"><legend><a href="<?php echo wp_logout_url( get_permalink() ); ?>"><?php _e( 'Logout', 'fdx-lang' ); ?></a></legend> <?php _e( 'Welcome', 'fdx-lang' ); ?>: <a href="<?php echo admin_url('profile.php'); ?>"><?php echo $user_identity; ?></a></fieldset>

<!-- log --><fieldset style="width: 275px"><legend><?php comment_form_title( 'Leave a Comment', 'Leave a Reply to %s' ); ?> <?php cancel_comment_reply_link(); ?></legend>
<?php else : ?>
<!-- no-log --><fieldset style="width: 275px"><legend><?php comment_form_title( 'Leave a Comment', 'Leave a Reply to %s' ); ?> <?php cancel_comment_reply_link(); ?></legend>

<p><?php _e( 'Name', 'fdx-lang' ); ?><?php if ($req) { ?>*<?php } ?>:<br /><input type="text" name="author" id="author" class="fdx_commentform_input" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1"  /> </p>
<p<?php _e( 'Email', 'fdx-lang' ); ?><?php if ($req) { ?>*<?php } ?>:<br /><input type="text" name="email" id="email" class="fdx_commentform_input" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" /></p>
<p><?php _e( 'Website', 'fdx-lang' ); ?>:<br /><input type="text" name="url" id="url" class="fdx_commentform_input" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3"/> </p>

<?php endif; ?>

<?php _e( 'Comment', 'fdx-lang' ); ?>: <br /><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" class="fdx_commentform_textarea"></textarea>
<div align="center" style=" margin-top: 5px">
<input type="submit" id="searchsubmit" id="submit" value="<?php _e( 'Post Comment', 'fdx-lang' ); ?>" tabindex="5" />
</div>
<?php comment_id_fields(); ?>
</fieldset>
</form>

<?php endif; ?>

</div>

 <?php endif; ?>
