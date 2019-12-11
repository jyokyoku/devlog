<?php
global $post;

use DevLog\Post\Post;
use DevLog\Tax\CategoryTax;
use OzdemirBurak\Iris\Color\Hex;

$category    = Post::get_term( $post, CategoryTax::get_slug() );
$theme_color = '';
$text_color  = '';
$icon        = null;

if ( $category ) {
	$theme_color = CategoryTax::get_meta( $category, 'theme_color' );
	$text_color  = CategoryTax::get_meta( $category, 'text_color' );
	$icon        = CategoryTax::get_meta( $category, 'icon' );
}

if ( $post->text_color ) {
	$text_color = $post->text_color;
}

$thumbnail   = Post::get_thumbnail( $post );
$bg_style    = '';
$data_colors = '';

if ( $thumbnail ) {
	$bg_style = ' style="background: url(' . $thumbnail['src'] . ') no-repeat center / cover"';

} else {
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

$text_style = '';

if ( $text_color ) {
	$text_style = ' style="color: ' . $text_color . ';"';
}

if ( $post->emphasize_row == 1 ) {
	$catch_phrase = '<div class="p-post-list__item-image-text-bold">' . $post->catch_phrase_row_1 . '</div>';
	$catch_phrase .= $post->catch_phrase_row_2;

} else if ( $post->emphasize_row == 2 ) {
	$catch_phrase = $post->catch_phrase_row_1;
	$catch_phrase .= '<div class="p-post-list__item-image-text-bold">' . $post->catch_phrase_row_2 . '</div>';

} else {
	$catch_phrase = $post->catch_phrase_row_1;
	$catch_phrase .= $post->catch_phrase_row_2;
}
?>
<article <?php post_class( 'p-post-list__item' ) ?><?php echo $data_colors ?>>
	<a href="<?php the_permalink() ?>" class="p-post-list__item-image"<?php echo $bg_style ?>>
		<div class="p-post-list__item-image-text"<?php echo $text_style ?>>
			<?php echo $catch_phrase ?>
		</div>
		<?php
		if ( $data_colors ) {
			?>
			<canvas class="p-post-list__item-image-bg" id="gradient-canvas-<?php echo $post->ID ?>"></canvas>
			<?php
		}
		?>
	</a>
	<div class="p-post-list__item-data">
		<h2 class="p-post-list__item-title"><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h2>
		<div class="p-post-list__item-meta">
			<div class="p-post-list__item-meta-date"><?php the_time( 'Y.m.d' ) ?></div>
			<div class="p-post-list__item-meta-author"><?php the_author() ?></div>
		</div>
	</div>
</article>