<?php
use DevLog\Post\Post;
use DevLog\View;

$related_post_ids = Post::get_related( $post );

if ( $related_post_ids ) {
	$related_posts = Post::get_posts( [
		'post__in' => $related_post_ids,
	] );
	?>
	<section class="p-related-posts p-post-section c-section">
		<div class="c-container">
			<header class="c-section__header">
				<h2 class="c-section__title"><span class="u-point">RELATED<span class="u-point__bold">ARTICLES</span></span></h2>
			</header>
			<div class="p-post-list p-post-list--compact">
				<?php
				foreach ( $related_posts as $post ) {
					setup_postdata( $post );
					View::element( 'post-list-item' );
				}
				?>
			</div>
		</div>
	</section>
	<?php
}