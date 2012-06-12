<?php get_header(); ?>

    <!-- HERO SECTION
    -------------------------------------------------------------------------->
    <section class="hero-section box-shadow-2">
        <?php
            //if ( is_user_logged_in() ) {  
                wpgnv_generate_hero_section(); 
           // } else {
             //   wpgnv_generate_hero_registration();
            //}
        ?>
	</section><!-- end .hero-section -->

	<!-- SUCCESS/ERROR MESSAGES
	-------------------------------------------------------------------------->
	<?php wpgnv_success_error_processing();	?>

	<!-- OPEN/CLOSE TAB
	-------------------------------------------------------------------------->
    <?php
        //if ( is_user_logged_in() ) {
            wpgnv_generate_open_close();
        //}
    ?>

	<!-- MAIN CONTENT
	-------------------------------------------------------------------------->
    <section class="main-content">
        <div class="container">

		<?php wpgnv_display_ideas(); ?>
 
        </div><!-- end .container -->
    </section><!-- end .main-content -->
<?php get_footer(); ?>
