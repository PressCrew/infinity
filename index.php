<?php
	infinity_get_header();
?>
	<div id="content" role="main">
			<?php
			do_action( 'open_content' );
			do_action( 'open_home' );
		?>
		<div id="home-page" role="main" <?php post_class(); ?>>
			<?php
				infinity_get_template_part( 'templates/parts/introduction-boxes', 'index' );
				infinity_get_template_part( 'templates/loops/loop', 'index' );
			?>
		</div>
		<?php
			do_action( 'close_home' );
			do_action( 'close_content' );
		?>
	</div>
<?php
	infinity_get_sidebar();
	infinity_get_footer();
?>
