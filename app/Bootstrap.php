<?php

namespace DevLog;

use Iwf2b\AbstractSingleton;

/**
 * Class Bootstrap
 * @package DevLog
 */
class Bootstrap extends AbstractSingleton {
	/**
	 * {@inheritDoc}
	 */
	protected function initialize() {
		add_action( 'wp_head', [ $this, 'header_scripts' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_action( 'pre_get_posts', [ $this, 'query_filter' ] );
	}

	/**
	 * Common header scripts
	 */
	public function header_scripts() {
		if ( is_admin_bar_showing() ) {
			?>
			<style>
				html {
					height: calc(100% - 32px);
				}

				@media screen and (max-width: 782px) {
					html {
						height: calc(100% - 46px);
					}
				}
			</style>
			<?php
		}
	}

	/**
	 * Enqueue admin styles
	 */
	public function admin_enqueue_styles() {
		wp_enqueue_style( 'devlog-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:300,400,700|Roboto:300,400,700|Roboto+Mono&display=swap&subset=japanese' );
		wp_enqueue_style( 'devlog-reset-block-editor', get_stylesheet_directory_uri() . '/admin/assets/css/reset-block-editor.css' );
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		// no use
	}

	/**
	 * Enqueue frontend styles
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'devlog-fonts', 'https://fonts.googleapis.com/css?family=Noto+Sans+JP:300,400,700|Roboto:300,400,700|Roboto+Mono&display=swap&subset=japanese' );
		wp_enqueue_style( 'devlog-style', get_stylesheet_directory_uri() . '/assets/css/style.css' );
	}

	/**
	 * Enqueue frontend scripts
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'devlog-common', get_stylesheet_directory_uri() . '/assets/js/common.js' );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		if ( is_singular() ) {
			wp_enqueue_script( 'devlog-post-header', get_stylesheet_directory_uri() . '/assets/js/post-header.js' );
		}
	}

	/**
	 * Filter the query
	 *
	 * @param \WP_Query $the_query
	 */
	public function query_filter( $the_query ) {
		if ( ! is_admin() ) {
			if ( $the_query->is_search() ) {
				$the_query->set( 'post_type', 'post' );
			}
		}
	}
}