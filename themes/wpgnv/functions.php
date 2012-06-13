<?php
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
 
/* REMOVE FRONT-END ADMIN BAR 
******************************************************************************/
show_admin_bar(false);

/* ADD IN THE IDEAS SUBFUNCTIONS ( Registration, Form, Loop, etc. )
******************************************************************************/
include_once( get_stylesheet_directory() . '/functions/functions-ideas.php' );

/* ADD IN THE USER SUBFUNCTIONS ( Registration, Form, etc. )
******************************************************************************/
include_once( get_stylesheet_directory() . '/functions/functions-user.php' );

/* ADD IN THE HERO SUBFUNCTIONS
******************************************************************************/
include_once( get_stylesheet_directory() . '/functions/functions-hero.php' );

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

?>
