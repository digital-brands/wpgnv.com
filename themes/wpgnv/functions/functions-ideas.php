<?php
/* REGISTER IDEA
******************************************************************************/
add_action( 'init', 'wpgnv_add_ideas_post_type' );
function wpgnv_add_ideas_post_type() {
    $labels = array(
        'name' => 'Ideas',
        'singular_name' => 'Idea',
        'add_new' => 'Add New Idea',
        'add_new_item' => 'Add New Idea',
        'edit_item' => 'Edit Idea',
        'new_item' => 'New Idea',
        'all_items' => 'All Ideas',
        'view_item' => 'View Ideas',
        'search_items' => 'Search Ideas',
        'not_found' =>  'No ideas found',
        'not_found_in_trash' => 'No ideas found in Trash', 
        'parent_item_colon' => '',
        'menu_name' => 'Ideas'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true, 
        'show_in_menu' => true, 
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true, 
        'hierarchical' => false,
        'menu_position' => 2,
        'supports' => array( 'custom-fields', 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );

    register_post_type( 'ideas', $args );
}

/* SAVE IDEAS META ON POST SAVE
******************************************************************************/
add_action( 'save_post', 'wpgnv_save_ideas_meta' );
function wpgnv_save_ideas_meta($post_id) {
    $slug = 'ideas';

    /* check whether anything should be done */
    $_POST += array("{$slug}_edit_nonce" => '');
    if ( $slug != $_POST['post_type'] ) {
        return;
    }
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    $upvotes = intval( get_post_meta( $post_id, 'upvotes', true ) );
    $downvotes = intval( get_post_meta( $post_id, 'downvotes', true ) );

    if ( empty( $upvotes ) ) {
        $upvotes = 0;
        add_post_meta( $post_id, 'upvotes', 0, true );
    }
    
    if ( empty( $downvotes ) ) {
        $downvotes = 0;
        add_post_meta( $post_id, 'downvotes', 0, true );
    }

    $total = $upvotes + $downvotes;
	update_post_meta( $post_id, 'total-vote', $total );

}

/* SET UP AN E-MAIL WHEN A NEW IDEA IS PENDING
******************************************************************************/
add_action( 'save_post', 'wpgnv_mail_on_post' );
function wpgnv_mail_on_post( $post_id ) {
	// Set up an e-mail alerting administrators of new post.
	if ( 'pending' == get_post_status( $post_id ) ) {
		$post_title = get_the_title( $post_id );
		$subject = 'A new Idea has been submitted on wpgnv.com!';
		$message = "Please moderate this Idea as soon as possible.\n\n";
		$message .= "TITLE: $post_title \n\n";	
		$message .= "Link: http://wpgnv.com/wp-admin";
		//$message = get_post_status( $post_id );
		wp_mail( 'ryan@digitalbrands.com', $subject, $message );
		wp_mail( 'admin@wpbeginner.com', $subject, $message );
		wp_mail( 'toby@digitalbrands.com', $subject, $message );
	}
}

/* DISPLAY THE CURRENT IDEAS
 ******************************************************************************/
function wpgnv_display_ideas() {
?>
	<!-- LOOP PREPARATION ( WP QUERY )
	---------------------------------------------------------------------->
	<?php $query_args = array(
		'post_type' => 'ideas',
		'order' => 'DES',
		'meta_key' => 'total-vote',
		'orderby' => 'meta_value_num',
		'posts_per_page' => -1
	);  
	$ideas_query = new WP_Query( $query_args );
	?>

	<!-- THE LOOP
	---------------------------------------------------------------------->
	<?php if ( $ideas_query->have_posts() ) : while ( $ideas_query->have_posts() ) : $ideas_query->the_post(); ?>
		<?php echo wpgnv_create_ideas_display(); ?> 
	<?php endwhile; else: ?>
		<p>Sorry, there were no ideas to display.</p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
	<?php
}

/* CREATE IDEAS DISPLAY
******************************************************************************/
function wpgnv_create_ideas_display() {
    global $post;

    $returner = "<div class='row'>";
    $returner .= "<div class='idea sixteen columns alpha omega border-radius-4 box-shadow-5-light'>";

    $returner .= "<div class='three columns alpha'><div class='score'>";
    $returner .= "<span class='info'>SCORE</span>";
    $upvotes = intval( get_post_meta( $post->ID, 'upvotes', true ) );
    $downvotes = intval( get_post_meta( $post->ID, 'downvotes', true ) );
    $total = $upvotes - $downvotes;
    update_post_meta( $post->id, 'total-vote', $total, true );

    if ( (int) $total > 0 ) {
        $votes_class = "positive";
    } elseif ( (int)$total < 0 ) {
        $votes_class = "negative";
    } else {
        $votes_class = "neutral";
    }

    $returner .= "<span class='total  $votes_class' id='total-$post->ID'>$total</span>";
    $returner .= "</div></div><!-- end .score -->";

    $returner .= "<div class='eleven columns'><div class='title'>";
    $returner .= "<span class='info'>IDEA</span>";
    $returner .= '<span class="text">' . get_the_title() . '</span>';
    $returner .= "</div></div><!-- end .title -->";

	//if ( is_user_logged_in() ) {
	$returner .= "<div class='one columns omega'><div class='vote'>";
		$returner .= "<div id='upvote' postID='$post->ID'>";
		$returner .= "</div><!-- end #upvote -->";
		$returner .= "<div id='downvote' postID='$post->ID'>";
		$returner .= "</div>";
		$returner .= "</div></div><!-- end .vote -->";
	//} else {
	//	$returner .= "<div style='padding:5px;'>Login or Register to Vote</div>";
	//}

    $returner .= "</div><!-- end .idea -->";
    $returner .= "</div><!-- end .row -->";

    return $returner;
}

/* DISPLAY THE IDEA FORM
 *******************************************************************************/
function wpgnv_display_form() {
?>
	<div style='display: none;' class='row idea-form-row'>
		<div class='idea sixteen columns alpha omega border-radius-4 box-shadow-5-light'>
			<form method="post" action="" class='idea-form'>
				<h1 class='tk-nimbus-sans-condensed'>Add your own idea!</h1>
				<p>First, make sure that none of the ideas already submitted contain your idea.  You can just fill out the form below to enter your own idea for a MeetUp topic.  These topics will be moderated and then added to the list.</p>
				<label for="title">TITLE</label>
				<textarea placeholder="enter your idea here" type="text" name="post-title"></textarea>
				<?php wp_nonce_field( 'wpgnv_new_idea', 'wpgnv_new_idea' ); ?>
				<button type="submit">Submit Your Idea</button>
			</form><!-- end .idea-form -->
		</div><!-- end .sixteen -->
	</div>
<?php
}

/* PROCESS THE NEW IDEA FORM 
*******************************************************************************/
function wpgnv_process_form() {
	if ( empty( $_POST['post-title'] ) && wp_verify_nonce( $_POST['wpgnv_new_idea'], 'wpgnv_new_idea' ) ) {
		global $wpgnv_error;
		$wpgnv_error = 'You left the Idea field empty. Please enter an Idea and THEN submit it.';
		return;
	}
	if ( isset( $_POST ) && wp_verify_nonce( $_POST['wpgnv_new_idea'], 'wpgnv_new_idea' ) ) {
		$post_args = array (
			'post_type' => 'ideas',
			'post_status' => 'pending',
			'post_title' => esc_html( $_POST['post-title'] )
		);
		$post_id = wp_insert_post( $post_args );

		if ( is_wp_error( $post_id ) ) {
			global $wpgnv_error;
			$wpgnv_error = 'Sorry, there was an error processing your new idea.';
		} else {
			global $wpgnv_success;
			$wpgnv_success = 'Awesome!  Your idea has been submitted and is awaiting moderation.';
		}
	}	
}

?>
