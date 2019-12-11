const {__} = wp.i18n;
const {Fragment} = wp.element;
const {registerPlugin} = wp.plugins;
const {PluginSidebar, PluginSidebarMoreMenuItem} = wp.editPost;
const {PanelBody} = wp.components;

import CatchPhrase from './components/catch-phrase';
import ThemeColor from './components/theme-color';
import RelatedPosts from './components/related-posts';
import MainImage from './components/overlay';

registerPlugin('devlog-post-sidebar', {
	icon: (
		<svg xmlns="http://www.w3.org/2000/svg" width="22" height="20" viewBox="0 0 576 512">
			<g className="fa-group">
				<path className="fa-secondary" fill="currentColor" d="M566.64 233.37a32 32 0 0 1 0 45.25l-45.25 45.25a32 32 0 0 0-9.39 22.64V384a96 96 0 0 1-96 96h-48a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h48a32 32 0 0 0 32-32v-37.48a96 96 0 0 1 28.13-67.89L498.76 256l-22.62-22.62A96 96 0 0 1 448 165.47V128a32 32 0 0 0-32-32h-48a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h48a96 96 0 0 1 96 96v37.48a32 32 0 0 0 9.38 22.65l45.25 45.24z" opacity=".4" />
				<path className="fa-primary" fill="currentColor" d="M208 32h-48a96 96 0 0 0-96 96v37.48a32.12 32.12 0 0 1-9.38 22.65L9.38 233.37a32 32 0 0 0 0 45.25l45.25 45.25A32.05 32.05 0 0 1 64 346.51V384a96 96 0 0 0 96 96h48a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16h-48a32 32 0 0 1-32-32v-37.48a96 96 0 0 0-28.13-67.89L77.26 256l22.63-22.63A96 96 0 0 0 128 165.48V128a32 32 0 0 1 32-32h48a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16z" />
			</g>
		</svg>
	),
	render: () => {
		return (
			<Fragment>
				<PluginSidebarMoreMenuItem
					target="devlog-post-sidebar"
				>
					DevLog
				</PluginSidebarMoreMenuItem>
				<PluginSidebar
					name="devlog-post-sidebar"
					title="DevLog"
				>
					<PanelBody
						title={__('Theme color', 'devlog')}
						initialOpen={true}
					>
						<ThemeColor />
					</PanelBody>
					<PanelBody
						title={__('Catch phrase', 'devlog')}
						initialOpen={true}
					>
						<CatchPhrase />
					</PanelBody>
					<PanelBody
						title={__('Related posts', 'devlog')}
						initialOpen={true}
					>
						<RelatedPosts />
					</PanelBody>
				</PluginSidebar>
			</Fragment>
		);
	}
});