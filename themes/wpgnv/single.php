
<?php get_header(); ?>
    <section class="hero-section box-shadow-5">
        <div class="container">
            <div class="eight columns alpha left">
                <div class="text">
                    SUGGEST.
                    PARTICIPATE.
                    LEARN.
                </div><!-- end .text -->
            </div><!-- end .left -->
      
            <div class="eight columns omega right">
                <p>Below you will find suggestions from our group members.  Please take some time to look through them and vote on the next MeetUp's topic.  You can also suggest a new topic by filling out the form at the bottom of the page.</p>
            <?php get_search_form(); ?>
            </div> 
        </div>
    </section><!-- end .hero-section -->

    <div class="container">
        <div  class="one columns offset-by-fifteen alpha omega">
            <div id="open-close" class="box-shadow-5">
                <span>Close</span>
            </div><!-- end #open-close -->
        </div><!-- .columns -->
    </div><!-- end .container -->
    <section class="main-content">
        <div class="container">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <?php echo wpgnv_create_ideas_display(); ?> 
        <?php endwhile; else: ?>
            <p>Sorry, there were no ideas to display.</p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
 
        </div><!-- end .container -->
    </section><!-- end .main-content -->
<?php get_footer(); ?>
