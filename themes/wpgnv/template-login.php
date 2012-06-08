<?php
/*
Template Name: Custom Login
*/
?>

<?php get_header(); ?>

<section class="custom-register">
    <div class="container">
        <div class="row">
            <div class="sixteen columns">

                <!-- REGISTRATION FORM -->
                <?php 
                    if ( ! isset( $_POST['registration-form'] ) ) {
                        wpgnv_user_registration_form();
                    } elseif ( wp_verify_nonce( $_POST['registration_form_nonce'], 'registration_form_nonce' ) ) {
                        $user_email = $_POST['user_email'];
                        // Check if email is valid
                        if ( ! is_email( $user_email ) ) {
                            echo "<p class='registration-error'>Your e-mail address is not valid.</p>";
                            wpgnv_user_registration_form();
                        } else {
                            $user_id = email_exists( $user_email ); 
                            if ( ! $user_id ) {
                                $start_time = time();
                                $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
                                echo ( 'Password Time: ' . ( time() - $start_time ) . ' seconds' );
                                $user_id = wp_create_user( $user_email, $random_password, $user_email );
                                echo ( 'Create User Time: ' . ( time() - $start_time ) . ' seconds' );
                                wp_new_user_notification( $user_id, $random_password );  
                                echo ( 'User Notification Time: ' . ( time() - $start_time ) . ' seconds' );
                                echo "<p class='registration-success'>Thank you for registering!</p>";
                            } else {
                                echo "<p classs='registration-error'>User already exists.</p>";
                                wpgnv_user_registration_form();
                            }
                        }
                    } 
                ?>

            </div><!-- end .sixteen columns -->
        </div><!-- end .row -->
    </div><!-- end .container -->
</section><!-- end .custom-login -->

<?php get_footer(); ?>
