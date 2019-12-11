<?php

namespace DevLog;

use Iwf2b\AbstractSingleton;

/**
 * Class Customizer
 * @package DevLog
 */
class Customizer extends AbstractSingleton {
	/**
	 * {@inheritDoc}
	 */
	protected function initialize() {
		add_action( 'customize_register', [ $this, 'theme_customize_register' ] );
	}

	/**
	 * @param \WP_Customize_Manager $wp_customize
	 */
	public function theme_customize_register( $wp_customize ) {
		$wp_customize->add_section( 'post_theme_color', array(
			'title'    => __( 'Post theme color choices', 'devlog' ), // 項目名
			'priority' => 200, // 優先順位
		) );

		for ( $i = 1; $i <= 6; $i ++ ) {
			$wp_customize->add_setting( 'post_theme_color_' . $i, array(
				'default'   => '',
				'type'      => 'theme_mod',
				'transport' => 'postMessage',
			) );

			$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'post_theme_color_' . $i, array(
				'label'    => sprintf( __( 'Color %s', 'devlog' ), $i ),
				'section'  => 'post_theme_color',
				'settings' => 'post_theme_color_' . $i,
			) ) );
		}
	}
}