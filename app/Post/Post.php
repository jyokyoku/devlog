<?php

namespace DevLog\Post;

use DevLog\Tax\CategoryTax;
use OzdemirBurak\Iris\Color\Hex;

class Post extends \Iwf2b\Post\Post {
	protected static $metas = [
		'catch_phrase_row_1' => [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		],
		'catch_phrase_row_2' => [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		],
		'emphasize_row'      => [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'integer',
		],
		'theme_color'        => [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		],
		'related_posts'      => [
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		],
	];

	protected function initialize() {
		parent::initialize();

		add_action( 'init', [ $this, 'register_post_meta' ] );

		add_action( 'wp_head', [ $this, 'header_styles' ] );

		add_action( 'admin_print_scripts', [ $this, 'admin_print_scripts' ] );

		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_script' ] );

		add_action( 'enqueue_block_assets', [ $this, 'enqueue_block_editor_style' ] );
	}

	public function header_styles() {
		if ( is_singular( static::$post_type ) ) {
			$post     = get_queried_object();
			$category = Post::get_term( $post->ID, CategoryTax::get_slug() );

			if ( $category ) {
				$text_color = CategoryTax::get_meta( $category, 'text_color' );
			}

			if ( $post->text_color ) {
				$text_color = $post->text_color;
			}

			if ( $text_color ) {
				try {
					$alpha_text_color = ( new Hex( $text_color ) )->toRgba();
					$alpha_text_color->alpha( 0.8 );

				} catch ( \Exception $e ) {
				}
				?>
				<style>
					.p-post-header__category svg {
						fill: <?php echo $alpha_text_color ?>;
					}

					.p-post-header__category a {
						color: <?php echo $alpha_text_color ?>;
					}

					.p-post-header__title {
						color: <?php echo $text_color ?>;
					}
				</style>
				<?php
			}
		}
	}

	public function admin_print_scripts() {
		global $pagenow, $post_type;

		if ( ( $pagenow === 'post-new.php' || $pagenow === 'post.php' ) && $post_type === 'post' ) {
			$colors = [];

			foreach ( get_theme_mods() as $mod_key => $mod_value ) {
				if ( strpos( $mod_key, 'post_theme_color_' ) === 0 && ! empty( $mod_value ) ) {
					$colors[] = [ 'color' => $mod_value ];
				}
			}

			if ( $colors ) {
				?>
				<script>
					var postThemeColors = <?php echo json_encode( $colors ) ?>;
				</script>
				<?php
			}
		}
	}

	/**
	 * Register post-meta for block-editor
	 */
	public function register_post_meta() {
		foreach ( static::$metas as $meta_key => $meta_config ) {
			register_post_meta(
				static::$post_type,
				$meta_key,
				$meta_config
			);
		}
	}

	/**
	 * Enqueue block-editor scripts
	 */
	public function enqueue_block_editor_script() {
		global $pagenow, $post_type;

		if ( ( $pagenow === 'post-new.php' || $pagenow === 'post.php' ) && $post_type === 'post' ) {
			wp_enqueue_style( 'devlog-post-sidebar', get_stylesheet_directory_uri() . '/admin/assets/css/post-sidebar.css' );
			wp_enqueue_script( 'devlog-post-sidebar', get_stylesheet_directory_uri() . '/admin/assets/js/post-sidebar.js',
				[
					'wp-plugins',
					'wp-edit-post',
					'wp-element',
					'wp-components',
				]
			);
		}
	}

	/**
	 * Enqueue block-editor styles
	 */
	public function enqueue_block_editor_style() {
		global $pagenow, $post_type;

		if ( ( $pagenow === 'post-new.php' || $pagenow === 'post.php' ) && $post_type === 'post' ) {
			wp_enqueue_style( 'plugin-sidebar-css' );
		}
	}

	/**
	 * Get the related posts array
	 *
	 * @param $post_id
	 *
	 * @return array
	 */
	public static function get_related( $post_id ) {
		if ( ! static::is_valid( $post_id ) ) {
			return [];
		}

		$related_posts = static::get_meta( $post_id, 'related_posts' );

		if ( ! $related_posts ) {
			return [];
		}

		$related_posts = json_decode( $related_posts, true );

		return $related_posts ? array_filter( wp_list_pluck( $related_posts, 'id' ) ) : [];
	}
}