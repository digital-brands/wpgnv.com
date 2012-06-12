<?php
/* DISPLAY THE FORM SUBMISSION
 *******************************************************************************/
function wpgnv_display_form() {
?>
	<div class='row'>
		<div class='idea sixteen columns alpha omega border-radius-4 box-shadow-5-light'>
			<form method="post" action="" class='idea-form'>
				<h1 class='tk-nimbus-sans-condensed'>Add your own idea!</h1>
				<p>Just fill out the form below to enter your own idea for a MeetUp topic.  These topics will be moderated and then added to the list.</p>
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
		'orderby' => 'meta_value_num'
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

/* INSTRUCTIONS SECTION
 ******************************************************************************/
function wpgnv_instruction() {
	?>
	<section class="instructions">
		<h2>Instructions for Using this Site</h2>
		<p>Hi there.</p>	
	</section>
	<?php
}

/* SUCCESS/ERROR PROCESSING
 ******************************************************************************/
function wpgnv_success_error_processing() {
	global $wpgnv_error;
	global $wpgnv_success;
	
	$sucess_error_flag = false;
	if ( isset ( $wpgnv_error ) && !empty( $wpgnv_error ) ) {
		$success_error_flag = true;
		$success_error_class = 'error';
	} 

	if ( isset ( $wpgnv_success ) && !empty( $wpgnv_success ) ) {
		$success_error_flag = true;
		$success_error_class = 'success';
	}

	if ( $success_error_flag ) {
?>
		<section class="<?php echo $success_error_class; ?>">
			<div class="container">
				<div class="row">
					<div class="sixteen columns">
						<?php
							if ( $success_error_class == 'success' ) {
								echo "<span>$wpgnv_success</span>";
							} else {
								echo "<span>$wpgnv_error</span>";
							}
						?> 
					</div><!-- end .columns -->
				</div><!-- end .row -->
			</div><!-- end .container -->
		</section><!-- end .error -->
<?php
		unset( $wpgnv_error );
	}
}

/* REMOVE FRONT-END ADMIN BAR 
******************************************************************************/
show_admin_bar(false);

/* USER REGISTRATION FORM 
******************************************************************************/
function wpgnv_user_registration_form() {
?>
    <div class="registration-form">
        <form method="post" action="" class="wp-user-form">
            <input placeholder="enter your email here" class="main-search border-radius-30 five columns alpha" type="email" name="user_email" value="<?php echo esc_attr(stripslashes($user_email)); ?>" size="25" id="user_email" tabindex="102" />
            <button type="submit" name="user-submit" value="Sign Up!" class="user-submit"><span>Sign Up!</span></button>
            <input type="hidden" name="registration-form" value="true" />
            <br class="clear" />
            <?php wp_nonce_field( 'registration_form_nonce','registration_form_nonce' ); ?> 
        </form>
    </div><!-- end .registration-form -->
<?php
}

/* VERIFY AND CREATE USER
******************************************************************************/
function wpgnv_verify_and_create_user() {
	global $wpgnv_error;
	global $wpgnv_success;
    if ( wp_verify_nonce( $_POST['registration_form_nonce'], 'registration_form_nonce' ) ) {
        $user_email = $_POST['user_email'];
        // Check if email is valid
		if ( ! is_email( $user_email ) ) {
			$wpgnv_error = 'Your e-mail address is not properly formatted.  Please try again.';
            //echo "<p class='error'>Your e-mail address is not properly formatted.  Please try again.</p>";
        } else {
            $user_id = email_exists( $user_email ); 
            if ( ! $user_id ) {
                $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                $user_id = wp_create_user( $user_email, $random_password, $user_email );
				wp_new_user_notification( $user_id, $random_password ); 
				$wpgnv_success = '<h2>Thank you for registering.</h2><p>You will receive an e-mail shortly containing your temporary password.  one you login at the top of the page you will be able to change your password by clicking on your username.  Enjoy.</p>';	
                //echo "<p class='registration-success'>Thank you for registering!</p>";
			} else {
				$recover_password_link = get_home_url() . '/wp-login.php?action=lostpassword';
				$wpgnv_error = 'That user (e-mail) already exists.  You can login at the top of the screen or <a href="' . $recover_password_link  . '">recover your password.</a>';
                //echo "<p classs='error'>ser already exists.</p>";
            }
        }
    } 
}

/* USER LOGIN
******************************************************************************/
function wpgnv_verify_user_login() {
    // Verify nonce
    if ( ! wp_verify_nonce( $_POST['user_login'], 'user_login' ) ) 
        return;

    // Verify user login info
    if ( ! isset( $_POST['login'] ) && ! isset( $_POST['password'] ) )
        return;
    
    // Log user in
    $creds['user_login'] = $_POST['login'];
    $creds['user_password'] = $_POST['password'];
    $creds['remember'] = true;
    $user = wp_signon( $creds, false );
	if ( is_wp_error($user) ) {
		global $wpgnv_error;
		$wpgnv_error = $user->get_error_message();
	}

	wp_redirect( get_home_url() );
	exit;
}

/* ENQUEUES 
******************************************************************************/
add_action( 'wp_enqueue_scripts', 'wpgnv_enqueue' );
function wpgnv_enqueue() {
    wp_enqueue_script( 'jquery' );

    wp_register_script( 'wpgnv-js', get_stylesheet_directory_uri() . '/js.js', 'jquery', '', true );
    wp_enqueue_script( 'wpgnv-js' );

    // declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
    wp_localize_script( 'wpgnv-js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}
 
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
        'menu_position' => 5,
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

/* GENERATE HERO SECTION
******************************************************************************/
function wpgnv_generate_hero_section() {
?>
    <div class="container">
        <div class="eight columns alpha left">
            <div class="text tk-nimbus-sans-condensed">
                <h1>SUGGEST.</h1>
                <h1>PARTICIPATE.</h1>
                <h1>LEARN.</h1>
            </div><!-- end .text -->
        </div><!-- end .left -->
  
        <div class="eight columns omega right">
            <p>Below you will find suggestions from our group members.  Please take some time to look through them and vote on the next MeetUp's topic. You can also add your own topic idea by filling out the form at the bottom of the page.</p>
        <?php get_search_form(); ?>
        </div><!-- end .columns --> 
    </div><!-- end .container -->
<?php
}

/* GENERATE HERO REGISTRATION
******************************************************************************/
function wpgnv_generate_hero_registration() {
?>
    <div class="container">
        <div class="eight columns alpha left">
            <div class="text tk-nimbus-sans-condensed">
                <h1>SUGGEST.</h1>
                <h1>PARTICIPATE.</h1>
                <h1>LEARN.</h1>
            </div><!-- end .text -->
        </div><!-- end .left -->

        <div class="eight columns omega right">
            <p>Below you will find suggestions from our group members.  To vote on ideas or suggest new ones you have to register for the site.  All you have to do is enter your e-mail below.</p> 
        <?php wpgnv_user_registration_form(); ?>
		<?php wpgnv_verify_and_create_user(); ?>
		<?php //wpgnv_insert_icons(); ?>
        </div><!-- end .columns -->
    </div><!-- end .container -->
<?php
}

/* ICONS INSERT
******************************************************************************/
function wpgnv_insert_icons() {
	?>
	<div class="row">
		<div class="two columns alpha">
			<div id="mobile-enabled">
				<div class="icon"></div>
				<span>MOBILE</span>
				<span>ENABLED</span>
			</div><!-- end #mobile-enabled -->
		</div><!-- end .columns -->

		<div class="four columns omega">
			<div id="html5-enabled">
			</div><!-- end #html5-enabled -->
		</div>
	</div><!-- end .row -->
	<?php
}

/* GENERATE OPEN/CLOSE
******************************************************************************/
function wpgnv_generate_open_close() {
?>
    <div class="container">
        <div  class="one columns omega">
            <div id="open-close" class="box-shadow-5">
                <span>Close</span>
            </div><!-- end #open-close -->
        </div><!-- .columns -->
    </div><!-- end .container -->
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

/* VOTING AJAX PROCESSING
******************************************************************************/
add_action( 'wp_ajax_nopriv_wpgnv_upvote', 'wpgnv_upvote' );
add_action( 'wp_ajax_wpgnv_upvote', 'wpgnv_upvote' );
function wpgnv_upvote() {
    
     // get the submitted parameters
    $postID = $_POST['postID'];
    $value = $_POST['value'];

    // get and set the new upvotes count
    $upvotes = intval( get_post_meta( $postID, 'upvotes', true ) );

    // get the downvote count
    $downvotes = intval( get_post_meta( $postID, 'downvotes', true ) );
    
    // update the results
    if ( $value > 0 ) {    
        $upvotes = $upvotes + 1;
        update_post_meta( $postID, 'upvotes', $upvotes );
    } elseif ( $value < 0) {
        $downvotes = $downvotes + 1;
        update_post_meta( $postID, 'downvotes', $downvotes );
    }
    // calcultate the new total
    $total = $upvotes - $downvotes;

    // save the total
    update_post_meta( $postID, 'total-vote', $total );
    echo $total;
    exit;
}

function wpgnv_downvote() {
    echo 'Downvote!';
}
?>
