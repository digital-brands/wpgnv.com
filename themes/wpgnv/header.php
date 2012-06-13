<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if IE 9]>    <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php wp_title(); ?> <?php bloginfo( 'name' ); ?></title>

	<!-- TypeKit -->
	<script type="text/javascript" src="http://use.typekit.com/ugy8zpa.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" />

	<!-- Viewport -->
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" type="text/css" media="screen" />
    <?php wp_head(); ?>
</head>
<body>
    <!--[if lt IE 9]><div class=chromeframe><h3>Your browser is <strong>ancient!</strong></h3><p><a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p></div><![endif]-->

    <header>
        <section class="menu menu-gradient box-shadow-2">
            <div class="container">
                <div class="row">
                    <a class="seven columns alpha" href="<?php echo get_home_url(); ?>">Gainesville WordPress MeetUp</a>
                    <!-- PROCESS LOGIN
                    ---------------------------------------------------------->
                    <?php 
                        wpgnv_verify_user_login();
                    ?>
					<!-- Visit GitHub
					---------------------------------------------------------->
					<div class="six columns omega offset-by-three">
						<a class="pull-right" href='https://github.com/digital-brands/wpgnv.com'>This site's code can be found on GitHub</a>
					</div>

                    <!-- LOGIN
                    ---------------------------------------------------------->
					<?php 
					//if ( is_user_logged_in() ) {
                    //    global $user_email;
                    //    get_currentuserinfo();
                    //    $profile_url = get_home_url() . '/wp-admin/profile.php'; 
                    //    echo "<div class='seven columns offset-by-two omega user-logged-in'><a href='$profile_url'>$user_email</a></div>";
                    //} else {
					?>
				<!--	
					<div class="nine columns omega user-not-logged-in">
	                    <form action="" method="post" class="login-form">
							<input name="login" placeholder="e-mail" type="email" class="border-radius-30 header-login" />
							<input name="password" placeholder="password" type="password" class="border-radius-30 header-password" />
							<button class="login-button">Login</button>
							<?php //wp_nonce_field( 'user_login','user_login' ); ?> 
		                </form>
					</div> --><!-- end .user-not-logged-in -->
                    <?php //} ?>
                </div><!-- end .row -->
            </div><!-- end .container -->
        </section><!-- end .menu -->
    </header>

<div role="main" class="main">
