<?php

namespace DevLog\Post;

use DevLog\Util;

class PagePost extends \Iwf2b\Post\PagePost {
	protected function initialize() {
		parent::initialize();

		add_action( 'wp_head', [ $this, 'header_scripts' ] );
	}

	public function header_scripts() {
		if ( is_singular( static::$post_type ) ) {
			$base_color = '#eeeeee';

			if ( get_queried_object()->theme_color ) {
				$base_color = get_queried_object()->theme_color;
			}

			$colors = Util::generate_gradient_colors( $base_color, 10 );

			if ( $colors ) {
				?>
				<script>
					var mainVisualColors = <?php echo json_encode( $colors ) ?>;
				</script>
				<?php
			}
		}
	}
}