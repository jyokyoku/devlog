<?php
use Iwf2b\View;
?>
<?php get_header() ?>
<main class="l-body">
	<?php
	while ( have_posts() ) {
		the_post();
		?>
		<article class="p-post-detail">
			<header class="p-page-header">
				<h1 class="p-page-header__title"><?php the_title() ?></h1>
			</header>
			<div class="p-post-detail__body">
				<div class="c-container">
					<div class="p-post-content c-editable">
						<?php the_content() ?>
					</div>
				</div>
				<?php View::element( 'post-share' ) ?>
			</div>
		</article>
		<?php
		if ( comments_open() || pings_open() || get_comments_number() ) {
			comments_template( '', true );
		}
		?>
		<?php
	}
	?>
</main>
<?php get_footer() ?>
