<?php
/*
Plugin Name: Simple Author Widget
Plugin URI: http://buffercode.com/simple-author-widget-wordpress-plugin/
Description: Easy way to display the Author profile with four social networking profiles using widget.
Version: 1.0
Author: vinoth06
Author URI: http://buffercode.com/
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Additing Action hook widgets_init
add_action( 'widgets_init', 'buffercode_simple_author_widget'); 

function buffercode_SAW_CSS() {
wp_enqueue_style( 'Buffercode_SAW',plugins_url('css\buffercode-SAW.css',__FILE__) );
}
add_action( 'wp_enqueue_scripts', 'buffercode_SAW_CSS' );

function buffercode_simple_author_widget() {
register_widget( 'buffercode_simple_author_info' );
}

class buffercode_simple_author_info extends WP_Widget {
function buffercode_simple_author_info () {
		$this->WP_Widget('buffercode_simple_author_info', 'Simple Author Widget','Select the category to display');	}

public function form( $instance ) { 
 if ( isset( $instance[ 'buffercode_simple_author_widget_custom_title' ])) {
			$buffercode_simple_author_widget_custom_title = $instance[ 'buffercode_simple_author_widget_custom_title' ];	
		}
else {//Setting Default Values
		$buffercode_simple_author_widget_custom_title = 'Post Author';
}?>
		<p>Custom Name <input class="widefat" name="<?php echo $this->get_field_name( 'buffercode_simple_author_widget_custom_title' ); ?>" type="text" value="<?php echo esc_attr( $buffercode_simple_author_widget_custom_title );?>" /></p>
		
<?php }

function update($new_instance, $old_instance) {
$instance = $old_instance;
$instance['buffercode_simple_author_widget_custom_title'] = ( ! empty( $new_instance['buffercode_simple_author_widget_custom_title'] ) ) ? strip_tags( $new_instance['buffercode_simple_author_widget_custom_title'] ) : '';
return $instance;
}

function widget($args, $instance) {
if(is_single()){
global $wpdb;
extract($args);
echo $before_widget;
$buffercode_simple_author_widget_custom_title = apply_filters( 'widget_title', $instance['buffercode_simple_author_widget_custom_title'] );
if ( !empty( $name ) ) { echo $before_title . $buffercode_simple_author_widget_custom_title .
$after_title; }
$buffercode_saw_author = get_the_author_meta( 'ID' );
$buffercode_saw_author_email = get_the_author_meta('user_email',$buffercode_saw_author);
$buffercode_saw_author_login_id = get_the_author_meta('user_login',$buffercode_saw_author);
$buffercode_saw_author_nickname = get_the_author_meta('nickname',$buffercode_saw_author); 
$buffercode_saw_author_display = get_the_author_meta('display_name',$buffercode_saw_author);

$buffercode_SAW_fb_textbox=get_the_author_meta( 'buffercode_SAW_fb_textbox', $buffercode_saw_author );
$buffercode_SAW_twitter_textbox=get_the_author_meta( 'buffercode_SAW_twitter_textbox', $buffercode_saw_author );
$buffercode_SAW_GP_textbox=get_the_author_meta( 'buffercode_SAW_GP_textbox', $buffercode_saw_author );
$buffercode_SAW_LI_textbox=get_the_author_meta( 'buffercode_SAW_LI_textbox', $buffercode_saw_author );
?>
<!-- Buffercode.com Simple Author Widget -->
<div class="buffercode-wrap">
<div class="buffercode-inner-wrap-name"><b><?php echo strtoupper($buffercode_saw_author_display); ?></b></div>
<div class="buffercode-inner-wrap-img-commet">
<div class="buffercode-image"><!-- Buffercode.com Simple Author Widget -->
<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php the_author_meta( 'display_name' ); ?>">
<?php
echo get_avatar( $buffercode_saw_author_email, '96','',$buffercode_saw_author_login_id); 
?>
</a>
</div>
<div class="buffercode-post-comment">
<div class="buffercode-post">
<?php echo number_format_i18n( get_the_author_posts() ); ?> Posts
</div>
<!-- Buffercode.com Simple Author Widget -->
<div class="buffercode-comments">
<?php 
global $wpdb;

    $count = $wpdb->get_var('
             SELECT COUNT(comment_ID) 
             FROM ' . $wpdb->comments. ' 
             WHERE user_id = "' . $buffercode_saw_author . '"');
    echo $count . ' comments';
?>
</div>
</div>
	</div>
<div class="buffercode-inner-wrap-social clear">

<?php if(!empty($buffercode_SAW_fb_textbox)) {?>
<div class="buffercode-social">
<a href="<?php echo get_the_author_meta( 'buffercode_SAW_fb_textbox', $buffercode_saw_author ); ?>"><img src="<?php  echo plugins_url('image\fb.png',__FILE__)?>" /></a>
</div>
<?php } ?>


<?php if(!empty($buffercode_SAW_twitter_textbox)) {?>
<div class="buffercode-social">
<a href="<?php echo get_the_author_meta( 'buffercode_SAW_twitter_textbox', $buffercode_saw_author ); ?>"><img src="<?php  echo plugins_url('image\twitter.png',__FILE__)?>" /></a>
</div>
<?php } ?>

<?php if(!empty($buffercode_SAW_GP_textbox)) {?>
<div class="buffercode-social">
<a href="<?php echo get_the_author_meta( 'buffercode_SAW_GP_textbox', $buffercode_saw_author ); ?>"><img src="<?php  echo plugins_url('image\gp.png',__FILE__)?>" /></a>
</div>
<?php } ?>


<?php if(!empty($buffercode_SAW_LI_textbox)) {?>
<div class="buffercode-social">
<a href="<?php echo get_the_author_meta( 'buffercode_SAW_LI_textbox', $buffercode_saw_author ); ?>"><img src="<?php  echo plugins_url('image\li.png',__FILE__)?>" /></a>
</div>
<?php } ?>
</div>
</div>
<div class="clear"></div>
<!-- Buffercode.com Simple Author Widget -->
<?php
echo $after_widget;
}
}
}

add_action( 'show_user_profile', 'buffercode_SAW_show_profile' );
add_action( 'edit_user_profile', 'buffercode_SAW_show_profile' );

function buffercode_SAW_show_profile( $user ) { ?>
<!-- Buffercode.com Simple Author Widget -->
	<h3>Simple Author Widget Profile Information</h3>
	<table class="form-table">
	<tr>
	<th><label for="buffercode_SAW_fb_label">Facebook ID</label></th>
	<td>
	<input name="buffercode_SAW_fb_textbox" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'buffercode_SAW_fb_textbox', $user->ID ) ); ?>" /><br>
	<span class="description">Please enter your FB acount URL.<br>
	(eg) http://facebook.com/buffercode
	</span>
	</td>
	</tr>
	<!-- Buffercode.com Simple Author Widget -->
	<tr>
	<th><label for="buffercode_SAW_twitter_label">Twitter</label></th>
	<td>
	<input name="buffercode_SAW_twitter_textbox" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'buffercode_SAW_twitter_textbox', $user->ID ) ); ?>" /><br>
	<span class="description">Please enter your Twitter account.<br>(eg) http://twitter.com/buffercode</span>
	</td>
	</tr>
	<!-- Buffercode.com Simple Author Widget -->
	<tr>
	<th><label for="buffercode_SAW_GP_label">Google Plus</label></th>
	<td>
	<input name="buffercode_SAW_GP_textbox" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'buffercode_SAW_GP_textbox', $user->ID ) ); ?>" /><br>
	<span class="description">Please enter your Google Plus account <br>(eg) https://plus.google.com/+buffercode</span>
	</td>
	</tr>
	<!-- Buffercode.com Simple Author Widget -->
	<tr>
	<th><label for="buffercode_SAW_LI_label">Linked In</label></th>
	<td>
	<input name="buffercode_SAW_LI_textbox" class="regular-text" value="<?php echo esc_attr( get_the_author_meta( 'buffercode_SAW_LI_textbox', $user->ID ) ); ?>" /><br>
	<span class="description">Please enter your Linked In account <br> (eg) http://in.linkedin.com/in/mavinothkumar/</span>
	</td>
	</tr>
</table>
<!-- Buffercode.com Simple Author Widget -->
<?php
}
add_action( 'personal_options_update', 'buffercode_SAW_edit_save_profile' );
add_action( 'edit_user_profile_update', 'buffercode_SAW_edit_save_profile' );

function buffercode_SAW_edit_save_profile( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_user_meta($user_id, 'buffercode_SAW_fb_textbox', $_POST['buffercode_SAW_fb_textbox'] );
	update_user_meta($user_id, 'buffercode_SAW_twitter_textbox', $_POST['buffercode_SAW_twitter_textbox'] );
	update_user_meta($user_id, 'buffercode_SAW_GP_textbox', $_POST['buffercode_SAW_GP_textbox'] );
	update_user_meta($user_id, 'buffercode_SAW_LI_textbox', $_POST['buffercode_SAW_LI_textbox'] );
	
}

?>
