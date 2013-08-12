
<?php

/*
Template Name:  Custom contact page with map.
*/
get_header(); ?>
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=true"></script>
<script src="/wp-content/themes/twentytwelve-child/js/drawMap.js"></script>
<div id="primary" >
	<?php
	if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<?php
	add_action('save_post', 'add_address_meta');
	/**
	 * Updates all meta fields related to creating the page.
	 * In case field does not exist it will be created. 
	 * @param $post_id: Gets global current post ID from add_ction. 
	 */
	function add_address_meta($post_id){
		$values = array('טופס','כתובת','טלפון','פקס','פרטי כתובת', 'רמת זום' );
		foreach ($values as $field) {
			update_post_meta($post_id, $field, $meta_value);
		}
	}

	?>
	<!-- #content -->

	<div id="content" role="main" style="min-height:786px">
		<div class="pages_right">
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title() ?></h1>
			</header>
			<div class="right_side">
				<?php the_content() ?>
				<h4>מלאו פרטים ונחזור אליכם בהקדם:</h4>
				<div class ="menu_tsur_kesher"> 
					<?php 
					$meta_values = get_post_meta( get_the_ID() );
					echo do_shortcode($meta_values['טופס'][0]); ?> 
				</div>
				<script>
				$('document').ready(function(){
					var input   = "<?php echo $meta_values['כתובת'][0] ?>",
					zLevel      = "<?php echo $meta_values['רמת זום'][0] ?>",
					place       = input.replace(/\s/g, '+'),
					baseURI     = 'http://maps.googleapis.com/maps/api/geocode/json?address=' + place + '&sensor=true',
					imageURL    = '<?php echo(  wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ))[0]); ?>',
					addDetails  = "<?php echo $meta_values['פרטי כתובת'][0] ?>",
					headerImage = '<?php echo get_header_image() ?>';
					drawMap(baseURI,imageURL,addDetails,zLevel,headerImage);
				});
				</script>
				<style>
				.info_window_image{
					width:150px;
					display:block;
				}
				</style>
			</div>
			<!-- <div class="clear"></div> -->
			<div class="left_side">
				<div class="ktovet"><?php echo $meta_values['כתובת'][0] ?><br>
					<span>טלפון:</span><?php echo $meta_values['טלפון'][0] .' '. $meta_values['פקס'][0]; ?>
				</div>
				<style>
				html, body,#map-canvas {
					margin: 0;
					padding: 0;
					height: 500px;
					}</style>
					<div id="map-canvas"></div>
				</div>
			</div>
			<div class="left_form">
				<a href="?p=42#more-42"><img class="left_banner" style = "margin-top:46px"src="http://ereztzcpa.co.il/wp-content/themes/twentytwelve/images/page_banner_pic.jpg" width="182" height="128" alt="חבילת שירותים לעצמאים"></a>
				<div id = "small_footer_menu"><?php wp_nav_menu( array( 'theme_location' => 'third_menu', 'menu_class' => 'nav-menu' ) ); ?></div>
			</div>
		</div>
	<?php endwhile; else: ?>
	<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
</div>
<?php get_footer(); ?>