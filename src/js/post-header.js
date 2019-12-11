import $ from 'jquery';
import Granim from 'granim';

class PostHeader {
	/**
	 * Constructor
	 */
	constructor() {
		this.$postHeader = $('#post-header');
		this.$gradientCanvasDesktop = $('#gradient-canvas-desktop');
		this.$gradientCanvasMobile = $('#gradient-canvas-mobile');
		this.postHeaderGranim;

		window.context.ui.mql.addListener(mql => {
			this.applyResponsiveEvent(mql)
		});

		this.applyResponsiveEvent(window.context.ui.mql)
	}

	/**
	 * Responsive Event
	 *
	 * @param mql
	 */
	applyResponsiveEvent(mql) {
		this.setupGradientAnimation(mql);
	}

	/**
	 * Setup gradient animation with granim.js
	 *
	 * @param mql
	 */
	setupGradientAnimation(mql) {
		if (this.postHeaderGranim) {
			this.postHeaderGranim.destroy();
		}

		if (this.$postHeader.length) {
			const colors = this.$postHeader.data('colors');

			if (Array.isArray(colors) && colors.length > 0) {
				let options = {
					name: 'granim',
					direction: 'diagonal',
					elToSetClassOn: '#post-header',
					isPausedWhenNotInView: true,
					states: {
						"default-state": {
							gradients: colors,
							transitionSpeed: 5000
						}
					}
				};

				if (mql.matches) {
					if (this.$gradientCanvasMobile.length) {
						options.element = '#' + this.$gradientCanvasMobile.attr('id');
						this.postHeaderGranim = new Granim(options);
					}

				} else {
					if (this.$gradientCanvasDesktop.length) {
						options.element = '#' + this.$gradientCanvasDesktop.attr('id');
						this.postHeaderGranim = new Granim(options);
					}
				}
			}
		}
	}
}

$(() => {
	new PostHeader();
});