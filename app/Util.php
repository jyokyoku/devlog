<?php

namespace DevLog;

use Iwf2b\Arr;
use OzdemirBurak\Iris\Color\Hex;
use OzdemirBurak\Iris\Exceptions\InvalidColorException;

/**
 * Class Util
 * @package DevLog
 */
class Util {
	/**
	 * @param $base_color
	 * @param int $length
	 * @param array $args
	 *
	 * @return array
	 */
	public static function generate_gradient_colors( $base_color, $length = 5, array $args = [] ) {
		$args = Arr::merge_intersect_key( [
			'lightness_upper' => 10,
			'lightness_lower' => - 10,
			'spin_upper'      => 10,
			'spin_lower'      => - 10,
			'saturate_upper'  => 40,
			'saturate_lower'  => 0,
		], $args );

		$colors         = [];
		$lightness_diff = abs( $args['lightness_upper'] ) + ( $args['lightness_lower'] >= 0 ? - $args['lightness_lower'] : abs( $args['lightness_lower'] ) );
		$spin_diff      = abs( $args['spin_upper'] ) + ( $args['spin_lower'] >= 0 ? - $args['spin_lower'] : abs( $args['spin_lower'] ) );
		$saturate_diff  = abs( $args['saturate_upper'] ) + ( $args['saturate_lower'] >= 0 ? - $args['saturate_lower'] : abs( $args['saturate_lower'] ) );

		try {
			for ( $i = 0; $i < $length; $i ++ ) {
				$color_1 = new Hex( $base_color );
				$color_2 = new Hex( $base_color );

				$lightness = mt_rand( $args['lightness_upper'] - round( $lightness_diff / 2 ), $args['lightness_upper'] );
				$darkness  = mt_rand( $args['lightness_lower'], $args['lightness_lower'] + round( $lightness_diff / 2 ) );

				$spins = [
					mt_rand( $args['spin_upper'] - round( $spin_diff / 2 ), $args['spin_upper'] ),
					mt_rand( $args['spin_lower'], $args['spin_lower'] + round( $spin_diff / 2 ) ),
				];

				$saturates = [
					mt_rand( $args['saturate_upper'] - round( $spin_diff / 2 ), $args['saturate_upper'] ),
					mt_rand( $args['saturate_lower'], $args['saturate_lower'] + round( $saturate_diff / 2 ) ),
				];

				shuffle( $spins );
				shuffle( $saturates );

				$colors[] = [
					(string) $color_1->lighten( $lightness )->spin( $spins[0] )->saturate( $saturates[0] ),
					$base_color,
					(string) $color_2->lighten( $darkness )->spin( $spins[1] )->saturate( $saturates[1] ),
				];
			}
		} catch ( InvalidColorException $e ) {
		}

		return $colors;
	}
}