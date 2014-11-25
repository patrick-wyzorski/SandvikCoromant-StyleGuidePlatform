<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<!-- Modal: Search -->
	<div class="modal fade" id="search" role="dialog" aria-hidden="true"></div>

	<!-- Modal: Menu -->
	<div class="modal fade" id="menu" role="dialog" aria-hidden="true">
		
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="logo"><a href="/" class="logotype" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Our Brand</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Toolbox</h1>
				</div>
			</div>

			<div class="row">
				<div class="col-md-4">
					<h1>Activities</h1>
				</div>
			</div>

				<?php
				// if (DEBUG) {
				// 	 $categories = array(
				// 		 'Our Brand' => 1,
				// 		 'Toolbox' => 2,
				// 		 'Activities' => 3,
				// 	 );

				// 	foreach($categories as $category_name => $category_id) {
				// 		 $selected = "$category_id-$category";
				// 		 if ($category_id == $category) {
				// 			 $selected = 'active';
				// 		 }
				// 		 echo sprintf('<div class="col-md-2" style="padding-top:70px;"><a href="?category=%d" class="%s">%s</a></div>', $category_id, $selected, $category_name);
				// 	}
				// }
				?>
				
			</div>
		</div>
	

	</div>

	<header id="header" class="site-header headroom" role="banner">
		<div class="container">
			<div class="row">
				<div class="col-md-2">
					<div class="logo"><a href="/" class="logotype" rel="home" title="<?php bloginfo( 'name' ); ?>"><?php bloginfo( 'name' ); ?></a></div>
				</div>
				<div class="col-md-2"></div>
				<div class="col-md-2"><a href="/" title="Search" data-toggle="modal" data-target="#search"><i class="icon icon_search-icon"></i></a></div>
				<div class="col-md-2"><a href="<?php echo esc_url( get_permalink( get_page_by_title( 'Info' ) ) ); ?>" title="Info"><i class="icon icon_info-icon"></i></a></div>
				<div class="col-md-2"><a href="/" title="Menu" data-toggle="modal" data-target="#menu"><i class="icon icon_menu-icon"></i></a></div>

<?php
// if (DEBUG) {
//		$categories = array(
//			'Our Brand' => 1,
//			'Toolbox' => 2,
//			'Activities' => 3,
//		);

	// @FIXME -- DEBUGGING MENU
	// echo '<div id="debug-bar" style="position:absolute;top:200px;z-index:100;right:0;width:100%;text-align:center;">';
	// echo '<h3><ul id="debug-menu">';
	// foreach($categories as $category_name => $category_id) {
	//	 $selected = "$category_id-$category";
	//	 if ($category_id == $category) {
	//		 $selected = 'active';
	//	 }
	//	 echo sprintf('<div class="col-md-2" style="padding-top:70px;"><a href="?category=%d" class="%s">%s</a></div>', $category_id, $selected, $category_name);
	// }
	// echo '</ul></h3></div><style type="text/css">.active{font-weight:700;text-decoration:underline;}</style>';
	// END DEBUGGING
// }
?>
				
			</div>
		</div>
	</header>

	<div id="page" class="hfeed site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'twentyfifteen' ); ?></a>
		<div id="content" class="site-content">