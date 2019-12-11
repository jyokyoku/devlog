<?php
use DevLog\Post\Post;
use DevLog\Tax\CategoryTax;
use Iwf2b\Html\Html;
use Iwf2b\Tax\PostTagTax;
use Iwf2b\View;
use OzdemirBurak\Iris\Color\Hex;
?>
<?php get_header() ?>
<main class="l-body">
	<?php
	while ( have_posts() ) {
		the_post();

		$categories  = Post::get_terms( $post, CategoryTax::get_slug() );
		$thumbnail   = Post::get_thumbnail( $post->ID );
		$bg_style    = '';
		$data_colors = '';

		if ( $thumbnail ) {
			$bg_style = ' style="background: url(' . $thumbnail['src'] . ') no-repeat center / cover;"';

		} else {
			if ( $categories ) {
				$category    = reset( $categories );
				$theme_color = CategoryTax::get_meta( $category, 'theme_color' );
			}

			if ( $post->theme_color ) {
				$theme_color = $post->theme_color;
			}

			if ( $theme_color ) {
				try {
					$light_color = ( new Hex( $theme_color ) )->lighten( 15 );
					$dark_color  = ( new Hex( $theme_color ) )->lighten( - 15 );
				} catch ( Exception $e ) {
				}

				$data_colors = " data-colors='" . json_encode( [ [ (string) $light_color, $theme_color, (string) $dark_color ] ] ) . "'";
			}
		}
		?>
		<article class="p-post-detail">
			<header class="p-post-header" id="post-header"<?php echo $data_colors ?>>
				<div class="p-post-header__image"<?php echo $bg_style ?>>
					<div class="p-post-header__overlay">
						<?php
						$categories = Post::get_terms( $post, CategoryTax::get_slug() );

						if ( $categories ) {
							?>
							<div class="p-post-header__category">
								<svg>
									<use xlink:href="#svg-folder"></use>
								</svg>
								<?php
								$category_links = [];

								foreach ( $categories as $category ) {
									$category_links[] = Html::tag( 'a', [ 'href' => get_term_link( $category ) ], $category->name );
								}

								if ( $category_links ) {
									echo implode( '', $category_links );
								}
								?>
							</div>
							<?php
						}
						?>
						<h1 class="p-post-header__title"><?php the_title() ?></h1>
						<?php
						if ( $data_colors ) {
							?>
							<canvas class="p-post-header__canvas p-post-header__canvas--desktop" id="gradient-canvas-desktop"></canvas>
							<?php
						}
						?>
					</div>
				</div>
				<div class="p-post-header__meta">
					<?php
					$tags = Post::get_terms( $post, PostTagTax::get_slug() );

					if ( $tags ) {
						?>
						<div class="p-post-header__meta-tag">
							<ul class="c-tag-list">
								<?php
								foreach ( $tags as $tag ) {
									?>
									<li class="c-tag-list__item"><a href="<?php echo get_term_link( $tag ) ?>"><?php echo $tag->name ?></a></li>
									<?php
								}
								?>
							</ul>
						</div>
						<?php
					}
					?>
					<div class="p-post-header__meta-author">
						<div class="p-post-header__meta-author-image">
							<?php echo get_avatar( $post->post_author, 50 ) ?>
						</div>
						<div class="p-post-header__meta-author-data">
							<div class="p-post-header__meta-author-name"><?php the_author() ?></div>
							<div class="p-post-header__meta-date"><?php the_time( 'Y.m.d' ) ?>
								<?php
								if ( get_the_modified_time( 'Ymd' ) != get_the_time( 'Ymd' ) ) {
									?>
									<div class="p-post-header__meta-date-update">
										<svg>
											<use xlink:href="#svg-sync"></use>
										</svg>
										<?php the_modified_time( 'Y.m.d' ) ?>
									</div>
									<?php
								}
								?>
							</div>
						</div>
					</div>
				</div>
				<?php
				if ( $data_colors ) {
					?>
					<canvas class="p-post-header__canvas p-post-header__canvas--mobile" id="gradient-canvas-mobile"></canvas>
					<?php
				}
				?>
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
		<?php View::element( 'related-post' ) ?>
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
