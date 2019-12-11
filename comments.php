<?php
use DevLog\WalkerComment;

if ( post_password_required() || ( ! comments_open() && empty( $wp_query->comments_by_type['comment'] ) ) ) {
	return;
}
?>
<section class="p-comments p-post-section c-section">
	<div class="c-container c-container--narrow">
		<?php
		if ( ! empty( $wp_query->comments_by_type['comment'] ) ) {
			$comment_count = count( $wp_query->comments_by_type['comment'] );
			$comment_text  = $comment_count > 1 ? 'COMMENTS' : 'COMMENT';
			?>
			<header class="c-section__header">
				<h2 class="c-section__title"><span class="u-point"><span class="u-point__bold"><?php echo $comment_count ?></span> <?php echo $comment_text ?></span></h2>
			</header>
			<div class="p-comments__list">
				<?php
				wp_list_comments( [
					'type'         => 'comment',
					'style'        => 'div',
					'walker'       => new WalkerComment(),
					'callback'     => function ( $comment, $args, $depth ) {
						$comment_date = sprintf( __( '%1$s at %2$s', 'devlog' ), get_comment_date( 'Y.m.d' ), get_comment_time() );
						$author_link  = get_comment_author_link();
						echo '<section ' . comment_class( 'p-comments__item', null, null, false ) . ' id="comment-' . get_comment_ID() . '">';
						?>
						<div class="p-comments__itemInner" id="div-comment-<?php comment_ID() ?>">
							<div class="p-comments__itemAuthor">
								<div class="p-comments__itemAuthorImage">
									<?php echo get_avatar( $comment, 50 ) ?>
								</div>
								<div class="p-comments__itemAuthorMeta">
									<h3 class="p-comments__itemAuthorName"><?php echo $author_link ?></h3>
									<div class="p-comments__itemAuthorDate"><?php echo $comment_date ?><?php edit_comment_link( __( 'Edit this comment', 'devlog' ) ) ?></div>
								</div>
							</div>
							<div class="p-comments__itemContent">
								<?php comment_text() ?>
								<?php
								comment_reply_link( array_merge( $args, array(
									'add_below' => 'div-comment',
									'depth'     => $depth,
									'max_depth' => $args['max_depth'],
									'reply_text'    => __( 'REPLY', 'devlog' ),
									'reply_to_text' => __( 'REPLY TO %s', 'devlog' ),
									'login_text'    => __( 'LOG IN TO REPLY', 'devlog' ),
									'before'    => '<div class="p-comments__itemAction">',
									'after'     => '</div>',
								) ) );
								?>
							</div>
						</div>
						<?php
					},
					'end-callback' => function () {
						echo '</section>';
					},
				] );
				?>
			</div>
			<?php
		} else {
			?>
			<header class="c-section__header">
				<h2 class="c-section__title"><span class="u-point"><span class="u-point__bold">NO</span>COMMENTS</span></h2>
				<p class="c-section__sub-title">まだコメントはありません</p>
			</header>
			<?php
		}
		?>
		<?php
		if ( comments_open() ) {
			?>
			<div class="p-comment-form" id="respond">
				<?php
				ob_start();
				comment_form( [
					'title_reply'    => '<span class="u-point">LEAVE<span class="u-point__bold">YOUR</span>COMMENT</span>',
					'title_reply_to' => '<span class="u-point">REPLY<span class="u-point__bold">TO</span><span class="p-comment-form__reply-to">%s</span></span>',
					'format'         => 'html5',
				] );

				echo str_replace( ' id="respond"', '', ob_get_clean() );
				?>
			</div>
			<?php
		}
		?>
	</div>
</section>