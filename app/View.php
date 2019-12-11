<?php

namespace DevLog;

/**
 * Class View
 * @package DevLog
 */
class View extends \Iwf2b\View {
	/**
	 * @param $text
	 */
	public static function point_text( $text ) {
		$textChunks = array_filter( explode( ' ', $text ) );

		foreach ( $textChunks as $i => $text_chunk ) {
			if ( $i % 2 > 0 ) {
				$textChunks[ $i ] = '<span class="u-point__bold">' . $text_chunk . '</span>';
			}
		}

		echo '<span class="u-point">' . implode( '', $textChunks ) . '</span>';
	}

	/**
	 * @param null $paged
	 * @param null $total_pages
	 * @param int $range
	 */
	public static function pager( $paged = null, $total_pages = null, $range = 5 ) {
		$html = '';

		if ( ! $paged ) {
			$paged = max( 1, get_query_var( 'paged' ) );
		}

		if ( ! $total_pages ) {
			global $wp_query;

			$total_pages = $wp_query->max_num_pages;
		}

		if ( $total_pages > 1 ) {
			$odd = 0;

			if ( $paged <= $range ) {
				$offset = 1;
				$odd    += $range - $paged + 1;

			} else {
				$offset = $paged - $range;
			}

			$max = $paged + $odd + $range;

			if ( $max > $total_pages ) {
				$odd = $max - $total_pages;
				$max = $total_pages;

				if ( $offset > $odd ) {
					$offset -= $odd;

				} else {
					$offset = 1;
				}
			}

			$html .= '<ul class="p-pager">';

			if ( $paged > 1 ) {
				$html .= '<li class="p-pager__item"><a href="' . get_pagenum_link( $paged - 1 ) . '">&lt;</a></li>';
			}

			for ( $i = $offset; $i <= $max; $i ++ ) {
				$html .= ( $paged == $i ) ? '<li class="p-pager__item p-pager__item--current">' : '<li class="p-pager__item">';
				$html .= '<a href="' . get_pagenum_link( $i ) . '">' . $i . '</a>';
				$html .= '</li>';
			}

			if ( $paged < $total_pages ) {
				$html .= '<li class="p-pager__item"><a href="' . get_pagenum_link( $paged + 1 ) . '">&gt;</a></li>';
			}

			$html .= '</ul>';
		}

		echo $html;
	}
}
