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
 
        </div><!-- end .container -->
    </section><!-- end .main-content -->
<?php get_footer(); ?>
