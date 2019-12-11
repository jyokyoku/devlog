import $ from 'jquery';

const {__} = wp.i18n;

class Category {
	constructor() {
		this.$mediaUpload = $('.js-media-upload');

		this.setupColorPicker();
		this.setupMediaUploadFrame();
		this.clearFormOnSubmit();
	}

	setupColorPicker() {
		$('.js-color-picker').wpColorPicker();
	}

	setupMediaUploadFrame() {
		if (this.$mediaUpload.length) {
			this.$mediaUpload.each((i, elm) => {
				let frame;
				const $mediaUpload = $(elm);
				const $mediaContainer = $mediaUpload.find('.js-media-container');
				const $uploadButton = $mediaUpload.find('.js-media-select');
				const $removeButton = $mediaUpload.find('.js-media-remove');
				const $mediaId = $mediaUpload.find('.js-media-id');

				if (!$mediaContainer.length || !$uploadButton.length || !$removeButton.length || !$mediaId.length) {
					return;
				}

				$uploadButton.on('click', e => {
					e.preventDefault();

					if (frame) {
						frame.open();
						return;
					}

					frame = wp.media({
						title: __('Select or Upload Media Of Your Chosen Persuasion', 'devlog'),
						button: {
							text: __('Use this media', 'devlog'),
						},
						multiple: false,
						library: {
							order: 'desc',
							orderby: 'date',
							type: 'image',
						}
					});

					frame.on('select', function () {
						const attachment = frame.state().get('selection').first().toJSON();

						$mediaContainer.append('<img src="' + attachment.url + '" alt="">');
						$uploadButton.addClass('is-hidden');
						$removeButton.removeClass('is-hidden');
						$mediaId.val(attachment.id);
					});

					frame.open();
				});

				$removeButton.on('click', e => {
					e.preventDefault();

					$mediaContainer.html('');
					$removeButton.addClass('is-hidden');
					$uploadButton.removeClass('is-hidden');
					$mediaId.val('');
				});
			});
		}
	}

	clearFormOnSubmit() {
		const $submit = $('#submit');

		if ($submit.length && this.$mediaUpload.length) {
			$submit.on('click', e => {
				const $submit = $(e.currentTarget);
				const $form = $submit.parents('form');

				if (!validateForm($form)) {
					return false;
				}

				this.$mediaUpload.each((i, elm) => {
					const $mediaUpload = $(elm);
					const $mediaContainer = $mediaUpload.find('.js-media-container');
					const $uploadButton = $mediaUpload.find('.js-mediaSelect');
					const $removeButton = $mediaUpload.find('.js-mediaRemove');
					const $mediaId = $mediaUpload.find('.js-mediaId');

					if (!$mediaContainer.length || !$uploadButton.length || !$removeButton.length || !$mediaId.length) {
						return;
					}

					$mediaContainer.html('');
					$uploadButton.removeClass('__hidden');
					$removeButton.addClass('__hidden');
					$mediaId.val('');
				});
			});
		}
	}
}

$(() => {
	new Category();
});
