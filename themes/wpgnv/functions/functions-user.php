<?php
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

?>
