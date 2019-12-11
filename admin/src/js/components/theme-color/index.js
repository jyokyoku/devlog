import connectSimpleMeta from '../../utils/connect-simple-meta';

const {__} = wp.i18n;
const {ColorPalette} = wp.components;
const {Fragment} = wp.element;

export default connectSimpleMeta(({value, setValue}) => {
	const colors = typeof window.postThemeColors === 'object' ? window.postThemeColors : {};

	const handleChange = (value) => {
		setValue(value);
	};

	return (
		<Fragment>
			<ColorPalette
				colors={colors}
				value={value}
				onChange={handleChange}
			/>
			<p>{__('If not selected, the category theme color is used.', 'devlog')}</p>
		</Fragment>
	)
}, 'theme_color');