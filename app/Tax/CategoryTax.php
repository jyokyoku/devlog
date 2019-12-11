<?php

namespace DevLog\Tax;

use Iwf2b\Arr;

class CategoryTax extends \Iwf2b\Tax\CategoryTax {
	protected $custom_fields = [];

	protected function initialize() {
		parent::initialize();

		$this->custom_fields['theme_color'] = [
			'label' => __( 'Theme color', 'devlog' ),
			'type'  => 'text',
			'attr'  => [
				'type'  => 'text',
				'class' => 'js-color-picker',
			],
		];

		$this->custom_fields['text_color'] = [
			'label' => __( 'Text color', 'devlog' ),
			'type'  => 'text',
			'attr'  => [
				'type'  => 'text',
				'class' => 'js-color-picker',
			],
		];

		$this->custom_fields['icon'] = [
			'label' => __( 'Icon', 'devlog' ),
			'type'  => 'image',
		];

		add_action( static::$taxonomy . '_add_form_fields', [ $this, 'render_add_custom_field' ] );

		add_action( static::$taxonomy . '_edit_form_fields', [ $this, 'render_edit_custom_field' ] );

		add_action( 'admin_print_styles', [ $this, 'admin_enqueue_styles' ] );

		add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );

		add_action( 'admin_print_scripts', [ $this, 'admin_enqueue_scripts' ] );

		add_action( 'create_' . static::$taxonomy, [ $this, 'save_custom_fields' ] );

		add_action( 'edit_' . static::$taxonomy, [ $this, 'save_custom_fields' ] );
	}

	/**
	 * Enqueue admin styles
	 */
	public function admin_enqueue_styles() {
		global $pagenow, $taxonomy;

		if ( ( $pagenow === 'edit-tags.php' || $pagenow === 'term.php' ) && $taxonomy === static::get_slug() ) {
			wp_enqueue_style( 'wp-color-picker' );
		}
	}

	/**
	 * Enqueue admin scripts
	 */
	public function admin_enqueue_scripts() {
		global $pagenow, $taxonomy;

		if ( ( $pagenow === 'edit-tags.php' || $pagenow === 'term.php' ) && $taxonomy === static::get_slug() ) {
			wp_enqueue_style( 'devlog-category', get_stylesheet_directory_uri() . '/admin/assets/css/category.css' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'devlog-category', get_stylesheet_directory_uri() . '/admin/assets/js/category.js', [ 'wp-i18n', 'wp-color-picker' ] );
		}
	}

	/**
	 * Save custom fields
	 *
	 * @param $term_id
	 */
	public function save_custom_fields( $term_id ) {
		foreach ( $this->custom_fields as $field_key => $field_config ) {
			if ( Arr::exists( $_POST, $field_key ) ) {
				$value = Arr::get( $_POST, $field_key );

				update_term_meta( $term_id, $field_key, $value );
			}
		}
	}

	/**
	 *  Render custom field in create panel (edit-tags.php)
	 */
	public function render_add_custom_field() {
		foreach ( $this->custom_fields as $field_key => $field_config ) {
			if ( $field_config['type'] === 'image' ) {
				?>
				<div class="form-field term-slug-wrap">
					<label><?php echo $field_config['label'] ?></label>
					<?php echo $this->render_media_field( $field_key, $field_config ) ?>
				</div>
				<?php
			} else {
				?>
				<div class="form-field term-slug-wrap">
					<label><?php echo $field_config['label'] ?></label>
					<?php echo call_user_func( "Iwf2b\\Form\\Form::{$field_config['type']}", $field_key, $field_config['attr'] ) ?>
				</div>
				<?php
			}
		}
	}

	/**
	 * Render custom field in edit panel (term.php)
	 *
	 * @param \WP_Term $term
	 */
	public function render_edit_custom_field( $term ) {
		foreach ( $this->custom_fields as $field_key => $field_config ) {
			$value = get_term_meta( $term->term_id, $field_key, true );

			if ( $field_config['type'] === 'image' ) {
				?>
				<tr class="form-field term-description-wrap">
					<th scope="row"><label for="description"><?php echo $field_config['label'] ?></label></th>
					<td>
						<?php echo $this->render_media_field( $field_key, $field_config, $term ) ?>
					</td>
				</tr>
				<?php
			} else {
				?>
				<tr class="form-field term-description-wrap">
					<th scope="row"><label for="description"><?php echo $field_config['label'] ?></label></th>
					<td>
						<?php
						$form = call_user_func( "Iwf2b\\Form\\Form::{$field_config['type']}", $field_key, $field_config['attr'] );
						echo $value ? $form->set_value( $value ) : $form;
						?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		<?php
	}

	/**
	 * @param $field_key
	 * @param $field_config
	 * @param \WP_Term $term
	 *
	 * @return string
	 */
	protected function render_media_field( $field_key, $field_config, $term = null ) {
		$upload_link   = esc_url( get_upload_iframe_src( 'image' ) );
		$attachment_id = $term ? get_term_meta( $term->term_id, $field_key, true ) : null;
		$img_attr      = $term ? wp_get_attachment_image_src( $attachment_id, 'full' ) : false;

		ob_start();
		?>
		<div class="dl-media-upload js-media-upload">
			<div class="dl-media-upload__img js-media-container">
				<?php
				if ( $img_attr ) {
					?>
					<img src="<?php echo $img_attr[0] ?>" alt="">
					<?php
				}
				?>
			</div>
			<p class="dl-media-upload__action">
				<a class="dl-media-upload__action-btn dl-media-upload__action-btn--select js-media-select<?php echo $img_attr ? ' is-hidden' : '' ?>" href="<?php echo $upload_link ?>"><?php _e( 'Set custom image', 'devlog' ) ?></a>
				<a class="dl-media-upload__action-btn dl-media-upload__action-btn--remove js-media-remove<?php echo $img_attr ? '' : ' is-hidden' ?>" href="#"><?php _e( 'Remove this image', 'devlog' ) ?></a>
			</p>
			<input class="js-media-id" name="<?php echo $field_key ?>" type="hidden" value="<?php echo esc_attr( $attachment_id ); ?>" />
		</div>
		<?php
		return ob_get_clean();
	}
}