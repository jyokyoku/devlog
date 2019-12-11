import $ from 'jquery';
import 'popper.js';
import 'bootstrap/dist/js/bootstrap.min';
import Granim from 'granim';

export default class UserInterface {
	/**
	 * Constructor
	 */
	constructor() {
		// objects
		this.$headerTitle = $('.l-header__title');
		this.$searchBtn = $('.l-header__search-btn');
		this.$searchArea = $('.l-header__search');
		this.$navTrigger = $('.l-nav-trigger');
		this.$overlay = $('.l-overlay');
		this.$sidebar = $('.l-sidebar');

		// events
		this.setupNavToggle();
		this.setupPostItemGradient();

		// mql events
		this.mql = window.matchMedia('screen and (max-width: 768px)');
		this.mql.addListener(mql => {
			this.applyResponsiveEvent(mql)
		});

		this.applyResponsiveEvent(this.mql);
	}

	/**
	 * Responsive Event
	 *
	 * @param mql
	 */
	applyResponsiveEvent(mql) {
		this.setupSidebarHeight(mql);
		this.setupSearchForm(mql);
	}

	/**
	 * Setup automatic sidebar height event
	 *
	 * @param mql
	 */
	setupSidebarHeight(mql) {
		if (mql.matches) {
			$(window).on('resize.sidebar', e => {
				const winHeight = window.innerHeight;
				this.$sidebar.height(winHeight);
			}).trigger('resize');

		} else {
			$(window).off('.sidebar');

			if (this.$sidebar.is('.l-sidebar--with-admin-bar')) {
				this.$sidebar.css({height: 'calc(100vh - 32px)'});

			} else {
				this.$sidebar.css({height: '100vh'});
			}
		}
	}

	/**
	 * Setup search form animation
	 *
	 * @param mql
	 */
	setupSearchForm(mql) {
		if (mql.matches) {
			this.$searchBtn.off('.searchFormDesktop');
			this.$searchArea.removeClass('is-open');

		} else {
			this.$searchBtn.on('click.searchFormDesktop', e => {
				if (this.$searchArea.hasClass('is-open')) {
					this.$searchArea.removeClass('is-open');

				} else {
					this.$searchArea.addClass('is-open');
				}

				return false;
			});
		}
	}

	/**
	 * Setup post item gradient
	 */
	setupPostItemGradient() {
		const $postItems = $('.p-post-list__item');

		$postItems.each((i, elm) => {
			const $elm = $(elm);
			const colors = $elm.data('colors');

			if (Array.isArray(colors) && colors.length > 0) {
				let options = {
					name: 'granim',
					direction: 'diagonal',
					isPausedWhenNotInView: true,
					states: {
						"default-state": {
							gradients: colors,
							transitionSpeed: 5000
						}
					}
				};

				const $gradientCanvas = $(elm).find('canvas');

				if ($gradientCanvas.length) {
					options.element = '#' + $gradientCanvas.attr('id');
					$elm.data('granim', new Granim(options));
				}
			}
		});
	}

	/**
	 * Setup navigation toggle
	 */
	setupNavToggle() {
		this.$navTrigger.on('click', e => {
			this.toggleSideNav();

			return false;
		});

		this.$overlay.on('click', e => {
			this.toggleSideNav();

			return false;
		});

		this.$sidebar.find('.widget_categories > ul > .cat-item, .widget_nav_menu .menu > li, .widget_archive > ul > li').each((i, elm) => {
			const html = $(elm).html();
			$(elm).html(html.replace(/<\/a>((?:\s|&nbsp;)\([\d]+?\))/g, '<span class="number">$1</span></a>'));
		});

		this.$sidebar.find('ul.children, ul.sub-menu').before($('<span class="toggle"><svg><use xlink:href="#svg-arrowDown"></use></svg></span>'));

		this.$sidebar.find('[name="archive-dropdown"]').wrap('<div class="archive-dropdown"></div>');

		this.$sidebar.find('.toggle').on('click', e => {
			const $this = $(e.currentTarget);
			$this.parent().toggleClass('is-open').find('> ul').slideToggle(200);

			return false;
		});
	}

	/**
	 * Toggle side navigation
	 */
	toggleSideNav() {
		if (this.$navTrigger.hasClass('is-open')) {
			this.$navTrigger.removeClass('is-open');
			this.$sidebar.removeClass('is-show');
			this.$headerTitle.removeClass('is-hide');
			this.$overlay.fadeOut(400);

		} else {
			this.$navTrigger.addClass('is-open');
			this.$sidebar.addClass('is-show');
			this.$headerTitle.addClass('is-hide');
			this.$overlay.fadeIn(400);
		}
	};
}