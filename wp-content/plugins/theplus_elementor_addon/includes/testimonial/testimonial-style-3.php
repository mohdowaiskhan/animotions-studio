<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $tlContentFrom == 'tlcontent' ) {
	$postid = get_the_ID();?>
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
										<?php
}
?>
<div class="testimonial-list-content">
	<div class="testimonial-content-text">
	<?php
		require THEPLUS_INCLUDES_URL . 'testimonial/post-meta-title.php';
		require THEPLUS_INCLUDES_URL . 'testimonial/get-excerpt.php';
	?>
	</div>
	<div class="post-content-image">
		<?php require THEPLUS_INCLUDES_URL . 'testimonial/format-image.php'; ?>
		<div class="author-left-text">
		<?php
			require THEPLUS_INCLUDES_URL . 'testimonial/post-title.php';
			require THEPLUS_INCLUDES_URL . 'testimonial/post-meta-designation.php';
		?>
		</div>
	</div>		
</div>
<?php
if ( $tlContentFrom == 'tlcontent' ) {
	?>
	</article><?php
} ?>