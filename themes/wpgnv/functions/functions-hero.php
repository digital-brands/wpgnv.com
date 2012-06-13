<?php
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
            <p>Below you will find suggestions from our group members.  Please take some time to look through them and vote on the next MeetUp's topic. You can also add your own topic by clicking on the '<strong>+ Add Idea</strong>' tab.  You can vote up to <strong>three times per day.</strong></p>
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

		<div class="one columns omega">
			<div id="add-idea" class="box-shadow-5">
				<span>+ Add Idea</span>
			</div><!-- end #add-idea -->
		</div><!-- end .columns -->
    </div><!-- end .container -->
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

?>
