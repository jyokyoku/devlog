<?php
use DevLog\View;
?>
<?php get_header() ?>
<main class="l-body">
	<div class="c-container">
		<div class="p-post-archive">
			<div class="p-post-archive__search">
				<form class="p-post-archive__search-form" method="get">
					<input type="text" name="s" class="p-post-archive__search-field" value="<?php echo get_search_query() ?>" placeholder="Search Keyword">
					<button class="p-post-archive__search-btn">
						<svg>
							<use xlink:href="#svg-search"></use>
						</svg>
					</button>
				</form>
			</div>
			<?php
			if ( have_posts() ) {
				?>
				<div class="p-post-list p-post-list--compact">
					<?php
					while ( have_posts() ) {
						the_post();
						View::element( 'post-list-item' );
					}
					?>
				</div>
				<?php
			} else if ( get_search_query() ) {
				?>
				<section class="p-no-result">
					<h2 class="p-no-result__title"><span class="u-point">NO<span class="u-point__bold">RESULTS</span></span></h2>
					<p class="p-no-result__desc">該当する記事が見つかりませんでした。</p>
				</section>
				<?php
			}
			?>
		</div>
		<?php echo View::pager() ?>
	</div>
</main>
<?php get_footer() ?>
