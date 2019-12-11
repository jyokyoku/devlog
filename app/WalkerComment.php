<?php

namespace DevLog;

/**
 * Class WalkerComment
 * @package DevLog
 */
class WalkerComment extends \Walker_Comment {
	/**
	 * {@inheritDoc}
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				$output .= '<div class="p-comments__list">' . "\n";
				break;
			case 'ol':
				$output .= '<ol class="p-comments__list">' . "\n";
				break;
			case 'ul':
			default:
				$output .= '<ul class="p-comments__list">' . "\n";
				break;
		}

		ob_start();
		?>
		<div class="p-comments__list-reply-title">
			<span class="u-point">
				<svg>
					<use xlink:href="#svg-comment"></use>
				</svg>
				REPLIES
			</span>
		</div>
		<?php
		$output .= ob_get_clean();
	}

	/**
	 * {@inheritDoc}
	 */
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1;

		switch ( $args['style'] ) {
			case 'div':
				$output .= "</div><!-- .children -->\n";
				break;
			case 'ol':
				$output .= "</ol><!-- .children -->\n";
				break;
			case 'ul':
			default:
				$output .= "</ul><!-- .children -->\n";
				break;
		}
	}
}