<?php
use DevLog\View;
?>
<?php get_header() ?>
<main class="l-body">
	<div class="c-container">
		<div class="p-post-list">
			<?php
			while ( have_posts() ) {
				the_post();
				View::element( 'post-list-item' );
			}
			?>
		</div>
		<?php View::pager() ?>
	</div>
</main>
<?php get_footer() ?>
